<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF;

use EDTF\EdtfFactory;
use ValueFormatters\FormatterOptions;
use ValueFormatters\ValueFormatter;
use ValueValidators\ValueValidator;
use Wikibase\EDTF\Services\HumanizingHtmlFormatter;
use Wikibase\EDTF\Services\Parser;
use Wikibase\EDTF\Services\PlainFormatter;
use Wikibase\EDTF\Services\Validator;
use Wikibase\Lib\Formatters\SnakFormat;
use Wikibase\Lib\Formatters\SnakFormatter;

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
		switch ( ( new SnakFormat() )->getBaseFormat( $format ) ) {
			case SnakFormatter::FORMAT_HTML:
				return new HumanizingHtmlFormatter(
					EdtfFactory::newParser(),
					EdtfFactory::newStructuredHumanizerForLanguage( $options->getOption( 'lang' ) )
				);
			case SnakFormatter::FORMAT_WIKI:
			default:
				return new PlainFormatter();
		}
	}

	public function getParser(): Parser {
		return new Parser( EdtfFactory::newParser() );
	}

	public function getValidator(): ValueValidator {
		return new Validator( EdtfFactory::newValidator() );
	}

}
