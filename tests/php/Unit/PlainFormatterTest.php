<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\BooleanValue;
use DataValues\StringValue;
use PHPUnit\Framework\TestCase;
use Wikibase\EDTF\Services\PlainFormatter;

/**
 * @covers \Wikibase\EDTF\Services\PlainFormatter
 */
class PlainFormatterTest extends TestCase {

	public function testThrowsExceptionForNonStringValues() {
		$this->expectException( \InvalidArgumentException::class );
		$this->newFormatter()->format( new BooleanValue( false ) );
	}

	private function newFormatter(): PlainFormatter {
		return new PlainFormatter();
	}

	public function testFormatReturnsEdtf() {
		$this->assertSame(
			'1985-4',
			$this->newFormatter()->format( new StringValue( '1985-4' ) )
		);
	}

}
