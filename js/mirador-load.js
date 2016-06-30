/**
 * @file
 * Helper js to load the mirador viewer.
 */

(function ($, Drupal) {

  Drupal.behaviors.Mirador = {
    attach: function (context, settings) {
      var manifestUri = settings.init.entity.manifest_uri;
      var annotationUri = settings.init.entity.annotation_uri;
      var viewerID = settings.init.entity.viewer_id;
      var imageRefEntityID = settings.init.entity.entity_id;
      var userID = settings.init.entity.user_id;
      if ($('#' + viewerID + ' .mirador-viewer').length == 0) {
        Mirador({
          "id": viewerID,
          "layout": "1x1",
          'openManifestsPage' : false,
          'showAddFromURLBox' : false,
          "saveSession": false,
          "data": [
            { "manifestUri": manifestUri, "location": "National Virtual Library Of India"},
          ],
          "windowObjects":[{
            "loadedManifest" : manifestUri,
            "viewType" : "ImageView"} ],
            /** Annotations **/
            annotationEndpoint: {
              name: 'Mirador Endpoint',
              module: 'MiradorEndpoint',
              options: {
                url: annotationUri,
                  storeId: 'demo.nvli',
                  APIKey: 'user_auth',
                  imageRefEntityID: imageRefEntityID,
                  annotationOwner: userID
              }
            }
        });
      }
    }
  };
})(jQuery, Drupal);
