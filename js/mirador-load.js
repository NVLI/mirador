/**
 * @file
 * Helper js to load the mirador viewer.
 */

(function ($, Drupal) {

  Drupal.behaviors.Mirador = {
    attach: function (context, settings) {
      var manifestUri = settings.init.entity.manifest_uri;
      var viewerID = settings.init.entity.viewer_id;
      if ($('#' + viewerID + ' .mirador-viewer').length == 0) {
        Mirador(
                {
                  "id": viewerID,
                  "layout": "1x1",
                  'openManifestsPage': false,
                  'showAddFromURLBox': false,
                  "saveSession": false,
                  "data": [
                    {"manifestUri": manifestUri, "location": "National Virtual Library Of India"},
                  ],
                  "windowObjects": [{
                      "loadedManifest": manifestUri,
                      "viewType": "ImageView"}],
                }
        );
      }
    }
  };
})(jQuery, Drupal);
