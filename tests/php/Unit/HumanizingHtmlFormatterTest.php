<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\BooleanValue;
use DataValues\StringValue;
use EDTF\EdtfFactory;
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
		return new HumanizingHtmlFormatter(
			EdtfFactory::newParser(),
			EdtfFactory::newStructuredHumanizerForLanguage( 'en' )
		);
	}

	public function testHumanizes() {
		$this->assertSame(
			'<div class="edtf-value"><span class="edtf-plain">1985-04</span><br><span class="edtf-humanized">(April 1985)</span></div>',
			$this->newFormatter()->format( new StringValue( '1985-04' ) )
		);
	}

	public function testOutputsOnlyPlainValueWhenThereIsNoHumanizedVersion() {
		$this->assertSame(
			'<div class="edtf-value"><span class="edtf-plain">cant humanize this</span></div>',
			$this->newFormatter()->format( new StringValue( 'cant humanize this' ) )
		);
	}

	public function testHumanizesSetWithSingleMessage() {
		$this->assertSame(
			'<div class="edtf-value"><span class="edtf-plain">[2020, 2021]</span><br><span class="edtf-humanized">(2020 or 2021)</span></div>',
			$this->newFormatter()->format( new StringValue( '[2020, 2021]' ) )
		);
	}

	public function testHumanizesSetWithList() {
		$this->assertSame(
			'<div class="edtf-value"><span class="edtf-plain">[2021-02-14, 2021-02-15]</span>'
				. '<br><span class="edtf-humanized-context">One of these:</span><ul class="edtf-humanized"><li>February 14th, 2021</li><li>February 15th, 2021</li></ul></div>',
			$this->newFormatter()->format( new StringValue( '[2021-02-14, 2021-02-15]' ) )
		);
	}

	public function testDoesNotShowHumanizationWhenIdenticalToPlainValue() {
		$this->assertSame(
			'<div class="edtf-value"><span class="edtf-plain">1985</span></div>',
			$this->newFormatter()->format( new StringValue( '1985' ) )
		);
	}

	public function testYearZeroInSet() {
		$this->assertSame(
			'<div class="edtf-value"><span class="edtf-plain">[-1, 0, 1]</span><br><span class="edtf-humanized">(One of these: Year 1 BC, Year 0, Year 1)</span></div>',
			$this->newFormatter()->format( new StringValue( '[-1, 0, 1]' ) )
		);
	}

}
