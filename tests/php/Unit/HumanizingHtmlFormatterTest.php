<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\BooleanValue;
use DataValues\StringValue;
use EDTF\Humanize\HumanizerFactory;
use PHPUnit\Framework\TestCase;
use Wikibase\EDTF\Services\HumanizingHtmlFormatter;

/**
 * @covers \Wikibase\EDTF\Services\HumanizingHtmlFormatter
 */
class HumanizingHtmlFormatterTest extends TestCase {

	public function testThrowsExceptionForNonStringValues() {
		$this->expectException( \InvalidArgumentException::class );
		$this->newFormatter()->format( new BooleanValue( false ) );
	}

	private function newFormatter(): HumanizingHtmlFormatter {
		return new HumanizingHtmlFormatter( HumanizerFactory::newStringHumanizerForLanguage( 'en' ) );
	}

	public function testHumanizes() {
		$this->assertSame(
			'<div class="edtf-value"><span class="edtf-humanized">April 1985</span><br><span class="edtf-plain">1985-04</span></div>',
			$this->newFormatter()->format( new StringValue( '1985-04' ) )
		);
	}

	public function testOutputsOnlyPlainValueWhenThereIsNoHumanizedVersion() {
		$this->assertSame(
			'<div class="edtf-value"><span class="edtf-plain">cant humanize this</span></div>',
			$this->newFormatter()->format( new StringValue( 'cant humanize this' ) )
		);
	}

}
