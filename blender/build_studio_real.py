"""
Versión 2 (fotorrealista): misma escena que build_studio.py, con materiales PBR
procedurales, luz nocturna, desenfoque de cámara y bloom. En lugar de exportar
a three.js, renderiza el recorrido de cámara como secuencia de fotogramas que
la web reproduce con el scroll.

Uso:
  blender -b --python blender/build_studio_real.py -- stills <salida> [samples]
  blender -b --python blender/build_studio_real.py -- frames <salida> [samples] [desde] [hasta]

frames: N fotogramas por tramo entre cámaras + variantes del monitor (p_1..p_4)
para la sección de proyectos. Salta los fotogramas que ya existan (reanudable).
"""

import importlib.util
import json
import math
import os
import sys

import bpy
from mathutils import Vector

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.dirname(HERE)
_spec = importlib.util.spec_from_file_location("studio", os.path.join(HERE, "build_studio.py"))
studio = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(studio)

argv = sys.argv[sys.argv.index("--") + 1 :] if "--" in sys.argv else []
MODE = argv[0] if argv else "stills"
OUT = os.path.abspath(argv[1] if len(argv) > 1 else ".")
SAMPLES = int(argv[2]) if len(argv) > 2 else 128
FROM = int(argv[3]) if len(argv) > 3 else 0
TO = int(argv[4]) if len(argv) > 4 else 10**6
os.makedirs(OUT, exist_ok=True)

PER_SEGMENT = 60  # 60 fotogramas por tramo: transiciones fluidas al hacer scroll
RES = (1920, 1080)
PROJECT_IMAGES = [
    "Ordo.png",
    "PuntoDeVentaInventario.png",
    "ClinicaOdontologica.png",
    "ServicioComunitario.png",
    "RecomendadorLibrosIA.png",
]
# Apertura por cámara: planos amplios nítidos, primeros planos con bokeh
FSTOPS = [11.0, 4.0, 2.2, 3.2, 3.5, 11.0]
# Debe coincidir con CAMERA_KEYS (src/components/three/cameraPath.ts)
SHIFTS = [-0.2, -0.14, -0.18, -0.08, 0.16, 0.18]

lin = studio.hex_rgb


# ------------------------------------------------------------ nodos de apoyo


