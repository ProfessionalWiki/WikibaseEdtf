<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use ValueValidators\Result;
use ValueValidators\ValueValidator;

class EdtfValidator implements ValueValidator {

	public function validate( $value ): Result {
		return Result::newSuccess();
	}

	public function setOptions( array $options ) {
	}

}
