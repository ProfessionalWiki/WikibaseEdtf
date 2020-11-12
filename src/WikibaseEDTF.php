<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF;

class WikibaseEDTF {

	protected static /* ?self */ $instance;

	public static function getGlobalInstance(): self {
		if ( !isset( self::$instance ) ) {
			self::$instance = self::newDefault();
		}

		return self::$instance;
	}

	protected static function newDefault(): self {
		return new static();
	}

	protected final function __construct() {
	}

}
