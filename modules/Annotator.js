/**
 * Script for calling the annotations
 */
( function( mw, $ ) {
	$( function( $ ) {
		var revid, annotations;

		//Get the Revision Id of the page
		revid = mw.config.get( 'wgCurRevisionId' );
		//Call the annotations
		annotations = $( '#mw-content-text' ).annotator();
		//Add the store plugin and modify the urls according to mediawiki api
		annotations.annotator( 'addPlugin', 'Store', {
			prefix: mw.util.wikiScript( 'api' ),
			urls: {
				create: '?action=annotator-create&format=json&revid=' + revid,
				update: '?action=annotator-update&format=json&id=:id',
				read: '?action=annotator-read&format=json&id=:id',
				destroy: '?action=annotator-destroy&format=json&id=:id',
				search: '?action=annotator-search&format=json'
			},
			loadFromSearch: {
				revid: revid
			}
		} );
	} );
}( mediaWiki, jQuery ) );
