<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\GroupStore;

use MWStake\MediaWiki\Component\DataStore\IStore;
use MWStake\MediaWiki\Component\Utils\UtilityFactory;

class Store implements IStore {
	/** @var \MWStake\MediaWiki\Component\Utils\Utility\GroupHelper */
	private $groupHelper;

	/**
	 * @param UtilityFactory $utilityFactory
	 */
	public function __construct( UtilityFactory $utilityFactory ) {
		$this->groupHelper = $utilityFactory->getGroupHelper();
	}

	/**
	 * @return UserSchema
	 */
	public function getSchema() {
		return new GroupSchema();
	}

	/**
	 * @return PrimaryDataProvider
	 */
	public function getReader() {
		return new Reader( $this->groupHelper );
	}

	/**
	 * @return PrimaryDataProvider
	 */
	public function getWriter() {
		throw new NotImplementedException();
	}
}
