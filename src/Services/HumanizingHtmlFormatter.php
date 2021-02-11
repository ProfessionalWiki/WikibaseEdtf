<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\StringValue;
use EDTF\Humanize\StringHumanizer;
use InvalidArgumentException;
use ValueFormatters\ValueFormatter;
use Html;

class HumanizingHtmlFormatter implements ValueFormatter {

	private StringHumanizer $humanizer;

	public function __construct( StringHumanizer $humanizer ) {
		$this->humanizer = $humanizer;
	}

	public function format( $edtf ) {
		if ( !( $edtf instanceof StringValue ) ) {
			throw new InvalidArgumentException( 'Data value type mismatch. Expected a StringValue.' );
		}

		$humanized = $this->humanizer->humanize( $edtf->getValue() );

		if ( $humanized === $edtf->getValue() ) {
			return $this->warpInEdtfDiv( $this->buildPlainValueHtml( $edtf ) );
		}

		return $this->warpInEdtfDiv(
			$this->buildPlainValueHtml( $edtf ) . '<br>' . $this->buildHumanizedHtml( $humanized )
		);
	}

	private function buildPlainValueHtml( StringValue $edtf ): string {
		return Html::element(
			'span',
			[ 'class' => 'edtf-plain' ],
			$edtf->getValue()
		);
	}

	private function buildHumanizedHtml( string $humanizedEdtf ): string {
		return Html::element(
			'span',
			[ 'class' => 'edtf-humanized' ],
			"($humanizedEdtf)"
		);
	}

	private function warpInEdtfDiv( string $Html ): string {
		return Html::rawElement(
			'div',
			[ 'class' => 'edtf-value' ],
			$Html
		);
	}

}
