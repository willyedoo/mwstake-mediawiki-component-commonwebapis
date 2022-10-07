<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Rest;

use Elastica\Param;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\ResponseFactory;
use MediaWiki\Rest\SimpleHandler;
use MWStake\MediaWiki\Component\DataStore\IStore;
use MWStake\MediaWiki\Component\DataStore\ReaderParams;
use MWStake\MediaWiki\Component\DataStore\ResultSet;
use Wikimedia\ParamValidator\ParamValidator;

abstract class QueryStore extends Handler {
	public function needsReadAccess() {
		return true;
	}

	public function needsWriteAccess() {
		return false;
	}

	public function execute() {
		$store = $this->getStore();
		$readerParams = $this->getReaderParams();
		return $this->returnResult( $this->getResult( $store, $readerParams ) );
	}

	abstract protected function getStore(): IStore;

	protected function getStoreSpecificParams(): array {
		return [];
	}

	protected function getReaderParams(): ReaderParams {
		return new ReaderParams( [
			'start' => $this->getOffset(),
			'limit' => $this->getLimit(),
			'filter' => $this->getFilter(),
			'sort' => $this->getSort()
		] );
	}

	protected function getResult( IStore $store, ReaderParams $readerParams ): ResultSet {
		return $store->getReader()->read( $readerParams );
	}

	protected function returnResult( ResultSet $result ): Response {
		$contentType = $contentType ?? 'application/json';
		$response = new Response( $this->encodeJson( [
			'results' => $result->getRecords(),
			'total' => $result->getTotal(),
		] ) );
		$response->setHeader( 'Content-Type', $contentType );

		return $response;
	}

	private function encodeJson( $data ) {
		return json_encode( $data, $this->getFormat() === 'jsonfm' ? JSON_PRETTY_PRINT : 0 );
	}

	public function getParamSettings() {
		return array_merge( [
			'sort' => [
				static::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_TYPE => 'string',
			],
			'filter' => [
				static::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_TYPE => 'string',
			],
			'limit' => [
				static::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_DEFAULT => 25
			],
			'start' => [
				static::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_DEFAULT => 0
			],
			'format' => [
				static::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_TYPE => [ 'json', 'jsonfm' ],
				ParamValidator::PARAM_DEFAULT => 'json'
			]
		], $this->getStoreSpecificParams() );
	}

	/**
	 * @return int
	 */
	private function getOffset(): int {
		return (int)$this->getValidatedParams()['start'];
	}

	/**
	 * @return int
	 */
	private function getLimit(): int {
		return (int)$this->getValidatedParams()['limit'];
	}

	/**
	 * @return array
	 */
	private function getFilter(): array {
		$validated = $this->getValidatedParams();
		if ( is_array( $validated ) && isset( $validated['filter'] ) ) {
			return json_decode( $validated['filter'], 1 );
		}
		return [];
	}

	/**
	 * @return array
	 */
	private function getSort(): array {
		$validated = $this->getValidatedParams();
		if ( is_array( $validated ) && isset( $validated['sort'] ) ) {
			return json_decode( $validated['sort'], 1 );
		}
		return [];
	}

	private function getFormat(): string {
		return $this->getValidatedParams()['format'];
	}
}
