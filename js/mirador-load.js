(function($) {
  $(function() {
    Drupal.behaviors.mirador = {
      attach:function(context, settings) {
        entityInfo = drupalSettings.init.entity.entityInfo;
        var entityInfo = jQuery.parseJSON(entityInfo);
        var manifestUri = "http://mirador.local/mirador/manifest/" + entityInfo.entity + '/' + entityInfo.field_name + '/' + entityInfo.entity_id;
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
       { "manifestUri": manifestUri, "location": "National Library of Wales"},
        ],
        "windowObjects":[{
          "loadedManifest" : manifestUri,
          "viewType" : "ImageView"} ],
      });
      }
    }
  });
})(jQuery);