class Mat:
    """Árbol de nodos limpio con un Principled BSDF y coordenadas de objeto en metros."""

    def __init__(self, m):
        self.m = m
        self.nt = m.node_tree
        for n in list(self.nt.nodes):
            self.nt.nodes.remove(n)
        self.out = self.nt.nodes.new("ShaderNodeOutputMaterial")
        self.bsdf = self.nt.nodes.new("ShaderNodeBsdfPrincipled")
        self.nt.links.new(self.bsdf.outputs[0], self.out.inputs["Surface"])
        self.coord = self.nt.nodes.new("ShaderNodeTexCoord").outputs["Object"]

    def link(self, a, b):
        self.nt.links.new(a, b)

    def set(self, **kw):
        names = {
            "color": "Base Color",
            "rough": "Roughness",
            "metal": "Metallic",
            "coat": "Coat Weight",
            "coat_rough": "Coat Roughness",
            "sheen": "Sheen Weight",
            "sss": "Subsurface Weight",
            "trans": "Transmission Weight",
            "aniso": "Anisotropic",
            "emit_color": "Emission Color",
            "emit": "Emission Strength",
        }
        for k, v in kw.items():
            sock = self.bsdf.inputs[names[k]]
            if hasattr(v, "is_output"):
                self.link(v, sock)
            else:
                sock.default_value = lin(v) if isinstance(v, str) else v
        return self

    def scaled(self, scale):
        mp = self.nt.nodes.new("ShaderNodeMapping")
        mp.inputs["Scale"].default_value = scale
        self.link(self.coord, mp.inputs["Vector"])
        return mp.outputs["Vector"]

    def noise(self, scale, detail=6.0, rough=0.6, vec=None):
        n = self.nt.nodes.new("ShaderNodeTexNoise")
        n.inputs["Scale"].default_value = scale
        n.inputs["Detail"].default_value = detail
        n.inputs["Roughness"].default_value = rough
        self.link(vec or self.coord, n.inputs["Vector"])
        return n.outputs["Fac"]

    def voronoi(self, scale, vec=None):
        n = self.nt.nodes.new("ShaderNodeTexVoronoi")
        n.inputs["Scale"].default_value = scale
        self.link(vec or self.coord, n.inputs["Vector"])
        return n.outputs["Distance"]

    def ramp(self, fac, c1, c2, p1=0.0, p2=1.0):
        r = self.nt.nodes.new("ShaderNodeValToRGB")
        e = r.color_ramp.elements
        e[0].position, e[0].color = p1, lin(c1)
        e[1].position, e[1].color = p2, lin(c2)
        self.link(fac, r.inputs["Fac"])
        return r.outputs["Color"]

    def frange(self, fac, lo, hi):
        mr = self.nt.nodes.new("ShaderNodeMapRange")
        mr.inputs["To Min"].default_value = lo
        mr.inputs["To Max"].default_value = hi
        self.link(fac, mr.inputs["Value"])
        return mr.outputs["Result"]

    def mix(self, a, b, fac=1.0, mode="MULTIPLY"):
        mx = self.nt.nodes.new("ShaderNodeMix")
        mx.data_type = "RGBA"
        mx.blend_type = mode
        mx.inputs[0].default_value = fac
        self.link(a, mx.inputs[6])
        self.link(b, mx.inputs[7])
        return mx.outputs[2]

    def bump(self, height, strength, distance=0.02, normal=None, invert=False):
        b = self.nt.nodes.new("ShaderNodeBump")
        b.invert = invert
        b.inputs["Strength"].default_value = strength
        b.inputs["Distance"].default_value = distance
        self.link(height, b.inputs["Height"])
        if normal is not None:
            self.link(normal, b.inputs["Normal"])
        self.link(b.outputs["Normal"], self.bsdf.inputs["Normal"])
        return b.outputs["Normal"]


def base_color(m):
    try:
        c = m.node_tree.nodes["Principled BSDF"].inputs["Base Color"].default_value
        return "#%02x%02x%02x" % tuple(round(255 * (x * 12.92 if x <= 0.0031308 else 1.055 * x ** (1 / 2.4) - 0.055)) for x in c[:3])
    except Exception:
        return "#808080"


# ---------------------------------------------------------------- materiales


def plaster(m, color):
    M = Mat(m)
    var = M.ramp(M.noise(2.5, 4), shade(color, 0.9), shade(color, 1.06))
    M.set(color=var, rough=0.88)
    M.bump(M.noise(55, 10, 0.7), 0.06, 0.01)


def floor_planks(m):
    M = Mat(m)
    brick = M.nt.nodes.new("ShaderNodeTexBrick")
    brick.offset = 0.5
    brick.inputs["Color1"].default_value = lin("#6e4a32")
    brick.inputs["Color2"].default_value = lin("#5a3a26")
    brick.inputs["Mortar"].default_value = lin("#1a100a")
    brick.inputs["Scale"].default_value = 1.0
    brick.inputs["Mortar Size"].default_value = 0.0025
    brick.inputs["Brick Width"].default_value = 1.4
    brick.inputs["Row Height"].default_value = 0.19
    M.link(M.coord, brick.inputs["Vector"])
    wave = M.nt.nodes.new("ShaderNodeTexWave")
    wave.bands_direction = "Y"
    wave.inputs["Scale"].default_value = 5.0
    wave.inputs["Distortion"].default_value = 12.0
    wave.inputs["Detail"].default_value = 4.0
    M.link(M.scaled((0.15, 1.0, 1.0)), wave.inputs["Vector"])
    grain = M.ramp(wave.outputs["Fac"], "#a08878", "#ffffff", 0.0, 0.8)
    color = M.mix(brick.outputs["Color"], grain, 0.4)
    M.set(color=color, rough=M.frange(wave.outputs["Fac"], 0.42, 0.28), coat=0.15, coat_rough=0.2)
    n = M.bump(brick.outputs["Fac"], 0.35, 0.003, invert=True)
    M.bump(wave.outputs["Fac"], 0.04, 0.002, normal=n)


