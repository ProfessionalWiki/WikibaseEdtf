<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\StringValue;
use EDTF\Humanize\StringHumanizer;
use InvalidArgumentException;
use ValueFormatters\ValueFormatter;

class HumanizingFormatter implements ValueFormatter {

	private StringHumanizer $humanizer;

	public function __construct( StringHumanizer $humanizer ) {
		$this->humanizer = $humanizer;
	}

	public function format( $value ) {
		if ( !( $value instanceof StringValue ) ) {
			throw new InvalidArgumentException( 'Data value type mismatch. Expected a StringValue.' );
		}

		return $this->humanizer->humanize( $value->getValue() );
	}

}
