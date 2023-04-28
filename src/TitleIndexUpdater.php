<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs;

use ManualLogEntry;
use MediaWiki\Hook\AfterImportPageHook;
use MediaWiki\Hook\PageMoveCompleteHook;
use MediaWiki\Page\Hook\PageDeleteCompleteHook;
use MediaWiki\Page\Hook\PageUndeleteHook;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Storage\Hook\PageSaveCompleteHook;
use StatusValue;
use Wikimedia\Rdbms\ILoadBalancer;

class TitleIndexUpdater implements PageSaveCompleteHook,
	PageMoveCompleteHook,
	PageDeleteCompleteHook,
	PageUndeleteHook,
	AfterImportPageHook
{

	/**
	 * @var ILoadBalancer
	 */
	private $lb = null;

	/**
	 * @param ILoadBalancer $lb
	 */
	public function __construct( ILoadBalancer $lb ) {
		$this->lb = $lb;
	}

	/**
	 * @inheritDoc
	 */
	public function onPageSaveComplete(
		$wikiPage, $user, $summary, $flags, $revisionRecord, $editResult
	) {
		if ( !( $flags & EDIT_NEW ) ) {
			return;
		}
		$this->insert( $wikiPage->getTitle() );
	}

	/**
	 * @inheritDoc
	 */
	public function onPageMoveComplete( $old, $new, $user, $pageid, $redirid, $reason, $revision ) {
		$this->delete( $old->getNamespace(), $old->getDBkey() );
		$this->insert( $new );
	}

	/**
	 * @inheritDoc
	 */
	public function onPageDeleteComplete(
		ProperPageIdentity $page, Authority $deleter, string $reason, int $pageID,
		RevisionRecord $deletedRev, ManualLogEntry $logEntry, int $archivedRevisionCount
	) {
		$this->delete( $page->getNamespace(), $page->getDBkey() );
	}

	/**
	 * @inheritDoc
	 */
	public function onPageUndelete(
		ProperPageIdentity $page, Authority $performer, string $reason, bool $unsuppress,
		array $timestamps, array $fileVersions, StatusValue $status
	) {
		// TODO: Implement onPageUndelete() method.
	}

	/**
	 * @inheritDoc
	 */
	public function onAfterImportPage( $title, $foreignTitle, $revCount, $sRevCount, $pageInfo ) {
		$this->insert( $title );
	}

	/**
	 * @param PageIdentity $page
	 *
	 * @return bool|void
	 */
	private function insert( PageIdentity $page ) {
		$db = $this->lb->getConnection( DB_PRIMARY );
		if ( !$page->exists() ) {
			return;
		}
		return $db->insert(
			'mws_title_index',
			[
				'mti_page_id' => $page->getId(),
				'mti_namespace' => $page->getNamespace(),
				'mti_title' => mb_strtolower( str_replace( '_', ' ', $page->getDBkey() ) ),
			],
			__METHOD__,
			[ 'IGNORE' ]
		);
	}

	/**
	 * @param int $namespace
	 * @param string $title
	 *
	 * @return bool
	 */
	private function delete( int $namespace, string $title ) {
		$db = $this->lb->getConnection( DB_PRIMARY );
		return $db->delete(
			'mws_title_index',
			[
				'mti_namespace' => $namespace,
				'mti_title' => mb_strtolower( str_replace( '_', ' ', $title->page_title ) ),
			],
			__METHOD__
		);
	}
}
