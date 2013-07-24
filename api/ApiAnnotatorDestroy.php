<?php
class ApiAnnotatorDestroy extends ApiBase {
	public function execute() {
		$params = $this->extractRequestParams();
		$id = $params['id'];

		//get the user object
		$user = $this->getUser();

		//checks user log in
		if( !$user->isLoggedIn() ) {
			$this->dieUsage( "Log in to delete annotation", 'user_not_logged_in', 401 );
		}
		else {
			$userId = $user->getId();
		}

		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->select(
			'annotator',
			array(
				'annotation_user_id'
				),
			array(
				'annotation_id' => $id
				)
			);
		$row = $dbw->fetchObject( $res );

		//return 404 if the id is not valid
		if( !$row ) {
			$this->dieUsage( "No annotation found", 'annotation_not_found', 404 );
		}

		//checks if the user_id is of the same user who created the annotation
		if( $userId !== intval( $row->annotation_user_id ) ) {
			$this->dieUsage( "You don't have permissions to destroy this annotation", 'user_not_authorized', 401 );
		}

		$dbw->delete(
			'annotator',
			array(
				'annotation_id' => $id
				)
			);

		$response = $this->getRequest()->response();
		$response->header( 'HTTP/1.0 204 No Content' );
		return true;
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