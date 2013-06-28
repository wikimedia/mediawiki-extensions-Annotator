/* Script for calling the annotations

*/
( function ( mw, $ ) {
  $( function( $ ) {
    //get the endpoint of the api
    this.apiUrl = mw.util.wikiScript('api');
    //Call the annotations
    var annotations = $('#mw-content-text').annotator();
    //Add the store plugin and modify the urls according to mediawiki api
    annotations.annotator('addPlugin', 'Store', {
      prefix: this.apiUrl,
      urls: {
        create: '',
        update: '',
        read: '',
        destroy: '',
      }
    });
  } )
}( mediaWiki, jQuery ) );