def walnut(m):
    M = Mat(m)
    wave = M.nt.nodes.new("ShaderNodeTexWave")
    wave.bands_direction = "Y"
    wave.inputs["Scale"].default_value = 2.2
    wave.inputs["Distortion"].default_value = 14.0
    wave.inputs["Detail"].default_value = 8.0
    wave.inputs["Detail Roughness"].default_value = 0.7
    M.link(M.scaled((0.25, 1.0, 1.0)), wave.inputs["Vector"])
    grain = M.ramp(wave.outputs["Fac"], "#3c2414", "#563420", 0.2, 0.9)
    color = M.mix(grain, M.ramp(M.noise(4, 6), "#c8b8a8", "#ffffff"), 1.0)
    M.set(color=color, rough=0.32, coat=0.4, coat_rough=0.1)
    M.bump(wave.outputs["Fac"], 0.02, 0.001)


def aluminum(m):
    M = Mat(m)
    M.set(color="#7a7d86", metal=1.0, rough=0.3, aniso=0.4)
    M.bump(M.noise(900, 2), 0.015, 0.001)


def plastic(m, color, rough=0.42, micro=0.03):
    M = Mat(m)
    M.set(color=color, rough=rough)
    if micro:
        M.bump(M.noise(1200, 2), micro, 0.001)


def fabric(m, color, scale=320, strength=0.25, sheen=0.6):
    M = Mat(m)
    var = M.ramp(M.noise(6, 3), shade(color, 0.85), shade(color, 1.12))
    M.set(color=var, rough=0.95, sheen=sheen)
    M.bump(M.voronoi(scale), strength, 0.002)


def ceramic(m, color):
    Mat(m).set(color=color, rough=0.12, coat=0.6, coat_rough=0.04)


def terracotta(m, color):
    M = Mat(m)
    var = M.ramp(M.noise(12, 6), shade(color, 0.82), shade(color, 1.1))
    M.set(color=var, rough=0.85)
    M.bump(M.noise(140, 8), 0.12, 0.003)


def soil(m):
    M = Mat(m)
    M.set(color=M.ramp(M.noise(60, 8), "#140d08", "#3a2717"), rough=1.0)
    M.bump(M.voronoi(90), 0.6, 0.004)


def leaf(m, color):
    M = Mat(m)
    var = M.ramp(M.noise(18, 4), shade(color, 0.8), shade(color, 1.15))
    M.set(color=var, rough=0.42, coat=0.25, coat_rough=0.3, sss=0.15)
    M.bump(M.noise(70, 4), 0.06, 0.002)


def cork(m):
    M = Mat(m)
    var = M.ramp(M.noise(90, 10, 0.8), "#8a6238", "#c79a62")
    M.set(color=var, rough=0.95)
    M.bump(M.voronoi(220), 0.35, 0.002)


def paper(m, color):
    M = Mat(m)
    M.set(color=color, rough=0.82, sheen=0.2)
    M.bump(M.noise(400, 6), 0.03, 0.001)


def metal_gold(m):
    Mat(m).set(color="#d4a24a", metal=1.0, rough=0.22)


def emissive(m, color, strength, base="#111111"):
    Mat(m).set(color=base, rough=0.3, emit_color=color, emit=strength)


