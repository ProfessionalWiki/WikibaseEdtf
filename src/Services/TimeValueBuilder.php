<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\TimeValue;
use EDTF\EdtfParser;
use EDTF\Model\ExtDate;

class TimeValueBuilder {

	private EdtfParser $edtfParser;

	public function __construct( EdtfParser $edtfParser ) {
		$this->edtfParser = $edtfParser;
	}

	public function edtfToTimeValue( string $edtfString ): TimeValue {
		$edtf = $this->edtfParser->parse( $edtfString )->getEdtfValue();

		if ( $edtf instanceof ExtDate ) {
			return new TimeValue(
				$this->buildIsoTimeStamp( $edtf ),
				0, // TODO
				0, // Gets discarded
				0, // Gets discarded
				$this->getPrecision( $edtf ),
				TimeValue::CALENDAR_GREGORIAN // Gets discarded
			);
		}

		// TODO

		throw new \InvalidArgumentException( 'Not implemented yet' );
	}

	private function buildIsoTimeStamp( ExtDate $edtf ): string {
		return sprintf(
			'%s-%02d-%02dT00:00:00Z',
			$edtf->getYear() < 0 ? $edtf->getYear() : '+' . $edtf->getYear(),
			$edtf->getMonth() ?? 0,
			$edtf->getDay() ?? 0,
		);
	}

	private function getPrecision( ExtDate $edtf ): int {
		if ( $edtf->getDay() !== null ) {
			return TimeValue::PRECISION_DAY;
		}

		if ( $edtf->getMonth() !== null ) {
			return TimeValue::PRECISION_MONTH;
		}

		return TimeValue::PRECISION_YEAR;
	}

}
