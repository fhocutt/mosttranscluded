<?php
/**
 * MostTranscludedImages SpecialPage for MostTranscludedImages extension
 *
 * @file
 * @ingroup Extensions
 * @author Frances Hocutt, 2015
 */

class SpecialMosttranscludedimages extends QueryPage {

	/**
	 * Initialize the special page.
	 */
	public function __construct( $name='MostTranscludedImages' ) {
		// A special page should at least have a name.
		// We do this by calling the parent class (the SpecialPage class)
		// constructor method with the name as first and only parameter.
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
        // select il_from,il_from_namespace,count(*) AS Cnt FROM imagelinks GROUP BY il_from  ORDER BY Cnt DESC;
        // Probably problems with this; see comments at end

		return array(
			'tables' => array( 'imagelinks', 'page' ),
			'fields' => array(
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'count(*)'
			),
			'join_conds' => array( 'page_id=il_from' ),
			'conds' => array(),
			'options' => array( 'GROUP BY' => 'il_from' ),
		);
	}

	public function getOrderFields() {
		return array( 'value' ); //sort by count
	}

	#TODO
	# I'm taking this from SpecialMostcategories.php, also SpecialMostinterwikis
	# see: https://doc.wikimedia.org/mediawiki-core/master/php/SpecialMostcategories_8php_source.html
	# How does this work? What's the deal with it? Obviously involves DB,
	# cache, some sort of batch processing.
	public function preprocessResults( $db, $res ) {
		if( !$this->isCached() || !$res->numRows() ) {
			return;
		}

		$batch = new LinkBatch();
		foreach( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();

		$res->seek( 0 );
	}



	//Nabbed from SpecialMostcategories.php with a different message
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

	#FIXME
		/*
		return var_dump( $result );

		/* This yields the following at the top of the page: 

object(stdClass)#573 (3) { ["namespace"]=> string(1) "0" 
                        ["title"]=> string(9) "Main_Page" 
                        ["value"]=> string(1) "7" }
object(stdClass)#579 (3) { ["namespace"]=> string(1) "0" 
                        ["title"]=> string(9) "Main_Page" 
                        ["value"]=> string(2) "21" }
object(stdClass)#580 (3) { ["namespace"]=> string(1) "0" 
                        ["title"]=> string(9) "Main_Page" 
                        ["value"]=> string(2) "14" }

		On my vagrant-backed wiki on localhost, there should be three entries.
		One should have 3 images, one 2, one 1, and only one of these
		pages is Main_Page. Problem with the query?
		*/
//	}
}
