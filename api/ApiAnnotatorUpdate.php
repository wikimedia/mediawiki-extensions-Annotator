<?php
class ApiAnnotatorUpdate extends ApiBase {
	public function execute() {
		$params = $this->extractRequestParams();
		$id = $params['id'];

		//get the annotation object
		$annotation_json = $this->getRawPostString();

		//If the annotation object is null, then die
		if( $annotation_json == null ) {
			$this->dieUsage( "No annotation object received", 'annotation_not_received', 500 );
		}

		//get the user object
		$user = $this->getUser();

		//checks user log in
		if( !$user->isLoggedIn() ) {
			$this->dieUsage( "Log in to update annotation", 'user_not_logged_in', 401 );
		}
		else {
			$userId = $user->getId();
		}

		$annotation_json = json_decode($annotation_json);
		unset($annotation_json->id); //strip out the id element
		unset($annotation_json->user); //strip out the user object
	
		$annotation_json = json_encode($annotation_json);

		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin(); //lock the annotation in the db
		$user_id = $dbw->selectField(
			'annotator',
			'annotation_user_id',
			array(
				'annotation_id' => $id
				),
			__METHOD__,
			array( 'FOR UPDATE' )
			);

		//return 404 if the id is not valid
		if( $user_id === false ) {
			$dbw->rollback();
			$this->dieUsage( "No annotation found", 'annotation_not_found', 404 );
		}

		//checks if the user_id is of the same user who created the annotation
		if( $userId !== (int) $user_id ) {
			$dbw->rollback();
			$this->dieUsage( "You don't have permissions to update this annotation", 'user_not_authorized', 401 );
		}
		
		$dbw->update(
			'annotator',
			array(
				'annotation_json' => $annotation_json
				),
			array(
				'annotation_id' => $id
				)
			);
		$dbw->commit(); //release the lock
		$url = wfExpandUrl( wfScript( 'api' ) . '?action=annotator-read&format=json&id=' . $id, PROTO_CURRENT );
		$response = $this->getRequest()->response();
		$response->header( "Location: {$url}", true, 303 );
		return true;
	}

	public function getRawPostString() {
		return file_get_contents('php://input');
	}

	public function getAllowedParams() {
		return array(
			'id' =>array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true
				)
			);
	}
}