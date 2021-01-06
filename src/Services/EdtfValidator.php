<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\StringValue;
use ValueValidators\Error;
use ValueValidators\Result;
use ValueValidators\ValueValidator;

class EdtfValidator implements ValueValidator {

	public function validate( $value ): Result {
		if ( $value instanceof StringValue ) {
			return $this->validateString( $value->getValue() );
		}

		return $this->newErrorResult( 'EDTF values need to be of type StringValue' );
	}

	private function validateString( string $edtf ): Result {
		if ( $edtf === 'bar' ) {
			return $this->newErrorResult( 'EDTF cannot be bar' );
		}

		return Result::newSuccess();
	}

	private function newErrorResult( string $message ): Result {
		return Result::newError( [
			Error::newError( $message )
		] );
	}

	public function setOptions( array $options ) {
	}

}