def screen(m, image_path, strength=1.4):
    M = Mat(m)
    tex = M.nt.nodes.new("ShaderNodeTexImage")
    tex.image = bpy.data.images.load(image_path, check_existing=True)
    tex.interpolation = "Cubic"
    uv = M.nt.nodes.new("ShaderNodeTexCoord").outputs["UV"]
    M.link(uv, tex.inputs["Vector"])
    # Vidrio negro brillante que además emite la imagen: refleja la habitación como una pantalla real
    M.set(color="#050506", rough=0.12, emit=strength)
    M.link(tex.outputs["Color"], M.bsdf.inputs["Emission Color"])
    return tex


def shade(hex_color, f):
    h = hex_color.lstrip("#")
    r, g, b = (min(255, int(int(h[i : i + 2], 16) * f)) for i in (0, 2, 4))
    return "#%02x%02x%02x" % (r, g, b)


def realify():
    by = {m.name: m for m in bpy.data.materials}
    rules = {
        "wall": lambda m: plaster(m, "#30344f"),
        "floor": floor_planks,
        "trim": lambda m: plastic(m, "#1c1e29", 0.5, 0),
        "desk": walnut,
        "metal": aluminum,
        "black": lambda m: plastic(m, "#111216", 0.45),
        "bezel": lambda m: plastic(m, "#0a0b0d", 0.22, 0),
        "key": lambda m: plastic(m, "#e8e4dc", 0.55, 0.05),
        "key_mod": lambda m: plastic(m, "#9aa0b4", 0.55, 0.05),
        "key_accent": lambda m: plastic(m, "#5b5ef0", 0.5, 0.05),
        "mat_pad": lambda m: fabric(m, "#15172a", 600, 0.12, 0.1),
        "chair": lambda m: fabric(m, "#1b1e2d", 380, 0.3, 0.2),
        "rug": lambda m: fabric(m, "#1d2256", 160, 0.45, 0.25),
        "ceramic": lambda m: ceramic(m, "#f1eee8"),
        "coffee": lambda m: Mat(m).set(color="#1a0c05", rough=0.03),
        "pot": lambda m: terracotta(m, "#b8603f"),
        "soil": soil,
        "leaf": lambda m: leaf(m, "#3d7f4c"),
        "leaf2": lambda m: leaf(m, "#2c6a40"),
        "paper": lambda m: paper(m, "#efe9dc"),
        "frame": lambda m: plastic(m, "#141519", 0.3, 0),
        "gold": metal_gold,
        "cork": cork,
        "neon": lambda m: emissive(m, studio.RED, 28.0, "#400000"),
        "led_indigo": lambda m: emissive(m, studio.INDIGO, 30.0),
        "led_cyan": lambda m: emissive(m, studio.CYAN, 18.0),
        "bulb": lambda m: emissive(m, "#ffd9a0", 45.0, "#fff3e0"),
        "docker": lambda m: plastic(m, "#2496ed", 0.32, 0.02),
        "seal": lambda m: ceramic(m, "#a00d20"),
        "ink": lambda m: plastic(m, "#3a3832", 0.9, 0),
    }
    for name, fn in rules.items():
        if name in by:
            fn(by[name])
    for name, m in by.items():
        if name.startswith("book"):
            fabric(m, base_color(m), 500, 0.08, 0.2)
        elif name.startswith("note"):
            paper(m, base_color(m))


def per_object_screens():
    tex_dir = os.path.join(HERE, "textures")
    proj_dir = os.path.join(ROOT, "public", "images", "projects")
    main = bpy.data.objects["screen_main"]
    m = bpy.data.materials.new("scr_main")
    main_tex = screen(m, os.path.join(proj_dir, PROJECT_IMAGES[0]))
    main.data.materials[0] = m
    for obj_name, img in (("screen_side", "code.png"), ("screen_laptop", "terminal.png")):
        m = bpy.data.materials.new("scr_" + obj_name)
        screen(m, os.path.join(tex_dir, img), 1.2)
        bpy.data.objects[obj_name].data.materials[0] = m
    return main_tex, proj_dir


