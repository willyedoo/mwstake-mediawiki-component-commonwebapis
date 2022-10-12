<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs;

use ExtensionRegistry;
use MediaWiki\MediaWikiServices;

class Setup {

	public function onExtensionFunctions() {

		$endpointManager = MediaWikiServices::getInstance()->getService(
			'MWStakeCommonWebAPIsEndpointManager'
		);
		$endpointManager->enableEndpoints();
	}
}
