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

	public function testYear(): void {
		$this->assertEquals(
			$this->newTimeValue( '+2021-00-00T00:00:00Z', 0, TimeValue::PRECISION_YEAR ),
			$this->edtfToTimeValue( '2021' )
		);
	}

	private function edtfToTimeValue( string $edtf ): TimeValue {
		return $this->edtfToMultipleTimeValues( $edtf )[0];
	}

	private function edtfToMultipleTimeValues( string $edtf ): array {
		return ( new TimeValueBuilder( EdtfFactory::newParser() ) )->edtfToTimeValues( $edtf );
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

	public function testMonth(): void {
		$this->assertEquals(
			$this->newTimeValue( '+2021-03-00T00:00:00Z', 0, TimeValue::PRECISION_MONTH ),
			$this->edtfToTimeValue( '2021-03' )
		);
	}

	public function testDay(): void {
		$this->assertEquals(
			$this->newTimeValue( '+2021-03-13T00:00:00Z', 0, TimeValue::PRECISION_DAY ),
			$this->edtfToTimeValue( '2021-03-13' )
		);
	}

	public function testNegativeYear(): void {
		$this->assertEquals(
			$this->newTimeValue( '-2021-00-00T00:00:00Z', 0, TimeValue::PRECISION_YEAR ),
			$this->edtfToTimeValue( '-2021' )
		);
	}

	public function testShortYear(): void {
		$this->assertEquals(
			$this->newTimeValue( '+0012-00-00T00:00:00Z', 0, TimeValue::PRECISION_YEAR ),
			$this->edtfToTimeValue( '12' )
		);
	}

	public function testHoursMinutesAndSeconds(): void {
		$this->assertEquals(
			$this->newTimeValue( '+2021-03-14T00:01:42Z', 0, TimeValue::PRECISION_MINUTE ),
			$this->edtfToTimeValue( '2021-03-14T00:01:42' )
		);
	}

	public function testTimeZone(): void {
		$this->assertEquals(
			$this->newTimeValue( '+2021-03-14T00:01:42Z', 60, TimeValue::PRECISION_MINUTE ),
			$this->edtfToTimeValue( '2021-03-14T00:01:42+01:00' )
		);
	}

	public function testNegativeTimeZone(): void {
		$this->assertEquals(
			$this->newTimeValue( '+2021-03-14T00:01:42Z', -150, TimeValue::PRECISION_MINUTE ),
			$this->edtfToTimeValue( '2021-03-14T00:01:42-02:30' )
		);
	}

	public function testSet(): void {
		$this->assertEquals(
			[
				$this->newTimeValue( '+2021-00-00T00:00:00Z', 0, TimeValue::PRECISION_YEAR ),
				$this->newTimeValue( '+2021-03-13T00:00:00Z', 0, TimeValue::PRECISION_DAY ),
				$this->newTimeValue( '+2021-03-14T00:01:42Z', 60, TimeValue::PRECISION_MINUTE )
			],
			$this->edtfToMultipleTimeValues( '[2021, 2021-03-13, 2021-03-14T00:01:42+01:00]' )
		);
	}

	public function testSeason(): void {
		$this->assertEquals(
			$this->newTimeValue( '+2021-01-00T00:00:00Z', 0, TimeValue::PRECISION_MONTH ), // TODO
			$this->edtfToTimeValue( '2021-21' )
		);
	}

	public function testInterval(): void {
		$this->assertEquals(
			[
				$this->newTimeValue( '+2020-11-00T00:00:00Z', 0, TimeValue::PRECISION_MONTH ),
				$this->newTimeValue( '+2021-03-00T00:00:00Z', 0, TimeValue::PRECISION_MONTH )
			],
			$this->edtfToMultipleTimeValues( '2020-11/2021-03' )
		);
	}

	public function testIntervalWithOpenStart(): void {
		$this->assertEquals(
			[
				$this->newTimeValue( '+2021-03-00T00:00:00Z', 0, TimeValue::PRECISION_MONTH )
			],
			$this->edtfToMultipleTimeValues( '../2021-03' )
		);
	}

	public function testIntervalWithOpenEnd(): void {
		$this->assertEquals(
			[
				$this->newTimeValue( '+2020-11-00T00:00:00Z', 0, TimeValue::PRECISION_MONTH ),
			],
			$this->edtfToMultipleTimeValues( '2020-11/..' )
		);
	}

	public function testUncertainDate(): void {
		$this->assertEquals(
			$this->newTimeValue( '+1984-00-00T00:00:00Z', 0, TimeValue::PRECISION_YEAR ),
			$this->edtfToTimeValue( '1984?' )
		);
	}

	public function testUnspecifiedDays(): void {
		$this->assertEquals(
			$this->newTimeValue( '+1985-04-00T00:00:00Z', 0, TimeValue::PRECISION_MONTH ),
			$this->edtfToTimeValue( '1985-04-XX' )
		);
	}

	public function testUnspecifiedMonths(): void {
		$this->assertEquals(
			$this->newTimeValue( '+1985-01-03T00:00:00Z', 0, TimeValue::PRECISION_DAY ),
			$this->edtfToTimeValue( '1985-XX-03' )
		);
	}

	public function testOnlyMonthSpecified(): void {
		$this->assertSame(
			[], // This cannot be represented with a TimeValue
			$this->edtfToMultipleTimeValues( 'XXXX-12-XX' )
		);
	}

	public function testInvalidEdtf(): void {
		$this->assertSame(
			[],
			$this->edtfToMultipleTimeValues( '~=[,,_,,]:3' )
		);
	}

}