# ------------------------------------------- texturas escaneadas (Poly Haven)

ASSETS = os.path.join(HERE, "assets")


def scanned(m, tex_id, meters, tint=None, rot=0.0, coat=0.0, normal_strength=1.0):
    """Material con texturas CC0 proyectadas en caja sobre coordenadas de objeto (en metros)."""
    M = Mat(m)
    vec = M.scaled((1 / meters,) * 3)
    if rot:
        mp = M.nt.nodes.new("ShaderNodeMapping")
        mp.inputs["Rotation"].default_value = (0, 0, math.radians(rot))
        M.link(vec, mp.inputs["Vector"])
        vec = mp.outputs["Vector"]

    def tex(name, color):
        n = M.nt.nodes.new("ShaderNodeTexImage")
        n.image = bpy.data.images.load(os.path.join(ASSETS, "textures", tex_id, name + ".jpg"), check_existing=True)
        n.image.colorspace_settings.name = "sRGB" if color else "Non-Color"
        n.projection = "BOX"
        n.projection_blend = 0.25
        M.link(vec, n.inputs["Vector"])
        return n.outputs["Color"]

    color = tex("Diffuse", True)
    if tint:
        rgb = M.nt.nodes.new("ShaderNodeRGB")
        rgb.outputs[0].default_value = lin(tint)
        color = M.mix(color, rgb.outputs[0], 1.0)
    M.set(color=color, rough=tex("Rough", False), coat=coat, coat_rough=0.12)
    nm = M.nt.nodes.new("ShaderNodeNormalMap")
    nm.inputs["Strength"].default_value = normal_strength
    M.link(tex("nor_gl", False), nm.inputs["Color"])
    M.link(nm.outputs["Normal"], M.bsdf.inputs["Normal"])


def scanned_materials():
    by = {m.name: m for m in bpy.data.materials}
    scanned(by["floor"], "wood_floor", 2.0, coat=0.1)
    # Yeso blanco teñido del azul nocturno de la escena
    scanned(by["wall"], "white_plaster_02", 2.0, tint="#4d5486", normal_strength=0.6)
    scanned(by["desk"], "american_walnut_veneer", 1.2, tint="#a8714a", coat=0.35)
    scanned(by["rug"], "wool_boucle", 0.12, tint="#3a44a8", normal_strength=0.7)
    # Repisas: misma madera con la veta a lo largo (eje Y)
    shelf = bpy.data.materials.new("shelf_wood")
    scanned(shelf, "american_walnut_veneer", 1.2, tint="#a8714a", rot=90, coat=0.3)
    for o in bpy.data.objects:
        if o.name.startswith("shelf_"):
            o.data.materials[0] = shelf


# ------------------------------------------------ modelos escaneados (Poly Haven)


def import_asset(name, keep=None):
    """Importa un modelo de blender/assets/models/<name> bajo un empty raíz."""
    import glob

    path = (glob.glob(os.path.join(ASSETS, "models", name, "*.gltf")) + glob.glob(os.path.join(ASSETS, "models", name, "*.blend")))[0]
    before = set(bpy.data.objects)
    if path.endswith(".gltf"):
        bpy.ops.import_scene.gltf(filepath=path)
    else:
        with bpy.data.libraries.load(path) as (src, dst):
            dst.objects = src.objects
        for o in dst.objects:
            if o is not None:
                bpy.context.scene.collection.objects.link(o)
    new = [o for o in bpy.data.objects if o not in before]
    bpy.context.view_layer.update()
    if keep:
        for o in [o for o in new if o.type == "MESH" and not keep(o)]:
            new.remove(o)
            bpy.data.objects.remove(o, do_unlink=True)
    root = bpy.data.objects.new(name + "_root", None)
    bpy.context.scene.collection.objects.link(root)
    for o in new:
        if o.parent is None:
            o.parent = root
    return root, [o for o in new if o.type == "MESH"]


