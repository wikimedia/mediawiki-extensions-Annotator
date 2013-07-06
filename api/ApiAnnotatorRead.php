<?php
class ApiAnnotatorRead extends ApiBase {
	public function execute() {
		$params = $this->extractRequestParams();
		$annotation_id = $params['id']; //get the annotation ID

		$dbr = wfGetDB( DB_SLAVE );
		//select the annotation object from the database
		$res = $dbr->select(
				'annotator',
				array('annotation_json'),
				array(
					'annotation_id' => $annotation_id
					)
				);

		$row = $dbr->fetchObject( $res );

		//return 404 if annotation is not found
		if( !$row ) {
			$this->dieUsage( "No annotation found", 'annotation_not_found', 404 );
		}

		$annotation = json_decode($row->annotation_json);

		$result = $this->getResult();

		$result->addValue( null, 'id', $annotation_id );
		$result->addValue( null, 'text' , $annotation->text );
		$result->addValue( null, 'quote', $annotation->quote );
		$result->addValue( 'ranges', 'start', $annotation->ranges[0]->start );
		$result->addValue( 'ranges', 'startOffset', $annotation->ranges[0]->startOffset );
		$result->addValue( 'ranges', 'end', $annotation->ranges[0]->end );
		$result->addValue( 'ranges', 'endOffset', $annotation->ranges[0]->endOffset );
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