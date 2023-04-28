<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\FileQueryStore;

use MWStake\MediaWiki\Component\CommonWebAPIs\Data\TitleQueryStore\TitleSchema;
use MWStake\MediaWiki\Component\DataStore\FieldType;

class FileSchema extends TitleSchema {
	public function __construct() {
		parent::__construct( [
			FileRecord::FILE_EXTENSION => [
				self::FILTERABLE => true,
				self::SORTABLE => true ,
				self::TYPE => FieldType::STRING
			],
			FileRecord::MIME_MAJOR => [
				self::FILTERABLE => true,
				self::SORTABLE => true ,
				self::TYPE => FieldType::STRING
			],
			FileRecord::MIME_MINOR => [
				self::FILTERABLE => true,
				self::SORTABLE => true ,
				self::TYPE => FieldType::STRING
			],
			FileRecord::FILE_SIZE => [
				self::FILTERABLE => true,
				self::SORTABLE => true ,
				self::TYPE => FieldType::INT
			]
		] );
	}
}
