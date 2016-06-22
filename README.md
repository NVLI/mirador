Mirador module:
------------------------
Requires - Drupal 8
Compiled Mirador javascript library
IIIF [Image API](http://iiif.io/api/image/2.0/) server


Overview:
--------
Mirador is a multi-repository, configurable, extensible, and easy-to-integrate viewer and annotation creation and comparison environment for IIIF resources, ranging from deep-zooming artwork, to complex manuscript objects. It provides a tiling windowed environment for comparing multiple image-based resources, synchronised structural and visual navigation of content using openSeadragon, Open Annotation compliant annotation creation and viewing on deep-zoomable canvases, metadata display, bookreading, and bookmarking.

* Mirador - https://github.com/IIIF/mirador


Features:
---------

The Mirador module:

* Works as a Image Formatter in entities.

The Mirador plugin:

* pen-source, web based, multi-window image viewing platform
  with the ability to zoom, display, compare and annotate
  images from around the world.

Installation:
------------
1. Install the module as normal, see link for instructions.
   Link: https://www.drupal.org/documentation/install/modules-themes/modules-8
2. Download compiled Mirador javascript library into Drupal's libraries directory, usually in`/libraries`. Verify the file permission is web servable. Make sure the path to the plugin is libraries/mirador/mirador.js.
3. Set the IIIF image server in path /admin/config/media/mirador. In the image server specify the path upto files directory, in simple resolver config.


Configuration:
-------------
Go to "Configuration" -> "Media" -> "Mirador" to find
all the configuration options.

