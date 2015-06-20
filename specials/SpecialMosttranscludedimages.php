<?php
/**
 * MostTranscludedImages SpecialPage for MostTranscludedImages extension
 *
 * @file
 * @ingroup Extensions
 * @author Frances Hocutt, 2015
 */

class SpecialMosttranscludedimages extends QueryPage {

	public function __construct( $name='MostTranscludedImages' ) {
		parent::__construct( $name );
	}

	protected function getGroupName() {
		return 'highuse';
	}

	public function isSyndicated() {
		return false;
	}

	public function isExpensive() {
		return true;
	}

	public function getQueryInfo() {
        // Query this tries to duplicate: 
	// SELECT page_namespace, page_title, count(*) 
	//	FROM imagelinks 
	// 	JOIN page ON page_id=il_from 
	// 	GROUP BY page_title
	//	ORDER BY count(*)
	//	DESC;

		return array(
			'tables' => array( 'imagelinks', 'page' ),
			'fields' => array(
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'count(*)'
			),
			'join_conds' => array( 'page' => array(
				JOIN, 'page_id=il_from' )),
			'conds' => array(),
			'options' => array( 'GROUP BY' => 'il_from' ),
		);
	}

	public function getOrderFields() {
		return array( 'value' ); 
	}

	// TODO Refactor this, it is common to this type of QueryPage
	public function preprocessResults( $db, $res ) {
		// If query is not cached or has no results, there are no known links to process
		if ( !$this->isCached() || !$res->numRows() ) {
			return;
		}

		// Otherwise, check all rows in cached result for links' current existence
		$batch = new LinkBatch();
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();

		$res->seek( 0 );
	}

	// TODO Refactor this, it is common to this type of QueryPage
	function formatResult( $skin, $result ) {
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
			return Html::element(
				'span',
				array( 'class' => 'mw-invalidtitle' ),
				Linker::getInvalidTitleDescription(
					$this->getContext(),
					$result->namespace,
					$result->title
				)
			);
		}

		if ( $this->isCached() ) {
			$link = Linker::link( $title );
		} else {
			$link = Linker::linkKnown( $title );
		}
 
		$count = $this->msg( 'nimages' )->numParams( $result->value )->escaped();
 
		return $this->getLanguage()->specialList( $link, $count );
	}
}
