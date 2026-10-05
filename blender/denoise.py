"""
Quita el ruido del horneado con OpenImageDenoise (nodo Denoise del compositor de Blender 5).

Uso: blender -b --python blender/denoise.py -- <entrada.exr> <salida.png>
"""

import sys

import bpy

src, dst = sys.argv[sys.argv.index("--") + 1 :][:2]

bpy.ops.wm.read_factory_settings(use_empty=True)
sc = bpy.context.scene
img = bpy.data.images.load(src)
w, h = img.size

tree = bpy.data.node_groups.new("denoise", "CompositorNodeTree")
tree.interface.new_socket("Image", in_out="OUTPUT", socket_type="NodeSocketColor")
n_img = tree.nodes.new("CompositorNodeImage")
n_img.image = img
n_dn = tree.nodes.new("CompositorNodeDenoise")
for name, value in (("prefilter", "ACCURATE"), ("quality", "HIGH")):
    if hasattr(n_dn, name):
        setattr(n_dn, name, value)
n_out = tree.nodes.new("NodeGroupOutput")
tree.links.new(n_img.outputs["Image"], n_dn.inputs["Image"])
tree.links.new(n_dn.outputs["Image"], n_out.inputs[0])
sc.compositing_node_group = tree

# El render de la escena vacía es irrelevante: solo interesa la salida del compositor
cam = bpy.data.objects.new("cam", bpy.data.cameras.new("cam"))
sc.collection.objects.link(cam)
sc.camera = cam
sc.render.engine = "BLENDER_WORKBENCH"
sc.render.resolution_x, sc.render.resolution_y = w, h
sc.render.resolution_percentage = 100
sc.view_settings.view_transform = "Standard"
sc.render.image_settings.file_format = "PNG"
sc.render.image_settings.color_depth = "8"
sc.render.filepath = dst
bpy.ops.render.render(write_still=True)
print("[denoise ok]", dst, w, h)
