/**
 * Script for calling the annotations
 */
( function( mw, $ ) {
	$( function( $ ) {
		var revid, annotations, userId;

		//Get the Revision Id of the page
		revid = mw.config.get( 'wgCurRevisionId' );
		//Get the userId
		userId = mw.config.get( 'wgUserId' );
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

    //add the permissions plugin
    annotations.annotator('addPlugin', 'Permissions', {
      
      user: {
        id: userId,
        username: mw.user.getName()
      },
      permissions: {
        'read': [],
        'update': [ userId ],
        'delete': [ userId ]
      },
      userId: function (user) {
        if( user && user.id ) {
          return user.id;
        }
        return user;
      },
      userString: function (user) {
        if( user && user.username ) {
          return user.username;
        }
        return user;
      },
      showViewPermissionsCheckbox: false,
      showEditPermissionsCheckbox: false
    });
  } )
}( mediaWiki, jQuery ) );
