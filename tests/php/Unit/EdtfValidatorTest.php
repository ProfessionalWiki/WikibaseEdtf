<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\StringValue;
use PHPUnit\Framework\TestCase;
use Wikibase\EDTF\Services\EdtfValidator;
use Wikibase\EDTF\Tests\ValidEdtfStrings;

/**
 * @covers \Wikibase\EDTF\Services\EdtfValidator
 */
class EdtfValidatorTest extends TestCase {

	/**
	 * @dataProvider validValueProvider
	 */
	public function testValidEdtf( string $validEdtf ) {
		$this->assertTrue(
			$this->newValidator()->validate( new StringValue( $validEdtf ) )->isValid()
		);
	}

	public function validValueProvider(): \Generator {
		foreach ( ValidEdtfStrings::allFromStandard() as $key => $value ) {
			yield $key => [ $value ];
		}
	}

	private function newValidator(): EdtfValidator {
		return new EdtfValidator();
	}

	/**
	 * @dataProvider invalidValueProvider
	 */
	public function testInvalidEdtf( string $invalidEdtf ) {
		$this->assertFalse(
			$this->newValidator()->validate( new StringValue( $invalidEdtf ) )->isValid()
		);
	}

	public function invalidValueProvider(): \Generator {
		yield 'empty string' => [ '' ];
		yield 'random stuff' => [ '~=[,,_,,]:3' ];

		yield 'stuff after valid date' => [ '1985wtf' ];
//	TODO	yield 'stuff before valid date' => [ 'wtf1985' ];
//	TODO	yield 'stuff inside valid date' => [ '19wtf85' ];


		foreach ( ValidEdtfStrings::allFromStandard() as $key => $value ) {
			// TODO
			// yield [ 'invalid ' . $value ];
		}
	}

}
