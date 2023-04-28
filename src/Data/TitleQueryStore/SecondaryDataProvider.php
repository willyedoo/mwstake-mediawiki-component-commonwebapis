<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\TitleQueryStore;

use MWStake\MediaWiki\Component\DataStore\ISecondaryDataProvider;
use MWStake\MediaWiki\Component\DataStore\Record;

class SecondaryDataProvider implements ISecondaryDataProvider {
	/** @var \TitleFactory */
	protected $titleFactory;
	/** @var \Language */
	protected $language;

	/**
	 * @param \TitleFactory $titleFactory
	 * @param \Language $language
	 */
	public function __construct( $titleFactory, \Language $language ) {
		$this->titleFactory = $titleFactory;
		$this->language = $language;
	}

	/**
	 * @param array $dataSets
	 *
	 * @return \MWStake\MediaWiki\Component\DataStore\Record[]
	 */
	public function extend( $dataSets ) {
		foreach ( $dataSets as $dataSet ) {
			$title = $this->titleFromRecord( $dataSet );

			$dataSet->set( TitleRecord::PAGE_TITLE, $title->getText() );
			$dataSet->set( TitleRecord::PAGE_PREFIXED, $title->getPrefixedText() );
			$dataSet->set( TitleRecord::PAGE_URL, $title->getLocalURL() );
			$dataSet->set(
				TitleRecord::PAGE_NAMESPACE_TEXT, $this->language->getNsText( $title->getNamespace() )
			);
		}

		return $dataSets;
	}

	/**
	 * @param Record $record
	 *
	 * @return \Title|null
	 */
	protected function titleFromRecord( $record ) {
		return $this->titleFactory->newFromID( $record->get( TitleRecord::PAGE_ID ) );
	}
}
