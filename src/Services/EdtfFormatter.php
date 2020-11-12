<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\StringValue;
use InvalidArgumentException;
use ValueFormatters\ValueFormatter;

class EdtfFormatter implements ValueFormatter {

	public function format( $value ) {
		if ( !( $value instanceof StringValue ) ) {
			throw new InvalidArgumentException( 'Data value type mismatch. Expected a StringValue.' );
		}

		return $value->getValue();
	}

}
