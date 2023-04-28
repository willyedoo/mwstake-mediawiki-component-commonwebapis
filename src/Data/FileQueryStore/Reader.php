<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\FileQueryStore;

use MWStake\MediaWiki\Component\DataStore\ReaderParams;
use Wikimedia\Rdbms\ILoadBalancer;

class Reader extends \MWStake\MediaWiki\Component\DataStore\Reader {
	/** @var ILoadBalancer */
	protected $lb;
	/** @var \TitleFactory */
	protected $titleFactory;
	/** @var \Language */
	protected $language;
	/** @var \NamespaceInfo */
	protected $nsInfo;

	/**
	 * @param ILoadBalancer $lb
	 * @param \TitleFactory $titleFactory
	 * @param \Language $language
	 * @param \NamespaceInfo $nsInfo
	 */
	public function __construct(
		ILoadBalancer $lb, \TitleFactory $titleFactory, \Language $language, \NamespaceInfo $nsInfo
	) {
		parent::__construct();
		$this->lb = $lb;
		$this->titleFactory = $titleFactory;
		$this->language = $language;
		$this->nsInfo = $nsInfo;
	}

	/**
	 * @return FileSchema
	 */
	public function getSchema() {
		return new FileSchema();
	}

	/**
	 * @param ReaderParams $params
	 *
	 * @return PrimaryDataProvider
	 */
	public function makePrimaryDataProvider( $params ) {
		return new PrimaryDataProvider(
			$this->lb->getConnection( DB_REPLICA ), $this->getSchema(), $this->language, $this->nsInfo
		);
	}

	/**
	 * @return SecondaryDataProvider
	 */
	public function makeSecondaryDataProvider() {
		return new SecondaryDataProvider( $this->titleFactory, $this->language );
	}
}
