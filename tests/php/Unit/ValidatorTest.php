<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\StringValue;
use EDTF\EdtfValidator;
use EDTF\ExampleData\ValidEdtfStrings;
use PHPUnit\Framework\TestCase;
use Wikibase\EDTF\Services\Validator;

/**
 * @covers \Wikibase\EDTF\Services\Validator
 */
class ValidatorTest extends TestCase {

	/**
	 * @dataProvider validValueProvider
	 */
	public function testValidEdtf( string $validEdtf ) {
		$this->assertTrue(
			$this->newValidator()->validate( new StringValue( $validEdtf ) )->isValid()
		);
	}

	public function validValueProvider(): \Generator {
		foreach ( ValidEdtfStrings::all() as $key => $value ) {
			yield $key => [ $value ];
		}
	}

	private function newValidator(): Validator {
		return new Validator( EdtfValidator::newInstance() );
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
		yield 'stuff before valid date' => [ 'wtf1985' ];
		yield 'stuff inside valid date' => [ '19wtf85' ];

		yield 'day too high' => [ '2021-01-32' ];
		yield 'month too high' => [ '2021-13-01' ];

		foreach ( ValidEdtfStrings::all() as $key => $value ) {
			yield [ 'invalid ' . $value ];
			yield [ $value. 'invalid' ];
		}
	}

}
