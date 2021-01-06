<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\StringValue;
use PHPUnit\Framework\TestCase;
use Wikibase\EDTF\Services\EdtfValidator;

/**
 * @covers \Wikibase\EDTF\Services\EdtfValidator
 */
class EdtfValidatorTest extends TestCase {

	private const VALID_DATE_AND_TIME = '1985-04-12T23:20:30';

	public function testHappyPath() {
		$this->assertTrue(
			$this->newValidator()->validate( new StringValue( self::VALID_DATE_AND_TIME ) )->isValid()
		);
	}

	private function newValidator(): EdtfValidator {
		return new EdtfValidator();
	}

}
