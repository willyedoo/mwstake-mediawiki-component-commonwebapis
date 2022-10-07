<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Rest;

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\User\UserFactory;
use MWStake\MediaWiki\Component\CommonWebAPIs\Data\UserQueryStore\Store;
use MWStake\MediaWiki\Component\DataStore\IStore;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\Rdbms\ILoadBalancer;

class UserQueryStore extends QueryStore {
	private $store;

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
		$this->store = new Store( $lb, $userFactory, $linkRenderer, $titleFactory );
	}

	protected function getStore() : IStore {
		return $this->store;
	}

	public function getStoreSpecificParams() : array {
		return [
			'query' => [
				static::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_TYPE => 'string',
			]
		];
	}
}
