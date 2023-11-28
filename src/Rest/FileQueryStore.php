<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Rest;

use MediaWiki\HookContainer\HookContainer;
use MWStake\MediaWiki\Component\CommonWebAPIs\Data\FileQueryStore\Store;
use MWStake\MediaWiki\Component\DataStore\IStore;
use Wikimedia\Rdbms\ILoadBalancer;

class FileQueryStore extends QueryStore {
	/** @var Store */
	private $store;

	/**
	 * @param HookContainer $hookContainer
	 * @param ILoadBalancer $lb
	 * @param \TitleFactory $titleFactory
	 * @param \Language $language
	 * @param \NamespaceInfo $nsInfo
	 */
	public function __construct(
		HookContainer $hookContainer, ILoadBalancer $lb, \TitleFactory $titleFactory,
		\Language $language, \NamespaceInfo $nsInfo
	) {
		parent::__construct( $hookContainer );
		$this->store = new Store( $lb, $titleFactory, $language, $nsInfo );
	}

	/**
	 * @return IStore
	 */
	protected function getStore(): IStore {
		return $this->store;
	}
}
