<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF;

use EDTF\EdtfParser;
use EDTF\EdtfValidator;
use EDTF\Humanize\HumanizerFactory;
use ValueFormatters\FormatterOptions;
use ValueFormatters\ValueFormatter;
use ValueValidators\ValueValidator;
use Wikibase\EDTF\Services\HumanizingHtmlFormatter;
use Wikibase\EDTF\Services\Parser;
use Wikibase\EDTF\Services\PlainFormatter;
use Wikibase\EDTF\Services\Validator;
use Wikibase\Lib\Formatters\SnakFormat;
use Wikibase\Lib\Formatters\SnakFormatter;
use Wikibase\Lib\Formatters\WikiLinkHtmlFormatter;
use Wikibase\Lib\Formatters\WikiLinkWikitextFormatter;

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
					HumanizerFactory::newStringHumanizerForLanguage( $options->getOption( 'lang' ) )
				);
			case SnakFormatter::FORMAT_WIKI:
			default:
				return new PlainFormatter();
		}
	}

	public function getParser(): Parser {
		return new Parser( new EdtfParser() );
	}

	public function getValidator(): ValueValidator {
		return new Validator( EdtfValidator::newInstance() );
	}

}
