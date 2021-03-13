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
				"+{$edtf->getYear()}-00-00T00:00:00Z", // TODO
				0, // TODO
				0, // Gets discarded
				0, // Gets discarded
				TimeValue::PRECISION_YEAR, // TODO
				TimeValue::CALENDAR_GREGORIAN // Gets discarded
			);
		}

		// TODO

		throw new \InvalidArgumentException( 'Not implemented yet' );
	}

}
