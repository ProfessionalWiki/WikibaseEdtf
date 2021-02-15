<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Services;

use DataValues\StringValue;
use EDTF\EdtfParser;
use EDTF\HumanizationResult;
use EDTF\StructuredHumanizer;
use InvalidArgumentException;
use ValueFormatters\ValueFormatter;
use Html;

class HumanizingHtmlFormatter implements ValueFormatter {

	private EdtfParser $parser;
	private StructuredHumanizer $humanizer;

	public function __construct( EdtfParser $parser, StructuredHumanizer $humanizer ) {
		$this->parser = $parser;
		$this->humanizer = $humanizer;
	}

	public function format( $edtf ) {
		if ( !( $edtf instanceof StringValue ) ) {
			throw new InvalidArgumentException( 'Data value type mismatch. Expected a StringValue.' );
		}

		return $this->warpInEdtfDiv( $this->formatString( $edtf->getValue() ) );
	}

	private function formatString( string $edtfString ): string {
		$parseResult = $this->parser->parse( $edtfString );

		if ( !$parseResult->isValid() ) {
			// TODO: could show the value is not understood
			return $this->buildPlainValueHtml( $edtfString );
		}

		$humanizationResult = $this->humanizer->humanize( $parseResult->getEdtfValue() );

		if ( !$humanizationResult->wasHumanized() ) {
			return $this->buildPlainValueHtml( $edtfString );
		}

		if ( $humanizationResult->isOneMessage() ) {
			return $this->buildPlainValueHtml( $edtfString )
				. '<br>'
				. $this->buildHumanizedHtml( $humanizationResult->getSimpleHumanization() );
		}

		return $this->buildPlainValueHtml( $edtfString )
			. '<br>'
			. $this->buildSetHtml( $humanizationResult );
	}

	private function buildPlainValueHtml( string $edtfString ): string {
		return Html::element(
			'span',
			[ 'class' => 'edtf-plain' ],
			$edtfString
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

	private function buildSetHtml( HumanizationResult $humanization ): string {
		return
			Html::element(
				'span',
				[ 'class' => 'edtf-humanized-context' ],
				$humanization->getContextMessage()
			)
			.
			Html::rawElement(
				'ul',
				[ 'class' => 'edtf-humanized' ],
				$this->buildHtmlList( $humanization )
			);
	}

	private function buildHtmlList( HumanizationResult $humanization ): string {
		$list = '';

		foreach ( $humanization->getStructuredHumanization() as $singleHumanization ) {
			$list .= Html::element(
				'li',
				[],
				$singleHumanization
			);
		}

		return $list;
	}

}
