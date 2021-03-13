<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF;

/**
 * Subset of @see \DataValues\TimeValue
 */
class IsoLikeTime {

	private string $wikibaseIsoTime;
	private int $timezone;
	private int $precision;

	public function __construct( string $wikibaseIsoTime, int $timezone, int $precision ) {
		$this->wikibaseIsoTime = $wikibaseIsoTime;
		$this->timezone = $timezone;
		$this->precision = $precision;
	}

	public function getWikibaseIsoTime(): string {
		return $this->wikibaseIsoTime;
	}

	public function getTimezone(): int {
		return $this->timezone;
	}

	public function getPrecision(): int {
		return $this->precision;
	}

}
