<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\TimeValue;
use EDTF\EdtfParser;
use EDTF\EdtfValue;
use EDTF\Model\ExtDate;
use EDTF\Model\ExtDateTime;
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
			return array_map(
				fn( EdtfValue $edtfValue ) => $this->singleValueEdtfToTimeValue( $edtfValue ),
				$edtf->getDates()
			);
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

}
