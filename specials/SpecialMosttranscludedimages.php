<?php
/**
 * MostTranscludedImages SpecialPage for Example extension
 *
 * @file
 * @ingroup Extensions
 */

class SpecialMosttranscludedimages extends QueryPage {

	/**
	 * Initialize the special page.
	 */
	public function __construct() {
		// A special page should at least have a name.
		// We do this by calling the parent class (the SpecialPage class)
		// constructor method with the name as first and only parameter.
		parent::__construct( 'MostTranscludedImages' ); //this is where the page link comes from, what's the deal? That I didn't have a mosttranscludedimages string in i18n/en.json! remember, add it to qqq.json too.
	}

	/**
	 * Shows the page to the user.
	 * @param string $sub: The subpage string argument (if any).
	 *  [[Special:HelloWorld/subpage]].

See if this is actually needed and how/if it's implemented in QueryPage.

	 */
/*	public function execute( $sub ) {
		$out = $this->getOutput();

		$out->setPageTitle( $this->msg( 'example-mosttranscluded' ) );

		// Parses message from .i18n.php as wikitext and adds it to the
		// page output.
		$out->addWikiMsg( 'example-mosttranscluded-intro' );
	}

	protected function getGroupName() {
		return 'maintenance';
	}
*/

    // from QueryPage

    //No need to have a feed for this.
    public function isSyndicated() {
        return false;
    }

    // things I see: tables, fields, conds, and options. Seems to set
    // parameters for some query that's executed elsewhere, because it's
    // just returning an array?
    public function getQueryInfo() {
        // select il_from,il_from_namespace,count(*) AS Cnt FROM imagelinks GROUP BY il_from  ORDER BY Cnt DESC;
        // turn this into an array per docs and Roan's explanation
        // it's super slow though

        return array(
            'tables' => array( 'imagelinks', 'page' ),
            'fields' => array(
                'namespace' => 'page_namespace',
                'title' => 'page_title',
                'value' => 'count(*)'
            ),
            'join_conds' => array( 'page_id=il_from' ),
            'conds' => array(
            ),
             'options' => array( 'GROUP BY' => 'il_from' ),
         );

        // tables: imagelinks

        // Fields:
        // namespace => page.page_namespace
        // value => count(*)
        // title => page.page_title

        // Conds: no WHERE

        //Join? JOIN page
        // JOIN conds:  ON page_id = il_from

    }

    public function getOrderFields() {
        return array(  ); //the thing I want it sorted by; number of images transcluded on a page
    }

    public function sortDescending() {
        return true; //don't think this is needed, copying from SpecialShortpage
    }

    public function formatResult( $skin, $result ) {  //overrides the one in QueryPage
        return '';
    }
}
