<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF;

use EDTF\EdtfParser;
use EDTF\EdtfValidator;
use EDTF\Humanize\HumanizerFactory;
use ValueFormatters\FormatterOptions;
use ValueFormatters\ValueFormatter;
use ValueValidators\ValueValidator;
use Wikibase\EDTF\Services\HumanizingFormatter;
use Wikibase\EDTF\Services\Parser;
use Wikibase\EDTF\Services\PlainFormatter;
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

	public function getFormatter( string $format, FormatterOptions $options ): ValueFormatter {
		if ( $format === 'text/plain' ) {
			return new PlainFormatter();
		}

		return new HumanizingFormatter(
			HumanizerFactory::newStringHumanizerForLanguage( $options->getOption( 'lang' ) )
		);
	}

	public function getParser(): Parser {
		return new Parser( new EdtfParser() );
	}

	public function getValidator(): ValueValidator {
		return new Validator( EdtfValidator::newInstance() );
	}

}
