<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Rest;

use MediaWiki\HookContainer\HookContainer;
use MWStake\MediaWiki\Component\CommonWebAPIs\Data\GroupStore\Store;
use MWStake\MediaWiki\Component\DataStore\IStore;
use MWStake\MediaWiki\Component\Utils\UtilityFactory;

class GroupStore extends QueryStore {
	/** @var Store */
	private $store;

	/**
	 * @param HookContainer $hookContainer
	 * @param UtilityFactory $utilityFactory
	 */
	public function __construct( HookContainer $hookContainer, UtilityFactory $utilityFactory ) {
		parent::__construct( $hookContainer );
		$this->store = new Store( $utilityFactory );
	}

	/**
	 * @return IStore
	 */
	protected function getStore() : IStore {
		return $this->store;
	}
}
