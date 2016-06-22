(function($) {
  $(function() {
    Drupal.behaviors.mirador = {
      attach:function(context, settings) {
        var manifestUri = drupalSettings.init.entity.manifest_uri;
        var viewerID = drupalSettings.init.entity.viewer_id;
        Mirador({
          "id": viewerID,
          "layout": "1x1",
          'showAddFromURLBox' : false,
          "saveSession": false,
          "data": [
            { "manifestUri": manifestUri, "location": "National Virtual Library Of India"},
          ],
          "windowObjects":[{
            "loadedManifest" : manifestUri,
            "viewType" : "ImageView"} ],
        });
      }
    }
  });
})(jQuery);

