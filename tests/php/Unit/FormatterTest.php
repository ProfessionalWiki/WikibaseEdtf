<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\BooleanValue;
use DataValues\StringValue;
use EDTF\Humanize\HumanizerFactory;
use PHPUnit\Framework\TestCase;
use Wikibase\EDTF\Services\HumanizingFormatter;

/**
 * @covers \Wikibase\EDTF\Services\HumanizingFormatter
 */
class FormatterTest extends TestCase {

	public function testThrowsExceptionForNonStringValues() {
		$this->expectException( \InvalidArgumentException::class );
		$this->newFormatter()->format( new BooleanValue( false ) );
	}

	private function newFormatter(): HumanizingFormatter {
		return new HumanizingFormatter( HumanizerFactory::newStringHumanizerForLanguage( 'en' ) );
	}

	public function testFormatWithFormattedEdtf() {
		$this->assertSame(
			'April 1985',
			$this->newFormatter()->format( new StringValue( '1985-4' ) )
		);
	}

}
