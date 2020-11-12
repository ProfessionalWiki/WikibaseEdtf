<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\BooleanValue;
use PHPUnit\Framework\TestCase;
use Wikibase\EDTF\Services\EdtfFormatter;

/**
 * @covers \Wikibase\EDTF\Services\EdtfFormatter
 */
class EdtfFormatterTest extends TestCase {

	public function testThrowsExceptionForNonStringValues() {
		$this->expectException( \InvalidArgumentException::class );
		$this->newFormatter()->format( new BooleanValue( false ) );
	}

	private function newFormatter(): EdtfFormatter {
		return new EdtfFormatter();
	}

}
