/**
 * Script for calling the annotations
 */
( function( mw, $ ) {
	$( function( $ ) {
		var revid, userId, $contentText, pTabsId, caViewAnnotations, isViewingAnnotations;
		isViewingAnnotations = false;

		function loadAnnotations() {
			isViewingAnnotations = true;
			if( userId === null ) {
				$contentText.annotator({ readOnly: true });
			}
			else {
				$contentText.annotator();
			}

			//Add the store plugin and modify the urls according to mediawiki api
			$contentText.annotator( 'addPlugin', 'Store', {
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
			$contentText.annotator('addPlugin', 'Permissions', {
				
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

		}

		//Get the Revision Id of the page
		revid = mw.config.get( 'wgCurRevisionId' );
		//Get the userId
		userId = mw.config.get( 'wgUserId' );
		//Define where the annotations need to be called
		$contentText = $( '#mw-content-text' );

		//Add a tab to view the page with annotations
		pTabsId = $( '#p-views' ).length ?  'p-views' : 'p-cactions';
		caViewAnnotations = mw.util.addPortletLink(
			pTabsId,
			'',
			mw.msg('annotator-view-annotations'),
			'ca-view-annotations'
			);

		//load the annotations when the tab is clicked
		$('#ca-view-annotations').click( function(e) {
			$( '#' + pTabsId ).find( 'li.selected' ).removeClass( 'selected' );
			$(this).addClass( 'selected' );
			mw.loader.using( 'mediawiki.libs.okfn', loadAnnotations );
			e.preventDefault();
		});

		$( '#' + pTabsId ).find( '[id^="ca-nstab-"], #ca-view' ).click( function(e) {
			//Destroy annotations when click on other tabs without reloading
			if( isViewingAnnotations ) {
				$contentText.annotator('destroy');
				$('#ca-view-annotations').removeClass('selected');
				$(this).addClass('selected');
				isViewingAnnotations = false;
				e.preventDefault();
			}
		});

	} );
}( mediaWiki, jQuery ) );
