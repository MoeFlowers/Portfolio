"""
Construye el "estudio" 3D del portafolio, hornea la iluminación y exporta a la web.

Uso (sin abrir Blender):
  blender -b --python blender/build_studio.py -- <modo> <salida> [samples] [resolucion]

  modo:  preview  -> render rápido con iluminación real desde las cámaras de la web
         bake     -> hornea la luz en una textura, exporta room.glb + room-baked.png
                     y renderiza previews con el resultado horneado

Coordenadas Blender: Z arriba, el espectador mira desde -Y hacia +Y.
"""

import math
import os
import sys

import bmesh
import bpy
from mathutils import Euler, Matrix, Vector

argv = sys.argv[sys.argv.index("--") + 1 :] if "--" in sys.argv else []
MODE = argv[0] if len(argv) > 0 else "preview"
OUT = os.path.abspath(argv[1] if len(argv) > 1 else ".")
SAMPLES = int(argv[2]) if len(argv) > 2 else 64
RES = int(argv[3]) if len(argv) > 3 else 4096
os.makedirs(OUT, exist_ok=True)

INDIGO = "#6366F1"
CYAN = "#22D3EE"
RED = "#FF1E1E"

# Cámaras de la web (posición, objetivo). Se replican en src/components/three/cameraPath.ts
CAMERAS = {
    "hero": ((4.6, -5.4, 3.9), (0.0, 0.7, 1.0)),
    "about": ((2.4, -1.5, 1.85), (-0.4, 1.5, 1.05)),
    "skills": ((0.55, 0.62, 1.32), (0.02, 1.35, 0.76)),
    "projects": ((-0.42, 0.25, 1.28), (0.0, 1.7, 1.2)),
    "experience": ((0.4, -0.4, 1.75), (-1.7, 1.6, 1.6)),
    "contact": ((0.8, -5.6, 6.6), (0.0, 0.6, 0.5)),
}


# ---------------------------------------------------------------- utilidades


def hex_rgb(h):
    h = h.lstrip("#")
    srgb = [int(h[i : i + 2], 16) / 255 for i in (0, 2, 4)]
    lin = [c / 12.92 if c <= 0.04045 else ((c + 0.055) / 1.055) ** 2.4 for c in srgb]
    return (*lin, 1.0)


_mats = {}


def mat(name, color, rough=0.6, metal=0.0, emit=None, strength=0.0):
    if name in _mats:
        return _mats[name]
    m = bpy.data.materials.new(name)
    try:
        m.use_nodes = True
    except Exception:
        pass
    bsdf = m.node_tree.nodes.get("Principled BSDF")
    bsdf.inputs["Base Color"].default_value = hex_rgb(color)
    bsdf.inputs["Roughness"].default_value = rough
    bsdf.inputs["Metallic"].default_value = metal
    if emit:
        bsdf.inputs["Emission Color"].default_value = hex_rgb(emit)
        bsdf.inputs["Emission Strength"].default_value = strength
    _mats[name] = m
    return m


STATIC = None
SCREENS = None


def link(obj, coll=None):
    (coll or STATIC).objects.link(obj)
    return obj


def new_obj(name, bm, material, loc=(0, 0, 0), rot=(0, 0, 0), parent=None, smooth=False, coll=None):
    me = bpy.data.meshes.new(name)
    bm.to_mesh(me)
    bm.free()
    if smooth:
        for p in me.polygons:
            p.use_smooth = True
        try:
            me.set_sharp_from_angle(angle=math.radians(40))
        except Exception:
            pass
    me.materials.append(material)
    ob = bpy.data.objects.new(name, me)
    ob.location = loc
    ob.rotation_euler = Euler([math.radians(a) for a in rot])
    if parent:
        ob.parent = parent
    return link(ob, coll)


def bevel(ob, width, segments=3):
    mod = ob.modifiers.new("Bevel", "BEVEL")
    mod.width = width
    mod.segments = segments
    mod.limit_method = "ANGLE"
    return ob


