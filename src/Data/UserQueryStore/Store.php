<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\UserQueryStore;

use GlobalVarConfig;
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
	/** @var \Config */
	private $mwsgConfig;

	/**
	 * @param ILoadBalancer $lb
	 * @param UserFactory $userFactory
	 * @param LinkRenderer $linkRenderer
	 * @param \TitleFactory $titleFactory
	 * @param GlobalVarConfig $mwsgConfig
	 */
	public function __construct(
		ILoadBalancer $lb, UserFactory $userFactory,
		LinkRenderer $linkRenderer, \TitleFactory $titleFactory, GlobalVarConfig $mwsgConfig
	) {
		$this->lb = $lb;
		$this->userFactory = $userFactory;
		$this->linkRenderer = $linkRenderer;
		$this->titleFactory = $titleFactory;
		$this->mwsgConfig = $mwsgConfig;
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
		return new Reader(
			$this->lb, $this->userFactory, $this->linkRenderer,
			$this->titleFactory, $this->mwsgConfig
		);
	}

	/**
	 * @return PrimaryDataProvider
	 */
	public function getWriter() {
		throw new NotImplementedException();
	}
}
