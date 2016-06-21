(function($) {
  $(function() {
    Drupal.behaviors.mirador = {
      attach:function(context, settings) {
        var entityInfo = drupalSettings.init.entity.entityInfo;
        var entitySettings = drupalSettings.init.entity.settings;
        var entityInfo = jQuery.parseJSON(entityInfo);
        var manifestUri = "http://mirador.local/mirador/manifest/" + entityInfo.entity + '/' + entityInfo.field_name + '/' + entityInfo.entity_id + '/' + entitySettings;
        Mirador({
          "id": "image-viewer",
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

