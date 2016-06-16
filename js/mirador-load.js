(function($) {
  $(function() {
    Mirador({
      "id": "image-viewer",
      "layout": "1x1",
      "mainMenuSettings" :
        {
          "show": true,
          "buttons" : {"bookmark" : true, "layout" : true},
          "userLogo": {"label": "IIIF", "attributes": {"href": "http://iiif.io"}}
        },
      'showAddFromURLBox' : true,
      "saveSession": false,
      "data": [
          { "manifestUri": "http://dev.llgc.org.uk/iiif/examples/photos/manifest.json", "location": "National Library of Wales"},
          { "manifestUri": "http://www.e-codices.unifr.ch/metadata/iiif/saa-0428/manifest.json", "location": "Virtual Manuscript Library of Switzerland"}
      ],
      "windowObjects":[{
        "loadedManifest" : "http://www.e-codices.unifr.ch/metadata/iiif/saa-0428/manifest.json",
        "viewType" : "ImageView"}
      ],
    });
  });
})(jQuery);

