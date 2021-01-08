<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF;

use EDTF\EdtfParser;
use EDTF\EdtfValidator;
use ValueValidators\ValueValidator;
use Wikibase\EDTF\Services\Formatter;
use Wikibase\EDTF\Services\Parser;
use Wikibase\EDTF\Services\Validator;

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

	public function getFormatter(): Formatter {
		return new Formatter();
	}

	public function getParser(): Parser {
		return new Parser( new EdtfParser() );
	}

	public function getValidator(): ValueValidator {
		return new Validator( EdtfValidator::newInstance() );
	}

}
