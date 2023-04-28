<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Maintenance;

class PopulateTitleIndex extends \LoggedUpdateMaintenance {
	/**
	 * @return bool
	 */
	protected function doDBUpdates() {
		$db = $this->getDB( DB_PRIMARY );

		$titles = $db->select(
			'page',
			[ 'page_id', 'page_namespace', 'page_title' ],
			[],
			__METHOD__
		);

		$toInsert = [];
		$cnt = 0;
$batch = 250;
		foreach ( $titles as $title ) {
			$toInsert[] = [
				'mti_page_id' => $title->page_id,
				'mti_namespace' => mb_strtolower( $title->page_namespace ),
				'mti_title' => mb_strtolower( str_replace( '_', ' ', $title->page_title ) )
			];
			if ( $cnt % $batch === 0 ) {
				$db->insert(
					'mws_title_index',
					$toInsert,
					__METHOD__,
					[ 'IGNORE' ]
				);
				$toInsert = [];
			}
		}

		$this->output( "Indexed $cnt pages\n" );

		return true;
	}

	/**
	 * @return string
	 */
	protected function getUpdateKey() {
		return 'mws-title-index-init';
	}
}