def world_bbox(meshes):
    bpy.context.view_layer.update()
    lo = Vector((1e9,) * 3)
    hi = Vector((-1e9,) * 3)
    for o in meshes:
        for c in o.bound_box:
            w = o.matrix_world @ Vector(c)
            lo = Vector(map(min, lo, w))
            hi = Vector(map(max, hi, w))
    return lo, hi


def fit(root, meshes, center_xy, base_z, rot_z=0.0, scale=1.0):
    """Gira/escala el modelo y lo apoya con su base en base_z, centrado en center_xy."""
    root.rotation_euler = (0, 0, math.radians(rot_z))
    root.scale = (scale,) * 3
    lo, hi = world_bbox(meshes)
    c = (lo + hi) / 2
    root.location += Vector((center_xy[0] - c.x, center_xy[1] - c.y, base_z - lo.z))


def x_center_between(a, b):
    def keep(o):
        xs = [(o.matrix_world @ Vector(c)).x for c in o.bound_box]
        return a <= (min(xs) + max(xs)) / 2 <= b

    return keep


def scanned_props():
    # Fuera los objetos procedurales que sustituyen los modelos escaneados
    for o in list(bpy.data.objects):
        n = o.name
        if n.startswith(("leaf_", "book_")) or n in {"pot", "soil", "stem", "mini_pot", "mini_plant"}:
            bpy.data.objects.remove(o, do_unlink=True)

    shelf_x = -2.35
    shelf_top = {0: 1.15 + 0.015, 1: 1.6 + 0.015, 2: 2.05 + 0.015}
    # Los modelos miran a -Y: +90° en Z deja lomos/frentes mirando a +X (hacia la habitación)
    root, m = import_asset("potted_plant_01")
    fit(root, m, (1.95, 1.62), 0.0, rot_z=35)
    root, m = import_asset("modern_arm_chair_01")
    fit(root, m, (-1.92, -1.2), 0.0, rot_z=68)
    root, m = import_asset("ceramic_vase_01")
    fit(root, m, (-2.25, -0.35), 0.0, scale=1.1)
    root, m = import_asset("potted_plant_04")
    fit(root, m, (shelf_x, 1.05), shelf_top[0], rot_z=20)
    root, m = import_asset("standing_picture_frame_01")
    fit(root, m, (0.6, 1.86), 0.75, rot_z=-12)
    root, m = import_asset("book_encyclopedia_set_01")
    fit(root, m, (shelf_x + 0.03, 0.28), shelf_top[1], rot_z=90)
    root, m = import_asset("decorative_book_set_01", keep=x_center_between(0.0, 0.85))
    fit(root, m, (shelf_x + 0.02, 0.36), shelf_top[0], rot_z=90)
    root, m = import_asset("decorative_book_set_01", keep=x_center_between(1.1, 1.75))
    fit(root, m, (shelf_x + 0.02, 0.3), shelf_top[2], rot_z=90)


# ----------------------------------------------------------- geometría extra


def soften_cushions():
    for name in ("chair_seat", "chair_back"):
        ob = bpy.data.objects[name]
        sub = ob.modifiers.new("Subsurf", "SUBSURF")
        sub.levels = sub.render_levels = 2
        for p in ob.data.polygons:
            p.use_smooth = True


