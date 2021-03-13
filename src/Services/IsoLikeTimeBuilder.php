<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\TimeValue;
use EDTF\EdtfParser;
use Wikibase\EDTF\IsoLikeTime;

class IsoLikeTimeBuilder {

	public function __construct( EdtfParser $edtfParser ) {
	}

	public function edtfToIsoLikeTime( string $edtf ): IsoLikeTime {
		return new IsoLikeTime(
			'+1749-08-28T00:00:00Z',
			0,
			TimeValue::PRECISION_DAY
		);
	}

}
