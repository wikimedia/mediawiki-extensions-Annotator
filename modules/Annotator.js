/* Script for calling the annotations

*/
( function ( mw, $ ) {
  $( function( $ ) {
    //get the endpoint of the api
    this.apiUrl = mw.util.wikiScript('api');
    //Get the Revision Id of the page
    var revid = mw.config.get( 'wgCurRevisionId' );
    //Call the annotations
    var annotations = $('#mw-content-text').annotator();
    //Add the store plugin and modify the urls according to mediawiki api
    annotations.annotator('addPlugin', 'Store', {
      prefix: this.apiUrl,
      urls: {
        create: '?action=annotator-create&format=json&revid=' + revid,
        update: '',
        read: '?action=annotator-read&format=json&id=:id',
        destroy: '',
        search: '?action=annotator-search&format=json'
      },
      loadFromSearch: {
        'revid': revid
      }
    });
  } )
}( mediaWiki, jQuery ) );