def window():
    """Ventana en la pared izquierda por donde entra la luz de luna."""
    wall = bpy.data.objects["wall_left"]
    cy, cz, w, h = -1.05, 1.5, 1.0, 1.25
    cutter = studio.box("win_cut", (0.6, w, h), (-2.6, cy, cz), bpy.data.materials["trim"])
    cutter.hide_render = True
    cutter.hide_viewport = True
    boolean = wall.modifiers.new("Window", "BOOLEAN")
    boolean.operation = "DIFFERENCE"
    boolean.object = cutter
    frame = bpy.data.materials.new("win_frame")
    plastic(frame, "#d9d6cf", 0.4, 0)
    t = 0.05
    x = -2.6
    studio.box("win_top", (0.22, w + 2 * t, t), (x, cy, cz + h / 2 + t / 2), frame, bev=0.005)
    studio.box("win_bot", (0.3, w + 2 * t, t), (x + 0.03, cy, cz - h / 2 - t / 2), frame, bev=0.005)
    studio.box("win_l", (0.22, t, h), (x, cy - w / 2 - t / 2, cz), frame, bev=0.005)
    studio.box("win_r", (0.22, t, h), (x, cy + w / 2 + t / 2, cz), frame, bev=0.005)
    studio.box("win_mid_v", (0.04, 0.035, h), (x, cy, cz), frame)
    studio.box("win_mid_h", (0.04, w, 0.035), (x, cy, cz), frame)
    glass = bpy.data.materials.new("glass")
    Mat(glass).set(color="#ffffff", rough=0.02, trans=1.0)
    g = studio.box("win_glass", (0.01, w, h), (x, cy, cz), glass)
    g.visible_shadow = False  # que la luz de luna atraviese el cristal sin cáusticas


def lights_real():
    sc = bpy.context.scene
    for o in list(sc.objects):
        if o.type == "LIGHT":
            bpy.data.objects.remove(o, do_unlink=True)
    bg = sc.world.node_tree.nodes["Background"]
    bg.inputs["Color"].default_value = lin("#0a0f22")
    bg.inputs["Strength"].default_value = 0.25

    def area(name, loc, target, power, size, color):
        ld = bpy.data.lights.new(name, "AREA")
        ld.energy, ld.size, ld.color = power, size, lin(color)[:3]
        ob = bpy.data.objects.new(name, ld)
        ob.location = loc
        ob.rotation_euler = (Vector(target) - Vector(loc)).to_track_quat("-Z", "Y").to_euler()
        sc.collection.objects.link(ob)

    area("moon", (-5.2, -1.6, 3.4), (0.0, -0.4, 0.0), 900, 1.2, "#a9bcff")
    area("fill", (3.5, -4.0, 4.5), (0, 0.8, 0.8), 140, 4.0, "#b9c4ff")
    area("rim", (2.4, 1.2, 3.2), (-0.5, 1.6, 0.8), 70, 1.2, "#a78bfa")
    spot = bpy.data.lights.new("lamp_spot", "SPOT")
    spot.energy = 90
    spot.spot_size = math.radians(95)
    spot.spot_blend = 0.85
    spot.shadow_soft_size = 0.05
    spot.color = lin("#ffcf8f")[:3]
    so = bpy.data.objects.new("lamp_spot", spot)
    bpy.context.view_layer.update()
    so.location = bpy.data.objects["lamp_bulb"].matrix_world.translation - Vector((0, 0, 0.012))
    sc.collection.objects.link(so)


# ------------------------------------------------------------------- render


def setup_render(samples):
    sc = bpy.context.scene
    studio.setup_cycles(samples)
    c = sc.cycles
    c.use_adaptive_sampling = True
    c.adaptive_threshold = 0.015
    c.use_denoising = True
    try:
        c.denoiser = "OPTIX"
    except Exception:
        pass
    c.max_bounces = 8
    c.caustics_reflective = False
    c.caustics_refractive = False
    c.blur_glossy = 1.0
    c.sample_clamp_indirect = 6.0
    sc.render.resolution_x, sc.render.resolution_y = RES
    sc.render.resolution_percentage = 100
    sc.view_settings.view_transform = "AgX"
    for look in ("AgX - Medium High Contrast", "AgX - Punchy", "None"):
        try:
            sc.view_settings.look = look
            break
        except Exception:
            continue
    sc.view_settings.exposure = 0.35
    sc.render.image_settings.file_format = "PNG"

    # Bloom: el neón, la lámpara y las pantallas "brillan" como en una foto
    tree = bpy.data.node_groups.new("post", "CompositorNodeTree")
    tree.interface.new_socket("Image", in_out="OUTPUT", socket_type="NodeSocketColor")
    rl = tree.nodes.new("CompositorNodeRLayers")
    glare = tree.nodes.new("CompositorNodeGlare")
    for key, value in (("Type", "Bloom"), ("Quality", "High")):
        try:
            glare.inputs[key].default_value = value
        except Exception as e:
            print("[glare]", key, e)
    for key, value in (("Threshold", 1.2), ("Strength", 0.55), ("Size", 0.7)):
        try:
            glare.inputs[key].default_value = value
        except Exception as e:
            print("[glare]", key, e)
    out = tree.nodes.new("NodeGroupOutput")
    tree.links.new(rl.outputs["Image"], glare.inputs["Image"])
    tree.links.new(glare.outputs["Image"], out.inputs[0])
    sc.compositing_node_group = tree


