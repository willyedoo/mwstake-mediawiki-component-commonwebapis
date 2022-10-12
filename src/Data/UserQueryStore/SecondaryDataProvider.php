<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\UserQueryStore;

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\User\UserFactory;
use MWStake\MediaWiki\Component\DataStore\ISecondaryDataProvider;

class SecondaryDataProvider implements ISecondaryDataProvider {
	/** @var UserFactory */
	private $userFactory;
	/** @var LinkRenderer */
	private $linkRenderer;
	/** @var \TitleFactory */
	private $titleFactory;

	/**
	 * @param UserFactory $userFactory
	 * @param LinkRenderer $linkRenderer
	 * @param \TitleFactory $titleFactory
	 */
	public function __construct( $userFactory, $linkRenderer, $titleFactory ) {
		$this->userFactory = $userFactory;
		$this->linkRenderer = $linkRenderer;
		$this->titleFactory = $titleFactory;
	}

	/**
	 * @params array $dataSets
	 *
	 * @return \MWStake\MediaWiki\Component\DataStore\Record[]
	 */
	public function extend( $dataSets ) {
		foreach ( $dataSets as $dataSet ) {
			$userPage = $this->titleFactory->makeTitle( NS_USER, $dataSet->get( 'user_name' ) );
			$userPageLink = $this->linkRenderer->makeLink(
				$userPage,
				// The whitespace is to aviod automatic rewrite to user_real_name by BSF
				$dataSet->get( 'user_name' ) . ' '
			);
			$dataSet->set( 'user_page_link', $userPageLink );
			$dataSet->set( 'page_prefixed_text', $userPage->getPrefixedText() );
		}

		return $dataSets;
	}
}
