<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\BooleanValue;
use DataValues\StringValue;
use PHPUnit\Framework\TestCase;
use Wikibase\EDTF\Services\EdtfFormatter;

/**
 * @covers \Wikibase\EDTF\Services\EdtfFormatter
 */
class EdtfFormatterTest extends TestCase {

	private const VALID_DATE_AND_TIME = '1985-04-12T23:20:30';

	public function testThrowsExceptionForNonStringValues() {
		$this->expectException( \InvalidArgumentException::class );
		$this->newFormatter()->format( new BooleanValue( false ) );
	}

	private function newFormatter(): EdtfFormatter {
		return new EdtfFormatter();
	}

	public function testFormatWithFormattedEdtf() {
		$this->assertSame(
			self::VALID_DATE_AND_TIME,
			$this->newFormatter()->format( new StringValue( self::VALID_DATE_AND_TIME ) )
		);
	}

}
