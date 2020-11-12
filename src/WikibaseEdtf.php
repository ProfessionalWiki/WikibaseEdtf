<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF;

use Wikibase\EDTF\Services\EdtfFormatter;
use Wikibase\EDTF\Services\EdtfParser;

class WikibaseEdtf {

	protected static /* ?self */ $instance;

	public static function getGlobalInstance(): self {
		if ( !isset( self::$instance ) ) {
			self::$instance = self::newDefault();
		}

		return self::$instance;
	}

	protected static function newDefault(): self {
		return new static();
	}

	protected final function __construct() {
	}

	public function getEdtfFormatter(): EdtfFormatter {
		return new EdtfFormatter();
	}

	public function getEdtfParser(): EdtfParser {
		return new EdtfParser();
	}

}
