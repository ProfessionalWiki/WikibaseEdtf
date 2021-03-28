<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\StringValue;
use EDTF\EdtfFactory;
use PHPUnit\Framework\TestCase;
use Wikibase\DataAccess\EntitySourceDefinitions;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Snak\PropertyValueSnak;
use Wikibase\EDTF\Services\RdfBuilder;
use Wikibase\EDTF\Services\TimeValueBuilder;
use Wikibase\Lib\EntityTypeDefinitions;
use Wikibase\Repo\Rdf\HashDedupeBag;
use Wikibase\Repo\Rdf\JulianDateTimeValueCleaner;
use Wikibase\Repo\Rdf\RdfVocabulary;
use Wikibase\Repo\Rdf\Values\ComplexValueRdfHelper;
use Wikibase\Repo\Rdf\Values\TimeRdfBuilder;
use Wikibase\Repo\Rdf\ValueSnakRdfBuilder;
use Wikibase\Repo\Tests\Rdf\NTriplesRdfTestHelper;
use Wikimedia\Purtle\NTriplesRdfWriter;
use Wikimedia\Purtle\RdfWriter;

/**
 * @covers \Wikibase\EDTF\Services\RdfBuilder
 */
class RdfBuilderTest extends TestCase {

	private NTriplesRdfTestHelper $helper;

	protected function setUp(): void {
		parent::setUp();

		$this->helper = new NTriplesRdfTestHelper();
	}

	public function testAddValue(): void {
		$propertyId = new PropertyId( 'P7' );
		$value = new StringValue( '2021-03-28' );

		$vocabulary = $this->newVocabulary();
		$snakWriter = $this->newSnakWriter();

		$snakWriter->start();
		$snakWriter->about( 'www', 'Q1' );

		$this->newRdfBuilder( $vocabulary, $snakWriter )->addValue(
			$snakWriter,
			RdfVocabulary::NSP_CLAIM_STATEMENT,
			$vocabulary->getEntityLName( $propertyId ),
			'DUMMY',
			RdfVocabulary::NS_VALUE,
			new PropertyValueSnak( $propertyId, $value )
		);

		$this->helper->assertNTriplesEquals(
			[
				'<http://www/Q1> '
					. '<http://acme/statement/P7> '
					. '"2021-03-28T00:00:00Z"^^<http://www.w3.org/2001/XMLSchema#dateTime> .',
				'<http://www/Q1> '
					. '<http://acme/statement/value/P7> '
					. '<http://acme/value/92c2b19606046ddabd6e671d630e37b9> .',
				'<http://acme/value/92c2b19606046ddabd6e671d630e37b9> '
					. '<http://www.w3.org/1999/02/22-rdf-syntax-ns#type> '
					. '<http://acme/onto/TimeValue> .',
				'<http://acme/value/92c2b19606046ddabd6e671d630e37b9> '
					. '<http://acme/onto/timeValue> '
					. '"2021-03-28T00:00:00Z"^^<http://www.w3.org/2001/XMLSchema#dateTime> .',
				'<http://acme/value/92c2b19606046ddabd6e671d630e37b9> '
					. '<http://acme/onto/timePrecision> '
					. '"11"^^<http://www.w3.org/2001/XMLSchema#integer> .',
				'<http://acme/value/92c2b19606046ddabd6e671d630e37b9> '
					. '<http://acme/onto/timeTimezone> '
					. '"0"^^<http://www.w3.org/2001/XMLSchema#integer> .',
				'<http://acme/value/92c2b19606046ddabd6e671d630e37b9> '
					. '<http://acme/onto/timeCalendarModel> '
					. '<http://www.wikidata.org/entity/Q1985727> .',
			],
			$snakWriter->drain()
		);
	}

	private function newRdfBuilder( RdfVocabulary $vocabulary, RdfWriter $snakWriter ): ValueSnakRdfBuilder {
		return new RdfBuilder(
			new TimeRdfBuilder(
				new JulianDateTimeValueCleaner(),
				new ComplexValueRdfHelper( $vocabulary, $snakWriter->sub(), new HashDedupeBag() ) // Null if simple
			),
			new TimeValueBuilder( EdtfFactory::newParser() )
		);
	}

	private function newVocabulary(): RdfVocabulary {
		return new RdfVocabulary(
			[ '' => 'http://acme.com/item/' ],
			[ '' => 'http://acme.com/data/' ],
			new EntitySourceDefinitions( [], new EntityTypeDefinitions( [] ) ),
			'',
			[ '' => '' ],
			[ '' => '' ]
		);
	}

	private function newSnakWriter(): NTriplesRdfWriter {
		$snakWriter = new NTriplesRdfWriter();

		$snakWriter->prefix( 'www', "http://www/" );
		$snakWriter->prefix( 'acme', "http://acme/" );
		$snakWriter->prefix( RdfVocabulary::NSP_CLAIM_VALUE, "http://acme/statement/value/" );
		$snakWriter->prefix( RdfVocabulary::NSP_CLAIM_VALUE_NORM, "http://acme/statement/value-norm/" );
		$snakWriter->prefix( RdfVocabulary::NSP_CLAIM_STATEMENT, "http://acme/statement/" );
		$snakWriter->prefix( RdfVocabulary::NS_VALUE, "http://acme/value/" );
		$snakWriter->prefix( RdfVocabulary::NS_ONTOLOGY, "http://acme/onto/" );

		return $snakWriter;
	}

}
