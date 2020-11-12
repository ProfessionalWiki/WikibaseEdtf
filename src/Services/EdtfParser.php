<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\StringValue;
use EDTF\Parser;
use ValueParsers\ParseException;
use ValueParsers\ValueParser;

class EdtfParser implements ValueParser {

	private Parser $edtfParser;

	public function __construct( Parser $edtfParser ) {
		$this->edtfParser = $edtfParser;
	}

	/**
	 * @param string $value
	 * @throws ParseException
	 * @return StringValue
	 */
	public function parse( $value ): StringValue {
		return new StringValue( $value );
	}

}
