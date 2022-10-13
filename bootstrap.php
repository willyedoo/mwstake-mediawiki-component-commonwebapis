<?php

if ( defined( 'MWSTAKE_MEDIAWIKI_COMPONENT_COMMONWEBAPIS_VERSION' ) ) {
	return;
}

define( 'MWSTAKE_MEDIAWIKI_COMPONENT_COMMONWEBAPIS_VERSION', '1.0.2' );

MWStake\MediaWiki\ComponentLoader\Bootstrapper::getInstance()
	->register( 'commonwebapis', function () {
		$GLOBALS['wgExtensionFunctions'][]
			= "\\MWStake\\MediaWiki\\Component\\CommonWebAPIs\\Setup::onExtensionFunctions";
		$GLOBALS['wgServiceWiringFiles'][] = __DIR__ . '/includes/ServiceWiring.php';

		$GLOBALS['wgHooks']['ResourceLoaderRegisterModules'][] = function ( $resourceLoader ) {
			$resourceLoader->register(
				[
					'ext.mws.commonwebapis' => [
						'localBasePath' => __DIR__ . '/resources',
						'scripts' => [ "api.js" ],
					]
				]
			);
		};
	} );