def box(name, size, loc, material, rot=(0, 0, 0), bev=0.0, parent=None):
    bm = bmesh.new()
    bmesh.ops.create_cube(bm, size=1.0)
    bmesh.ops.scale(bm, vec=Vector(size), verts=bm.verts)
    ob = new_obj(name, bm, material, loc, rot, parent)
    if bev:
        bevel(ob, bev)
    return ob


def cyl(name, r1, depth, loc, material, r2=None, rot=(0, 0, 0), seg=40, parent=None, bev=0.0):
    bm = bmesh.new()
    bmesh.ops.create_cone(
        bm, cap_ends=True, cap_tris=False, segments=seg, radius1=r1, radius2=r1 if r2 is None else r2, depth=depth
    )
    ob = new_obj(name, bm, material, loc, rot, parent, smooth=True)
    if bev:
        bevel(ob, bev, 2)
    return ob


def sphere(name, radius, loc, material, scale=(1, 1, 1), rot=(0, 0, 0), parent=None, seg=32):
    bm = bmesh.new()
    bmesh.ops.create_uvsphere(bm, u_segments=seg, v_segments=seg // 2, radius=radius)
    bmesh.ops.scale(bm, vec=Vector(scale), verts=bm.verts)
    return new_obj(name, bm, material, loc, rot, parent, smooth=True)


def torus(name, major, minor, loc, material, rot=(0, 0, 0), parent=None):
    bm = bmesh.new()
    seg_u, seg_v = 32, 12
    verts = []
    for i in range(seg_u):
        a = 2 * math.pi * i / seg_u
        row = []
        for j in range(seg_v):
            b = 2 * math.pi * j / seg_v
            r = major + minor * math.cos(b)
            row.append(bm.verts.new((r * math.cos(a), r * math.sin(a), minor * math.sin(b))))
        verts.append(row)
    for i in range(seg_u):
        for j in range(seg_v):
            bm.faces.new(
                (
                    verts[i][j],
                    verts[(i + 1) % seg_u][j],
                    verts[(i + 1) % seg_u][(j + 1) % seg_v],
                    verts[i][(j + 1) % seg_v],
                )
            )
    return new_obj(name, bm, material, loc, rot, parent, smooth=True)


def screen_plane(name, w, h, loc, rot, material, parent=None):
    """Plano mirando a -Y (local) con UV 0..1 para pintar texturas desde la web."""
    bm = bmesh.new()
    uv = bm.loops.layers.uv.new("UVMap")
    vs = [bm.verts.new(v) for v in ((-w / 2, 0, -h / 2), (w / 2, 0, -h / 2), (w / 2, 0, h / 2), (-w / 2, 0, h / 2))]
    f = bm.faces.new(vs)
    for loop, co in zip(f.loops, ((0, 0), (1, 0), (1, 1), (0, 1))):
        loop[uv].uv = co
    return new_obj(name, bm, material, loc, rot, parent, coll=SCREENS)


def empty(name, loc=(0, 0, 0), rot=(0, 0, 0)):
    e = bpy.data.objects.new(name, None)
    e.location = loc
    e.rotation_euler = Euler([math.radians(a) for a in rot])
    STATIC.objects.link(e)
    return e


# -------------------------------------------------------------------- escena


def reset():
    bpy.ops.wm.read_factory_settings(use_empty=True)
    global STATIC, SCREENS
    STATIC = bpy.data.collections.new("static")
    SCREENS = bpy.data.collections.new("screens")
    bpy.context.scene.collection.children.link(STATIC)
    bpy.context.scene.collection.children.link(SCREENS)


def build():
    M = {
        "wall": mat("wall", "#2c3050", 0.9),
        "floor": mat("floor", "#5b3e2b", 0.55),
        "trim": mat("trim", "#2a2d40", 0.7),
        "desk": mat("desk", "#6b4529", 0.45),
        "metal": mat("metal", "#3a3d4a", 0.35),
        "black": mat("black", "#101116", 0.4),
        "bezel": mat("bezel", "#16171d", 0.3),
        "key": mat("key", "#e9e6df", 0.5),
        "key_mod": mat("key_mod", "#9aa0b8", 0.5),
        "key_accent": mat("key_accent", INDIGO, 0.45),
        "mat_pad": mat("mat_pad", "#252840", 0.9),
        "chair": mat("chair", "#262a3d", 0.75),
        "rug": mat("rug", "#272c66", 0.95),
        "ceramic": mat("ceramic", "#f1eee8", 0.25),
        "coffee": mat("coffee", "#2a160c", 0.2),
        "pot": mat("pot", "#c46a47", 0.8),
        "soil": mat("soil", "#2b1d14", 1.0),
        "leaf": mat("leaf", "#3f8a55", 0.55),
        "leaf2": mat("leaf2", "#2f7246", 0.55),
        "paper": mat("paper", "#f4efe2", 0.8),
        "frame": mat("frame", "#1c1d24", 0.4),
        "gold": mat("gold", "#d6a645", 0.3),
        "cork": mat("cork", "#b48b5b", 0.95),
        "neon": mat("neon", RED, 0.4, emit=RED, strength=9.0),
        "seal": mat("seal", "#c8102e", 0.45),
        "ink": mat("ink", "#7d786c", 0.8),
        "led_indigo": mat("led_indigo", INDIGO, 0.4, emit=INDIGO, strength=25.0),
        "led_cyan": mat("led_cyan", CYAN, 0.4, emit=CYAN, strength=18.0),
        "bulb": mat("bulb", "#fff1d6", 0.4, emit="#ffe2b0", strength=30.0),
        "docker": mat("docker", "#2496ed", 0.5),
    }
    books = ["#e0533d", "#f2b134", "#3f6fd8", "#2bb39b", "#8b5cf6", "#ef7d57", "#e9e6df", "#334155"]
    notes = ["#fde047", "#f9a8d4", "#67e8f9", "#a5b4fc", "#86efac", "#fdba74"]

    # --- habitación (diorama recortado)
    box("floor", (5.0, 4.2, 0.24), (0, 0, -0.12), M["floor"], bev=0.01)
    box("floor_trim", (5.06, 4.26, 0.08), (0, 0, -0.28), M["trim"], bev=0.01)
    box("wall_back", (5.2, 0.2, 3.1), (-0.1, 2.2, 1.31), M["wall"], bev=0.01)
    box("wall_left", (0.2, 4.4, 3.1), (-2.6, 0.0, 1.31), M["wall"], bev=0.01)
    box("baseboard_b", (5.0, 0.03, 0.1), (0, 2.085, 0.05), M["trim"])
    box("baseboard_l", (0.03, 4.2, 0.1), (-2.485, 0, 0.05), M["trim"])

    # --- escritorio
    top_z = 0.75
    box("desk_top", (2.3, 0.82, 0.05), (0.1, 1.6, top_z - 0.025), M["desk"], bev=0.008)
    for sx in (-1.0, 1.2):
        for sy in (1.27, 1.93):
            box(f"leg_{sx}_{sy}", (0.05, 0.05, top_z - 0.05), (sx, sy, (top_z - 0.05) / 2), M["metal"], bev=0.004)
        box(f"foot_{sx}", (0.06, 0.74, 0.04), (sx, 1.6, 0.02), M["metal"], bev=0.004)
        box(f"rail_{sx}", (0.05, 0.7, 0.04), (sx, 1.6, top_z - 0.07), M["metal"], bev=0.004)
    box("back_rail", (2.2, 0.04, 0.06), (0.1, 1.93, top_z - 0.08), M["metal"], bev=0.004)
    box("led_desk", (2.1, 0.015, 0.012), (0.1, 1.98, top_z - 0.04), M["led_indigo"])

    # --- monitor principal (pantalla 0.96 x 0.64, proporción 3:2 como las imágenes de proyectos)
    mon_z = top_z + 0.5
    box("mon_bezel", (1.0, 0.035, 0.68), (0.0, 1.74, mon_z), M["bezel"], bev=0.006)
    box("mon_neck", (0.07, 0.04, 0.42), (0.0, 1.79, top_z + 0.21), M["metal"], bev=0.005)
    box("mon_base", (0.32, 0.22, 0.015), (0.0, 1.74, top_z + 0.0075), M["metal"], bev=0.006)
    box("mon_led", (0.85, 0.01, 0.01), (0.0, 1.765, mon_z + 0.25), M["led_cyan"])
    screen_mat = mat("screen_glow", "#000000", 0.5, emit="#a5b4fc", strength=0.9)
    screen_plane("screen_main", 0.96, 0.64, (0.0, 1.7185, mon_z), (0, 0, 0), screen_mat)

    # --- monitor vertical
    side = empty("side_mon", (0.98, 1.6, 0), (0, 0, -32))
    side_z = top_z + 0.47
    box("side_bezel", (0.4, 0.03, 0.7), (0, 0, side_z), M["bezel"], bev=0.006, parent=side)
    box("side_neck", (0.06, 0.035, 0.3), (0, 0.05, top_z + 0.15), M["metal"], parent=side)
    box("side_base", (0.24, 0.18, 0.015), (0, 0.02, top_z + 0.0075), M["metal"], bev=0.005, parent=side)
    screen_plane("screen_side", 0.37, 0.66, (0, -0.0185, side_z), (0, 0, 0), screen_mat, parent=side)

    # --- teclado
    kb_y = 1.36
    box("desk_mat", (1.15, 0.4, 0.004), (0.12, kb_y, top_z + 0.002), M["mat_pad"], bev=0.002)
    box("kb_base", (0.47, 0.165, 0.022), (0.0, kb_y, top_z + 0.015), M["black"], bev=0.004)
    pitch, cap = 0.0292, 0.0245
    rows = [15, 15, 14, 13, 12]
    for r, n in enumerate(rows):
        y = kb_y + 0.058 - r * pitch
        x0 = -pitch * (n - 1) / 2
        for c in range(n):
            accent = (r == 0 and c == 0) or (r == 2 and c == n - 1) or (r == 4 and c >= n - 3)
            mod = c == 0 or c == n - 1
            m = M["key_accent"] if accent else (M["key_mod"] if mod else M["key"])
            box(f"key_{r}_{c}", (cap, cap, 0.013), (x0 + c * pitch, y, top_z + 0.032), m, bev=0.003)
    box("key_space", (cap + pitch * 5, cap, 0.013), (0.0, kb_y - 0.0585 - 0.002, top_z + 0.032), M["key"], bev=0.003)

    # --- ratón
    sphere("mouse", 1.0, (0.43, kb_y - 0.01, top_z + 0.012), M["black"], scale=(0.032, 0.055, 0.02))

    # --- portátil
    lap = empty("laptop", (-0.72, 1.42, top_z), (0, 0, 24))
    box("lap_base", (0.36, 0.25, 0.016), (0, 0, 0.008), M["metal"], bev=0.004, parent=lap)
    hinge = empty("lap_hinge", (0, 0.125, 0.016), (-16, 0, 0))
    hinge.parent = lap
    box("lap_lid", (0.36, 0.012, 0.24), (0, 0.006, 0.12), M["metal"], bev=0.004, parent=hinge)
    term_mat = mat("term_glow", "#000000", 0.5, emit="#7dd3fc", strength=0.8)
    screen_plane("screen_laptop", 0.33, 0.21, (0, -0.003, 0.125), (0, 0, 0), term_mat, parent=hinge)

    # --- lámpara de escritorio
    lamp = empty("lamp", (-1.02, 1.82, top_z))
    cyl("lamp_base", 0.08, 0.025, (0, 0, 0.0125), M["black"], parent=lamp, bev=0.004)
    cyl("lamp_pole", 0.012, 0.5, (0, 0, 0.26), M["black"], parent=lamp)
    sphere("lamp_joint", 0.02, (0, 0, 0.51), M["black"], parent=lamp)
    arm = empty("lamp_arm", (0, 0, 0.51), (0, 0, -40))
    arm.parent = lamp
    cyl("lamp_arm_m", 0.011, 0.34, (0, -0.17, 0), M["black"], rot=(90, 0, 0), parent=arm)
    sphere("lamp_joint2", 0.018, (0, -0.34, 0), M["black"], parent=arm)
    cyl("lamp_head", 0.075, 0.11, (0, -0.34, -0.055), M["black"], r2=0.03, parent=arm)
    cyl("lamp_bulb", 0.062, 0.004, (0, -0.34, -0.112), M["bulb"], parent=arm)

    # --- taza
    cyl("mug", 0.042, 0.1, (0.66, 1.3, top_z + 0.05), M["ceramic"], bev=0.003)
    cyl("coffee", 0.037, 0.004, (0.66, 1.3, top_z + 0.092), M["coffee"])
    torus("mug_handle", 0.028, 0.007, (0.708, 1.3, top_z + 0.05), M["ceramic"], rot=(90, 0, 0))

    # --- torre / servidor bajo el escritorio
    box("tower", (0.22, 0.46, 0.5), (0.85, 1.55, 0.25), M["black"], bev=0.01)
    box("tower_led", (0.006, 0.36, 0.008), (0.738, 1.55, 0.44), M["led_cyan"])
    for i in range(3):
        cyl(f"tower_fan_{i}", 0.05, 0.006, (0.739, 1.42 + i * 0.13, 0.25), M["led_indigo"], rot=(0, 90, 0))

    # --- silla
    chair = empty("chair", (0.05, 0.62, 0), (0, 0, 14))
    for i in range(5):
        a = math.radians(i * 72)
        arm_ = empty(f"chair_leg_{i}", (0, 0, 0.06), (0, 0, i * 72))
        arm_.parent = chair
        box(f"chair_leg_m{i}", (0.3, 0.045, 0.035), (0.15, 0, 0), M["black"], bev=0.008, parent=arm_)
        sphere(f"wheel_{i}", 0.028, (0.31 * math.cos(a), 0.31 * math.sin(a), 0.028), M["black"], parent=chair)
    cyl("chair_hub", 0.05, 0.06, (0, 0, 0.07), M["black"], parent=chair)
    cyl("chair_gas", 0.022, 0.38, (0, 0, 0.26), M["metal"], parent=chair)
    box("chair_seat", (0.52, 0.5, 0.08), (0, 0, 0.48), M["chair"], bev=0.03, parent=chair)
    box("chair_back", (0.48, 0.07, 0.62), (0, -0.27, 0.86), M["chair"], rot=(-8, 0, 0), bev=0.03, parent=chair)
    for sx in (-0.27, 0.27):
        box(f"chair_armpost{sx}", (0.03, 0.04, 0.2), (sx, -0.02, 0.6), M["black"], parent=chair)
        box(f"chair_arm{sx}", (0.06, 0.28, 0.025), (sx, 0.0, 0.71), M["black"], bev=0.01, parent=chair)

    box("rug", (2.1, 1.5, 0.012), (0.1, 0.55, 0.006), M["rug"], bev=0.005)

    # --- planta grande (esquina derecha)
    pl = (1.95, 1.55)
    cyl("pot", 0.22, 0.46, (pl[0], pl[1], 0.23), M["pot"], r2=0.17, bev=0.008)
    cyl("soil", 0.205, 0.01, (pl[0], pl[1], 0.445), M["soil"])
    cyl("stem", 0.012, 0.7, (pl[0], pl[1], 0.75), M["leaf2"])
    for i in range(16):
        a = i * 137.5
        h = 0.62 + 0.045 * i
        tilt = 55 - i * 2.2
        ld = empty(f"leaf_dir_{i}", (pl[0], pl[1], h), (0, 0, a))
        sphere(
            f"leaf_{i}",
            1.0,
            (0.0, -0.2, 0.06),
            M["leaf" if i % 2 else "leaf2"],
            scale=(0.09, 0.22, 0.012),
            rot=(-tilt, 0, 0),
            parent=ld,
            seg=20,
        )

    # --- estanterías (pared izquierda)
    for i, z in enumerate((1.15, 1.6, 2.05)):
        box(f"shelf_{i}", (0.3, 1.3, 0.03), (-2.35, 0.55, z), M["desk"], bev=0.004)
        y = 0.0
        for b in range(7 - i * 2):
            h = 0.2 + ((b * 37 + i * 13) % 9) * 0.012
            w = 0.035 + ((b * 17) % 4) * 0.008
            box(
                f"book_{i}_{b}",
                (0.2, w, h),
                (-2.36, y + w / 2, z + 0.015 + h / 2),
                mat(f"book{(b + i) % len(books)}", books[(b + i) % len(books)], 0.7),
                bev=0.003,
            )
            y += w + 0.004
    box("docker_box", (0.16, 0.16, 0.16), (-2.34, 0.95, 1.6 + 0.095), M["docker"], bev=0.01)
    cyl("trophy_base", 0.05, 0.03, (-2.34, 0.85, 2.065 + 0.015), M["black"])
    cyl("trophy_cup", 0.045, 0.1, (-2.34, 0.85, 2.065 + 0.09), M["gold"], r2=0.02)
    cyl("mini_pot", 0.05, 0.08, (-2.34, 1.05, 1.15 + 0.055), M["pot"], r2=0.04)
    sphere("mini_plant", 0.07, (-2.34, 1.05, 1.15 + 0.13), M["leaf"])

    # --- diploma y tablero en la pared del fondo
    box("diploma_frame", (0.52, 0.03, 0.4), (-1.55, 2.085, 1.75), M["frame"], bev=0.004)
    box("diploma_paper", (0.44, 0.01, 0.32), (-1.55, 2.068, 1.75), M["paper"])
    cyl("diploma_seal", 0.036, 0.004, (-1.42, 2.0605, 1.655), M["seal"], rot=(90, 0, 0))
    for i, (w, z) in enumerate(((0.26, 1.86), (0.18, 1.815), (0.3, 1.77), (0.3, 1.745), (0.22, 1.72))):
        box(f"diploma_line_{i}", (w, 0.0015, 0.008 if i == 0 else 0.005), (-1.55, 2.0622, z), M["ink"])
    box("diploma_sign", (0.12, 0.0015, 0.003), (-1.64, 2.0622, 1.66), M["ink"])
    box("board", (0.95, 0.03, 0.62), (1.55, 2.085, 1.75), M["cork"], bev=0.006)
    box("board_frame", (0.99, 0.025, 0.66), (1.55, 2.095, 1.75), M["frame"], bev=0.004)
    for i, c in enumerate(notes):
        x = 1.55 - 0.3 + (i % 3) * 0.3
        z = 1.75 + 0.14 - (i // 3) * 0.28
        box(f"note_{i}", (0.15, 0.006, 0.15), (x, 2.068, z), mat(f"note{i}", c, 0.8), rot=(0, (i * 7) % 11 - 5, 0))

    # --- neón <MF/>
    cu = bpy.data.curves.new("neon_text", "FONT")
    cu.body = "<MoeFlowers.dev/>"
    cu.size = 0.2
    cu.extrude = 0.012
    cu.align_x = "CENTER"
    cu.align_y = "CENTER"
    t = bpy.data.objects.new("neon", cu)
    t.data.materials.append(M["neon"])
    t.location = (0.05, 2.08, 2.42)
    t.rotation_euler = (math.radians(90), 0, 0)
    STATIC.objects.link(t)


def lights():
    sc = bpy.context.scene
    world = bpy.data.worlds.new("world")
    sc.world = world
    try:
        world.use_nodes = True
    except Exception:
        pass
    bg = world.node_tree.nodes.get("Background")
    bg.inputs["Color"].default_value = hex_rgb("#1a2040")
    bg.inputs["Strength"].default_value = 0.35

    def area(name, loc, target, power, size, color="#ffffff"):
        ld = bpy.data.lights.new(name, "AREA")
        ld.energy = power
        ld.size = size
        ld.color = hex_rgb(color)[:3]
        ob = bpy.data.objects.new(name, ld)
        ob.location = loc
        d = Vector(target) - Vector(loc)
        ob.rotation_euler = d.to_track_quat("-Z", "Y").to_euler()
        sc.collection.objects.link(ob)

    area("key", (2.8, -3.2, 4.6), (0, 0.8, 0.6), 420, 3.0, "#dfe6ff")
    area("fill", (-1.8, -3.5, 2.2), (0, 1, 1), 70, 3.0, "#b8c2ff")
    area("rim", (2.4, 1.0, 3.2), (-0.5, 1.6, 0.8), 110, 1.2, "#a78bfa")
    spot = bpy.data.lights.new("lamp_spot", "SPOT")
    spot.energy = 70
    spot.spot_size = math.radians(80)
    spot.spot_blend = 0.6
    spot.color = hex_rgb("#ffd9a0")[:3]
    so = bpy.data.objects.new("lamp_spot", spot)
    bpy.context.view_layer.update()
    lamp_head = bpy.data.objects["lamp_bulb"].matrix_world.translation
    so.location = lamp_head - Vector((0, 0, 0.01))
    so.rotation_euler = (0, 0, 0)
    sc.collection.objects.link(so)


def setup_cycles(samples):
    sc = bpy.context.scene
    sc.render.engine = "CYCLES"
    prefs = bpy.context.preferences.addons["cycles"].preferences
    for kind in ("OPTIX", "CUDA"):
        try:
            prefs.compute_device_type = kind
            prefs.get_devices()
            if any(d.type == kind for d in prefs.devices):
                break
        except Exception:
            continue
    for d in prefs.devices:
        d.use = d.type != "CPU"
    sc.cycles.device = "GPU"
    sc.cycles.samples = samples
    sc.view_settings.view_transform = "Standard"
    sc.view_settings.look = "None"


def add_cameras():
    sc = bpy.context.scene
    cams = []
    for name, (pos, target) in CAMERAS.items():
        cd = bpy.data.cameras.new(name)
        cd.lens_unit = "FOV"
        cd.angle = math.radians(52)  # ~ fov vertical 32° en 16:9, igual que la web
        ob = bpy.data.objects.new(f"cam_{name}", cd)
        ob.location = pos
        ob.rotation_euler = (Vector(target) - Vector(pos)).to_track_quat("-Z", "Y").to_euler()
        sc.collection.objects.link(ob)
        cams.append(ob)
    return cams


def render_cameras(cams, prefix, samples, denoise):
    sc = bpy.context.scene
    sc.render.resolution_x, sc.render.resolution_y = 1280, 720
    sc.cycles.samples = samples
    sc.cycles.use_denoising = denoise
    for cam in cams:
        sc.camera = cam
        sc.render.filepath = os.path.join(OUT, f"{prefix}_{cam.name[4:]}.png")
        bpy.ops.render.render(write_still=True)


# ---------------------------------------------------------------------- bake


def bake(samples, res):
    sc = bpy.context.scene
    dg = bpy.context.evaluated_depsgraph_get()
    statics = [o for o in STATIC.all_objects if o.type in {"MESH", "FONT", "CURVE"}]
    parts = []
    for o in statics:
        me = bpy.data.meshes.new_from_object(o.evaluated_get(dg))
        me.transform(o.matrix_world)
        ob = bpy.data.objects.new(o.name + "_m", me)
        sc.collection.objects.link(ob)
        parts.append(ob)
    # Las pantallas cuelgan de empties que se borran: fijar su transformación mundial antes
    for o in SCREENS.all_objects:
        mw = o.matrix_world.copy()
        o.parent = None
        o.matrix_world = mw
    for o in list(STATIC.all_objects):
        bpy.data.objects.remove(o, do_unlink=True)

    bpy.ops.object.select_all(action="DESELECT")
    for p in parts:
        p.select_set(True)
    bpy.context.view_layer.objects.active = parts[0]
    bpy.ops.object.join()
    room = bpy.context.view_layer.objects.active
    room.name = "room"
    me = room.data
    while me.uv_layers:
        me.uv_layers.remove(me.uv_layers[0])
    me.uv_layers.new(name="bake")

    bpy.ops.object.mode_set(mode="EDIT")
    bpy.ops.mesh.select_all(action="SELECT")
    bpy.ops.uv.smart_project(angle_limit=math.radians(60), island_margin=0.004, area_weight=0.0)
    bpy.ops.object.mode_set(mode="OBJECT")

    img = bpy.data.images.new("room_baked", res, res, alpha=False, float_buffer=True)
    for slot in room.material_slots:
        nt = slot.material.node_tree
        n = nt.nodes.new("ShaderNodeTexImage")
        n.image = img
        nt.nodes.active = n

    setup_cycles(samples)
    b = sc.render.bake
    b.use_pass_direct = True
    b.use_pass_indirect = True
    b.use_pass_diffuse = True
    b.use_pass_glossy = False
    b.use_pass_transmission = True
    b.use_pass_emit = True
    b.margin = 24
    b.margin_type = "EXTEND"
    sc.cycles.bake_type = "COMBINED"
    bpy.ops.object.select_all(action="DESELECT")
    room.select_set(True)
    bpy.context.view_layer.objects.active = room
    print(f"[bake] {len(me.polygons)} caras, {res}px, {samples} samples")
    bpy.ops.object.bake(type="COMBINED")

    # EXR en coma flotante; blender/denoise.py lo limpia y lo convierte a PNG sRGB
    img.filepath_raw = os.path.join(OUT, "room-baked.exr")
    img.file_format = "OPEN_EXR"
    img.save()

    # Material horneado (solo para previews; la web usa MeshBasicMaterial)
    baked = bpy.data.materials.new("baked")
    try:
        baked.use_nodes = True
    except Exception:
        pass
    nt = baked.node_tree
    for n in list(nt.nodes):
        nt.nodes.remove(n)
    tex = nt.nodes.new("ShaderNodeTexImage")
    tex.image = img
    em = nt.nodes.new("ShaderNodeEmission")
    out = nt.nodes.new("ShaderNodeOutputMaterial")
    nt.links.new(tex.outputs["Color"], em.inputs["Color"])
    nt.links.new(em.outputs["Emission"], out.inputs["Surface"])
    me.materials.clear()
    me.materials.append(baked)
    return room


def export(room):
    bpy.ops.object.select_all(action="DESELECT")
    room.select_set(True)
    for o in SCREENS.all_objects:
        o.select_set(True)
    bpy.ops.export_scene.gltf(
        filepath=os.path.join(OUT, "room.glb"),
        export_format="GLB",
        use_selection=True,
        export_materials="NONE",
        export_normals=False,
        export_apply=True,
        export_yup=True,
    )


# ---------------------------------------------------------------------- main

reset()
build()
lights()
cams = add_cameras()
if MODE == "preview":
    setup_cycles(SAMPLES)
    render_cameras(cams, "preview", SAMPLES, True)
else:
    room = bake(SAMPLES, RES)
    export(room)
    # Previews con la textura horneada: sin luces, solo emisión = lo que verá la web
    for o in list(bpy.context.scene.objects):
        if o.type == "LIGHT":
            bpy.data.objects.remove(o, do_unlink=True)
    bpy.context.scene.world.node_tree.nodes["Background"].inputs["Strength"].default_value = 0
    render_cameras(cams, "baked", 1, False)
print("[ok]", MODE, OUT)
