<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\GroupStore;

use MWStake\MediaWiki\Component\DataStore\ReaderParams;
use MWStake\MediaWiki\Component\Utils\Utility\GroupHelper;

class Reader extends \MWStake\MediaWiki\Component\DataStore\Reader {
	/** @var \MWStake\MediaWiki\Component\Utils\Utility\GroupHelper */
	private $groupHelper;

	/**
	 * @param GroupHelper $groupHelper
	 */
	public function __construct( GroupHelper $groupHelper ) {
		$this->groupHelper = $groupHelper;
	}

	/**
	 * @return UserSchema
	 */
	public function getSchema() {
		return new GroupSchema();
	}

	/**
	 * @param ReaderParams $params
	 *
	 * @return PrimaryDataProvider
	 */
	public function makePrimaryDataProvider( $params ) {
		return new PrimaryDataProvider( $this->groupHelper );
	}

	/**
	 * @inheritDoc
	 */
	public function makeSecondaryDataProvider() {
		return null;
	}
}
