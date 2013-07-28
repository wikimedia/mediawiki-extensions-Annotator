<?php
class ApiAnnotatorSearch extends ApiBase {
	function execute() {
		$params = $this->extractRequestParams();
		$revid = $params['revid']; //get the revision ID
		$revision = Revision::newFromId( $revid );

		//return 404 if the revision is not valid
		if( $revision === null ) {
			$this->dieUsage( "The revision ID is not valid", 'invalid_revision_id', 404 );
		}

		$repository = new AnnotationRepository();
		$annotations = $repository->getAllByRevid( $revid );

		$result = $this->getResult();

		foreach( $annotations as $k => &$v ) {
			$result->addValue( null, $k, $v );
		}
		return true;
	}

	public function getAllowedParams() {
        return array(
            'revid' =>array(
                ApiBase::PARAM_TYPE => 'integer',
                ApiBase::PARAM_REQUIRED => true
                )
            );
    }
}