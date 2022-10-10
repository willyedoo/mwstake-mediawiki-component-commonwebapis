<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\UserQueryStore;

use MWStake\MediaWiki\Component\DataStore\ISecondaryDataProvider;

class SecondaryDataProvider implements ISecondaryDataProvider {
	private $userFactory;
	private $linkRenderer;
	private $titleFactory;

	public function __construct( $userFactory, $linkRenderer, $titleFactory ) {
		$this->userFactory = $userFactory;
		$this->linkRenderer = $linkRenderer;
		$this->titleFactory = $titleFactory;
	}

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
