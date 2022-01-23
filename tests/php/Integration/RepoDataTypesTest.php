<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\StringValue;
use PHPUnit\Framework\TestCase;
use ValueFormatters\FormatterOptions;
use ValueParsers\ParserOptions;
use Wikibase\EDTF\HookHandlers;
use Wikibase\EDTF\Services\Validator;
use Wikibase\Lib\DataTypeDefinitions;
use Wikibase\Lib\Formatters\SnakFormatter;
use Wikibase\Repo\WikibaseRepo;

/**
 * @covers \Wikibase\EDTF\HookHandlers
 */
class RepoDataTypesTest extends TestCase {

	private const VALID_DATE_AND_TIME = '1985-04-12T23:20:30';

	public function testCanConstructDataTypeDefinitions() {
		$definitions = new DataTypeDefinitions( $this->newTypeArray() );

		$this->assertSame( [ 'edtf' ], $definitions->getTypeIds() );
	}

	private function newTypeArray(): array {
		$types = [];
		HookHandlers::onWikibaseRepoDataTypes( $types );
		return $types;
	}

	public function testTypeIsRegistered() {
		$this->assertContains(
			'edtf',
			$this->getRepoTypeDefinitions()->getTypeIds()
		);
	}

	private function getRepoTypeDefinitions(): DataTypeDefinitions {
		return WikibaseRepo::getDataTypeDefinitions();
	}

	public function testValidatorIsRegisteredInTypeDefinitions() {
		$validatorFactories = $this->getRepoTypeDefinitions()->getValidatorFactoryCallbacks();

		$this->assertArrayHasKey( 'edtf', $validatorFactories );
		$this->assertInstanceOf( Validator::class, $validatorFactories['edtf']()[0] );
	}

	public function testParsing() {
		$this->assertEquals(
			new StringValue( self::VALID_DATE_AND_TIME ),
			WikibaseRepo::getValueParserFactory()->newParser(
				'edtf',
				new ParserOptions()
			)->parse( self::VALID_DATE_AND_TIME )
		);
	}

	public function testFormatting() {
		$this->assertEquals(
			self::VALID_DATE_AND_TIME,
			WikibaseRepo::getValueFormatterFactory()->getValueFormatter(
				SnakFormatter::FORMAT_PLAIN,
				new FormatterOptions()
			)->formatValue( new StringValue( self::VALID_DATE_AND_TIME ), 'edtf' )
		);
	}

	public function testValidatorIsRegisteredInValidatorFactory() {
		$this->assertInstanceOf(
			Validator::class,
			WikibaseRepo::getDataTypeValidatorFactory()->getValidators( 'edtf' )[0]
		);
	}

}
