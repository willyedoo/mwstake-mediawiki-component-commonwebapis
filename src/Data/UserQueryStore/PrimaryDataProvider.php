<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\UserQueryStore;

use MWStake\MediaWiki\Component\DataStore\Filter;
use MWStake\MediaWiki\Component\DataStore\PrimaryDatabaseDataProvider;
use MWStake\MediaWiki\Component\DataStore\ReaderParams;
use MWStake\MediaWiki\Component\DataStore\Schema;
use Wikimedia\Rdbms\IDatabase;

class PrimaryDataProvider extends PrimaryDatabaseDataProvider {
	/** @var array */
	private $groups = [];
	/** @var array */
	private $blocks = [];

	/**
	 * @inheritDoc
	 */
	public function makeData( $params ) {
		$this->getSupportingData( $params );
		return parent::makeData( $params );
	}

	private function getSupportingData() {
		$this->groups = $this->getGroups();
		$this->blocks = $this->getBlocks();
	}

	/**
	 * @return array
	 */
	private function getGroups() {
		$res = $this->db->select(
			'user_groups',
			[ 'ug_user', 'ug_group' ],
			[],
			__METHOD__
		);
		$groups = [];
		foreach ( $res as $row ) {
			$groups[$row->ug_user][] = $row->ug_group;
		}
		return $groups;
	}

	/**
	 * @return array
	 */
	private function getBlocks() {
		$blocks = [];
		$blocksRes = $this->db->select( 'ipblocks', '*', '', __METHOD__ );
		foreach ( $blocksRes as $row ) {
			$blocks[$row->ipb_user] = $row->ipb_address;
		}

		return $blocks;
	}

	/**
	 * @param ReaderParams $params
	 *
	 * @return array
	 */
	protected function makePreFilterConds( ReaderParams $params ) {
		$conds = parent::makePreFilterConds( $params );
		$query = $params->getQuery();
		if ( $query !== '' ) {
			$conds[] = $this->db->makeList(
				[
					'user_name ' . $this->db->buildLike( $this->db->anyString(), $query, $this->db->anyString() ),
					'user_real_name ' . $this->db->buildLike( $this->db->anyString(), $query, $this->db->anyString() )
				],
				LIST_OR
			);
		}

		return $conds;
	}

	/**
	 * @param \stdClass $row
	 *
	 * @return void
	 */
	protected function appendRowToData( \stdClass $row ) {
		$resultRow = [
			'user_id' => (int)$row->user_id,
			'user_name' => $row->user_name,
			'user_real_name' => $row->user_real_name,
			'user_registration' => $row->user_registration,
			'user_editcount' => (int)$row->user_editcount,
			'groups' => isset( $this->groups[$row->user_id] ) ? $this->groups[$row->user_id] : [],
			'enabled' => isset( $this->blocks[$row->user_id] ) ? false : true,
			// legacy fields
			'display_name' => $row->user_real_name == null ? $row->user_name : $row->user_real_name,
		];
		$this->data[] = new UserRecord( (object)$resultRow );
	}

	/**
	 * @return string[]
	 */
	protected function getTableNames() {
		return [ 'user' ];
	}
}
