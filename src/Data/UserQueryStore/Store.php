<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\UserQueryStore;

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\User\UserFactory;
use MWStake\MediaWiki\Component\DataStore\IStore;
use Wikimedia\Rdbms\ILoadBalancer;

class Store implements IStore {
	/** @var ILoadBalancer */
	private $lb;
	/** @var UserFactory */
	private $userFactory;
	/** @var LinkRenderer */
	private $linkRenderer;
	/** @var \TitleFactory */
	private $titleFactory;

	/**
	 * @param ILoadBalancer $lb
	 * @param UserFactory $userFactory
	 * @param LinkRenderer $linkRenderer
	 * @param \TitleFactory $titleFactory
	 */
	public function __construct(
		ILoadBalancer $lb, UserFactory $userFactory,
		LinkRenderer $linkRenderer, \TitleFactory $titleFactory
	) {
		$this->lb = $lb;
		$this->userFactory = $userFactory;
		$this->linkRenderer = $linkRenderer;
		$this->titleFactory = $titleFactory;
	}

	/**
	 * @return UserSchema
	 */
	public function getSchema() {
		return new UserSchema();
	}

	/**
	 * @return PrimaryDataProvider
	 */
	public function getReader() {
		return new Reader( $this->lb, $this->userFactory, $this->linkRenderer, $this->titleFactory );
	}

	/**
	 * @return PrimaryDataProvider
	 */
	public function getWriter() {
		throw new NotImplementedException();
	}
}
