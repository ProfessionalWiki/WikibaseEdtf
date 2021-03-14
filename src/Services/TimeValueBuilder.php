<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\TimeValue;
use EDTF\EdtfParser;
use EDTF\EdtfValue;
use EDTF\Model\ExtDate;
use EDTF\Model\ExtDateTime;
use EDTF\Model\Interval;
use EDTF\Model\Season;
use EDTF\Model\Set;

class TimeValueBuilder {

	private EdtfParser $edtfParser;

	public function __construct( EdtfParser $edtfParser ) {
		$this->edtfParser = $edtfParser;
	}

	/**
	 * @return TimeValue[]
	 */
	public function edtfToTimeValues( string $edtfString ): array {
		$edtf = $this->edtfParser->parse( $edtfString )->getEdtfValue();

		if ( $edtf instanceof Set ) {
			return $this->setToTimeValues( $edtf );
		}

		if ( $edtf instanceof Interval ) {
			return $this->intervalToTimeValues( $edtf );
		}

		return [
			$this->singleValueEdtfToTimeValue( $edtf )
		];
	}

	private function singleValueEdtfToTimeValue( EdtfValue $edtf ): TimeValue {
		if ( $edtf instanceof ExtDate ) {
			return $this->newTimeValue(
				$this->buildDateIsoTimeStamp( $edtf ),
				0,
				$this->getDatePrecision( $edtf )
			);
		}

		if ( $edtf instanceof ExtDateTime ) {
			return $this->newTimeValue(
				$this->buildTimeIsoTimeStamp( $edtf ),
				$edtf->getTimezoneOffset() ?? 0,
				$this->getTimePrecision( $edtf )
			);
		}

		if ( $edtf instanceof Season ) {
			return $this->singleValueEdtfToTimeValue( new ExtDate(
				$edtf->getYear(),
				1 // TODO
			) );
		}

		throw new \InvalidArgumentException();
	}

	private function newTimeValue( string $isoLikeTimestamp, int $timezone, int $precision ): TimeValue {
		return new TimeValue(
			$isoLikeTimestamp,
			$timezone,
			0, // Gets discarded
			0, // Gets discarded
			$precision,
			TimeValue::CALENDAR_GREGORIAN // Gets discarded
		);
	}

	private function buildDateIsoTimeStamp( ExtDate $edtf ): string {
		return $this->buildDateString( $edtf ) . 'T00:00:00Z';
	}

	private function buildDateString( ExtDate $edtf ): string {
		return sprintf(
			'%s-%02d-%02d',
			$edtf->getYear() < 0 ? $edtf->getYear() : '+' . $edtf->getYear(),
			$edtf->getMonth() ?? 0,
			$edtf->getDay() ?? 0,
		);
	}

	private function getDatePrecision( ExtDate $edtf ): int {
		if ( $edtf->getDay() !== null ) {
			return TimeValue::PRECISION_DAY;
		}

		if ( $edtf->getMonth() !== null ) {
			return TimeValue::PRECISION_MONTH;
		}

		return TimeValue::PRECISION_YEAR;
	}

	private function getTimePrecision( ExtDateTime $edtf ): int {
		return TimeValue::PRECISION_MINUTE;
	}

	private function buildTimeIsoTimeStamp( ExtDateTime $edtf ): string {
		return $this->buildDateString( $edtf->getDate() ) . $this->buildTimeString( $edtf );
	}

	private function buildTimeString( ExtDateTime $edtf ): string {
		return sprintf(
			'T%02d:%02d:%02dZ',
			$edtf->getHour(),
			$edtf->getMinute(),
			$edtf->getSecond(),
		);
	}

	/**
	 * @return TimeValue[]
	 */
	private function setToTimeValues( Set $set ): array {
		return array_map(
			fn( EdtfValue $edtfValue ) => $this->singleValueEdtfToTimeValue( $edtfValue ),
			$set->getDates()
		);
	}

	/**
	 * @return TimeValue[]
	 */
	private function intervalToTimeValues( Interval $interval ): array {
		$timeValues = [];

		if ( $interval->hasStartDate() ) {
			$timeValues[] = $this->singleValueEdtfToTimeValue( $interval->getStartDate() );
		}

		if ( $interval->hasEndDate() ) {
			$timeValues[] = $this->singleValueEdtfToTimeValue( $interval->getEndDate() );
		}

		return $timeValues;
	}

}
