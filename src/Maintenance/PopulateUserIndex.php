<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Maintenance;

use Maintenance;

class PopulateUserIndex extends \LoggedUpdateMaintenance {
	/**
	 * @return bool
	 */
	protected function doDBUpdates() {
		$db = $this->getDB( DB_PRIMARY );

		$users = $db->select(
			'user',
			[ 'user_id', 'user_name', 'user_real_name' ],
			[],
			__METHOD__
		);

		$toInsert = [];
		$cnt = 0; $batch = 250;
		foreach( $users as $user ) {
			$toInsert[] = [
				'mui_user_id' => $user->user_id,
				'mui_user_name' => mb_strtolower( $user->user_name ),
				'mui_user_real_name' => mb_strtolower( $user->user_real_name )
			];
			if ( $cnt % $batch === 0 ) {
				$db->insert(
					'mws_user_index',
					$toInsert,
					__METHOD__,
					[ 'IGNORE' ]
				);
				$toInsert = [];
			}
		}

		$this->output( "Indexed $cnt users\n" );

		return true;
	}

	/**
	 * @return string
	 */
	protected function getUpdateKey() {
		return 'mws-user-index-init';
	}
}
