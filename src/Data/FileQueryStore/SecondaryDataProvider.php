<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\FileQueryStore;

use MWStake\MediaWiki\Component\CommonWebAPIs\Data\TitleQueryStore\SecondaryDataProvider
	as TitleSecondaryDataProvider;
use MWStake\MediaWiki\Component\CommonWebAPIs\Data\TitleQueryStore\TitleRecord;

class SecondaryDataProvider extends TitleSecondaryDataProvider {

	/**
	 * @param array $dataSets
	 *
	 * @return \MWStake\MediaWiki\Component\DataStore\Record[]
	 */
	public function extend( $dataSets ) {
		$dataSets = parent::extend( $dataSets );
		foreach ( $dataSets as $dataSet ) {
			$title = $this->titleFromRecord( $dataSet );
			$dataSet->set( TitleRecord::PAGE_PREFIXED, $title->getText() );
		}

		return $dataSets;
	}
}
