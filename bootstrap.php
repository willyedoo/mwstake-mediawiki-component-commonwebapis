<?php

use MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook;

if ( defined( 'MWSTAKE_MEDIAWIKI_COMPONENT_COMMONWEBAPIS_VERSION' ) ) {
	return;
}

define( 'MWSTAKE_MEDIAWIKI_COMPONENT_COMMONWEBAPIS_VERSION', '1.0.17' );

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
		$GLOBALS['wgHooks']['LoadExtensionSchemaUpdates'][] = static function( $updater ) {
			$updater->addExtensionTable(
				'mws_user_index',
				__DIR__ . "/sql/mws_user_index.sql"
			);

			$updater->addPostDatabaseUpdateMaintenance(
				\MWStake\MediaWiki\Component\CommonWebAPIs\Maintenance\PopulateUserIndex::class
			);
		};
		$GLOBALS['wgHooks']['UserSaveSettings'][] = static function( User $user ) {
			$lb = \MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer();
			$userIndexUpdater = new \MWStake\MediaWiki\Component\CommonWebAPIs\UserIndexUpdater( $lb );
			$userIndexUpdater->store( $user );
		};
		// Exclude users from these groups in user store
		$GLOBALS['mwsgCommonWebAPIsComponentUserStoreExcludeGroups'] = [ 'bot' ];
		// Exclude porticular users from user store
		$GLOBALS['mwsgCommonWebAPIsComponentUserStoreExcludeUsers'] = [
			'MediaWiki default', 'Mediawiki default'
		];
		// Exclude these groups from group store
		$GLOBALS['mwsgCommonWebAPIsComponentGroupStoreExcludeGroups'] = [ 'bot' ];
	} );
