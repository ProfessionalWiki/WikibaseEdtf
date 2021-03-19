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
use Wikibase\EDTF\Services\RdfBuilder;
use Wikibase\EDTF\Services\TimeValueBuilder;
use Wikibase\EDTF\Services\Validator;
use Wikibase\Lib\Formatters\SnakFormat;
use Wikibase\Lib\Formatters\SnakFormatter;
use Wikibase\Repo\Rdf\DedupeBag;
use Wikibase\Repo\Rdf\JulianDateTimeValueCleaner;
use Wikibase\Repo\Rdf\RdfProducer;
use Wikibase\Repo\Rdf\RdfVocabulary;
use Wikibase\Repo\Rdf\Values\ComplexValueRdfHelper;
use Wikibase\Repo\Rdf\Values\TimeRdfBuilder;
use Wikibase\Repo\Rdf\ValueSnakRdfBuilder;
use Wikimedia\Purtle\RdfWriter;

class WikibaseEdtf {

	protected static ?self $instance;

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
					EdtfFactory::newStructuredHumanizerForLanguage( $options->getOption( 'lang' ), 'en' )
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

	public function getRdfBuilder( int $flags, RdfVocabulary $vocab, RdfWriter $writer, DedupeBag $dedupe ): ValueSnakRdfBuilder {
		return new RdfBuilder(
			new TimeRdfBuilder(
				new JulianDateTimeValueCleaner(),
				( $flags & RdfProducer::PRODUCE_FULL_VALUES ) ? new ComplexValueRdfHelper( $vocab, $writer->sub(), $dedupe ) : null
			),
			new TimeValueBuilder(
				EdtfFactory::newParser()
			)
		);
	}

}