def make_camera():
    sc = bpy.context.scene
    cd = bpy.data.cameras.new("cam")
    cd.sensor_fit = "HORIZONTAL"
    cd.lens_unit = "FOV"
    cd.angle = math.radians(52)
    cd.dof.use_dof = True
    cam = bpy.data.objects.new("cam", cd)
    sc.collection.objects.link(cam)
    sc.camera = cam
    return cam


def ease(x):
    return 4 * x * x * x if x < 0.5 else 1 - (-2 * x + 2) ** 3 / 2


KEYS = list(studio.CAMERAS.values())


def place(cam, t):
    """Misma interpolación que sampleCamera() en la web."""
    last = len(KEYS) - 1
    t = min(max(t, 0.0), last)
    i = min(int(math.floor(t)), last - 1)
    f = ease(t - i)
    (p0, t0), (p1, t1) = KEYS[i], KEYS[i + 1]
    pos = Vector(p0).lerp(Vector(p1), f)
    target = Vector(t0).lerp(Vector(t1), f)
    cam.location = pos
    cam.rotation_euler = (target - pos).to_track_quat("-Z", "Y").to_euler()
    cam.data.shift_x = SHIFTS[i] + (SHIFTS[i + 1] - SHIFTS[i]) * f
    cam.data.dof.focus_distance = (target - pos).length
    cam.data.dof.aperture_fstop = FSTOPS[i] + (FSTOPS[i + 1] - FSTOPS[i]) * f


def render_to(path):
    if os.path.exists(path):
        return
    bpy.context.scene.render.filepath = path
    bpy.ops.render.render(write_still=True)


# --------------------------------------------------------------------- main

studio.reset()
studio.build()
studio.lights()
realify()
scanned_materials()
scanned_props()
main_tex, proj_dir = per_object_screens()
soften_cushions()
window()
lights_real()
setup_render(SAMPLES)
cam = make_camera()

if MODE == "stills":
    names = list(studio.CAMERAS.keys())
    for i, name in enumerate(names):
        place(cam, float(i))
        render_to(os.path.join(OUT, f"still_{name}.png"))
else:
    total = PER_SEGMENT * (len(KEYS) - 1) + 1
    for k in range(max(FROM, 0), min(TO, total - 1) + 1):
        place(cam, k / PER_SEGMENT)
        render_to(os.path.join(OUT, f"f_{k:03d}.png"))
    if TO >= total - 1:
        place(cam, 3.0)  # cámara de proyectos: una variante por captura en el monitor
        for p in range(1, len(PROJECT_IMAGES)):
            main_tex.image = bpy.data.images.load(os.path.join(proj_dir, PROJECT_IMAGES[p]), check_existing=True)
            render_to(os.path.join(OUT, f"p_{p}.png"))
        with open(os.path.join(OUT, "manifest.json"), "w") as fh:
            json.dump({"frames": total, "perSegment": PER_SEGMENT, "variants": len(PROJECT_IMAGES) - 1,
                       "projectKey": 3, "width": RES[0], "height": RES[1]}, fh)
print("[ok]", MODE, OUT)
