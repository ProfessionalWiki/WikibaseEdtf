<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\StringValue;
use EDTF\EdtfParser;
use ValueParsers\ParseException;
use ValueParsers\ValueParser;

class Parser implements ValueParser {

	private EdtfParser $edtfParser;

	public function __construct( EdtfParser $edtfParser ) {
		$this->edtfParser = $edtfParser;
	}

	/**
	 * @param string $value
	 * @throws ParseException
	 * @return StringValue
	 */
	public function parse( $value ): StringValue {
//		$edtf = $this->edtfParser->parse( 'foo' ); // TODO

		return new StringValue( $value );
	}

}
