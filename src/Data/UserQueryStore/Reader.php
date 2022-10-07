<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\UserQueryStore;

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\User\UserFactory;
use MWStake\MediaWiki\Component\DataStore\IReader;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LoadBalancer;

class Reader extends \MWStake\MediaWiki\Component\DataStore\Reader {
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
		parent::__construct();
		$this->lb = $lb;
		$this->userFactory = $userFactory;
		$this->linkRenderer = $linkRenderer;
		$this->titleFactory = $titleFactory;
	}

	public function getSchema() {
		return new UserSchema();
	}

	public function makePrimaryDataProvider( $params ) {
		return new PrimaryDataProvider(
			$this->lb->getConnection( DB_REPLICA ), $this->getSchema()
		);
	}

	public function makeSecondaryDataProvider() {
		return new SecondaryDataProvider( $this->userFactory, $this->linkRenderer, $this->titleFactory );
	}
}
