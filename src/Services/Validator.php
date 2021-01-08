<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\StringValue;
use EDTF\EdtfValidator;
use ValueValidators\Error;
use ValueValidators\Result;
use ValueValidators\ValueValidator;

class Validator implements ValueValidator {

	private EdtfValidator $edtfValidator;

	public function __construct( EdtfValidator $edtfValidator ) {
		$this->edtfValidator = $edtfValidator;
	}

	public function validate( $value ): Result {
		if ( $value instanceof StringValue ) {
			return $this->validateString( $value->getValue() );
		}

		return $this->newErrorResult( 'EDTF values need to be of type StringValue' );
	}

	private function validateString( string $edtf ): Result {
		if ( $this->edtfValidator->isValidEdtf( $edtf ) ) {
			return Result::newSuccess();
		}

		return $this->newErrorResult( 'Invalid EDTF' );
	}

	private function newErrorResult( string $message ): Result {
		return Result::newError( [
			Error::newError( $message )
		] );
	}

	public function setOptions( array $options ) {
	}

}
