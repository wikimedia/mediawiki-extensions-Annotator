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

		//selects annotations of a particular revision ID
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			'annotator',
			array(
				'annotation' => 'annotation_json',
				'id' => 'annotation_id'
				),
			array(
				'rev_id' => $revid
				)
			);

		$annotations = array();
		$annotations['rows'] = array();
		$total = 0;
		foreach($res as $result) {
			$annotation = json_decode($result->annotation);
			$annotation->id = $result->id; //update the annotation object with the annotation ID
			$annotations['rows'][] = $annotation;
			$total = $total + 1;
		}
		$annotations['total'] = $total;
		$result = $this->getResult();
		$result->addValue( null, 'rows' , $annotations['rows'] );
		$result->addValue( null, 'total', $total );
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