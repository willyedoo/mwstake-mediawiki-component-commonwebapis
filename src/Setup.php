<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs;

use ExtensionRegistry;

class Setup {

	public function onExtensionFunctions() {
		$extensionRegistry = ExtensionRegistry::getInstance();
		$requiredEndpoints = $extensionRegistry->getAttribute( 'MWStakeCommonWebAPIs' );

		$availableEndPoints = [
			'async-menu',
			'async-container',
			'title-query-store',
			'user-query-store',
			'group-query-store',
			'namespace-query-store',
			'page-tree-store'
		];

		$routeFilesDir = dirname( __DIR__ ) . '/route-files/';
		foreach ( $availableEndPoints as $availableEndPoint ) {
			if ( in_array( $availableEndPoint, $requiredEndpoints ) ) {
				$GLOBALS['wgRestAPIAdditionalRouteFiles'][]
					= $routeFilesDir . "$availableEndPoint.json";
			}
		}
	}
}
