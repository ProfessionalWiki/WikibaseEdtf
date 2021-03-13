<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\TimeValue;
use EDTF\EdtfFactory;
use PHPUnit\Framework\TestCase;
use Wikibase\EDTF\Services\TimeValueBuilder;

/**
 * @covers \Wikibase\EDTF\Services\TimeValueBuilder
 */
class TimeValueBuilderTest extends TestCase {

	public function testFoo(): void {
		$this->assertEquals(
			$this->newTimeValue( '+2021-00-00T00:00:00Z', 0, TimeValue::PRECISION_YEAR ),
			$this->edtfToTimeValue( '2021' )
		);
	}

	private function edtfToTimeValue( string $edtf ): TimeValue {
		return ( new TimeValueBuilder( EdtfFactory::newParser() ) )->edtfToTimeValue( $edtf );
	}

	private function newTimeValue( string $isoLikeTime, int $timezone, int $precision ): TimeValue {
		return new TimeValue(
			$isoLikeTime,
			$timezone,
			0, // Gets discarded
			0, // Gets discarded
			$precision,
			TimeValue::CALENDAR_GREGORIAN // Gets discarded
		);
	}

}
