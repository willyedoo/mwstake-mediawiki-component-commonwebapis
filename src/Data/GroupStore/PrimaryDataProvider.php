<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\GroupStore;

use MWStake\MediaWiki\Component\DataStore\IPrimaryDataProvider;
use MWStake\MediaWiki\Component\DataStore\ReaderParams;
use MWStake\MediaWiki\Component\Utils\Utility\GroupHelper;

class PrimaryDataProvider implements IPrimaryDataProvider {
	/**
	 * @var GroupHelper
	 */
	private $groupHelper;

	/**
	 * @param GroupHelper $groupHelper
	 */
	public function __construct( GroupHelper $groupHelper ) {
		$this->groupHelper = $groupHelper;
	}

	/**
	 * @param ReaderParams $params
	 *
	 * @return array
	 */
	public function makeData( $params ) {
		$query = strtolower( $params->getQuery() );

		$data = [];
		$explicitGroups = $this->groupHelper->getAvailableGroups( [ 'filter' => [ 'explicit' ] ] );
		foreach ( $explicitGroups as $group ) {
			$displayName = $group;
			$msg = \Message::newFromKey( "group-$group" );
			if ( $msg->exists() ) {
				$displayName = $msg->plain() . " ($group)";
			}

			if ( !$this->queryApplies( $query, $group, $displayName ) ) {
				continue;
			}

			$data[] = new GroupRecord( (object)[
				'group_name' => $group,
				'additional_group' => ( $this->groupHelper->getGroupType( $group ) === 'custom' ),
				'group_type' => $this->groupHelper->getGroupType( $group ),
				'displayname' => $displayName,
			] );
		}
		return $data;
	}

	/**
	 * @param string $query
	 * @param string $group
	 * @param string $displayName
	 *
	 * @return bool
	 */
	private function queryApplies( $query, $group, $displayName ): bool {
		if ( $query === '' ) {
			return true;
		}
		$query = mb_strtolower( $query );
		if ( strpos( mb_strtolower( $group ), $query ) !== false ) {
			return true;
		}
		if ( strpos( mb_strtolower( $displayName ), $query ) !== false ) {
			return true;
		}

		return false;
	}
}
