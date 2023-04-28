<?php

namespace MWStake\MediaWiki\Component\CommonWebAPIs\Data\FileQueryStore;

use MWStake\MediaWiki\Component\CommonWebAPIs\Data\TitleQueryStore\TitleRecord;

class FileRecord extends TitleRecord {
	public const FILE_EXTENSION = 'file_extension';
	public const MIME_MAJOR = 'mime_major';
	public const MIME_MINOR = 'mime_minor';
	public const FILE_SIZE = 'file_size';
}
