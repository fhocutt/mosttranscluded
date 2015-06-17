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
        // it's super slow though

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

	#TODO
	public function getOrderFields() {
		return array(  ); //the thing I want it sorted by; number of images transcluded on a page
	}

	#TODO
	# I'm taking this from SpecialMostcategories.php, also SpecialMostinterwikis
	# see: https://doc.wikimedia.org/mediawiki-core/master/php/SpecialMostcategories_8php_source.html
/*
	public function preprocessResults( $db, $res ) {
		# ask about this! cacheing etc.

		foreach( $res as $row ) {
			batch->add(  );
		}
	}
*/
	#TODO
	public function formatResult( $skin, $result ) {  //overrides the one in QueryPage
	# ok I think this is making the title safely??
		if ( !$result ) {
			return 'eggs';
		}
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		# it'll return falsey if it can't put that together for some reason, handle that

		# you're making a Link for each Title using the Linker class?
		# dun dun dunnnnn!
#		$link = Linker::link( $title );
#		$count = this->msg( 'nimages' )->numParams( $result->value )->escaped();
#		$resulttoreturn = this->getLanguage()->specialList( $link, $count );
		# IT BREAKS HERE
#		return $resulttoreturn;
		# yeah, this is making sense actually! ok cool. you have to make count htmlsafe, hence all the escaping stuff
		return 'spam';
	}
}
