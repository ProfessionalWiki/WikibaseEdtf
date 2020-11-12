<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF;

use ValueFormatters\FormatterOptions;
use ValueFormatters\FormattingException;
use ValueFormatters\ValueFormatter;

final class HookHandlers {

	public static function onExtensionRegistration(): void {

	}

	public static function onWikibaseRepoDataTypes( array &$dataTypeDefinitions ): void {
		$dataTypeDefinitions['PT:edtf'] = [
			'value-type' => 'string',
			'expert-module' => 'jquery.valueview.experts.edtf',
			'validator-factory-callback' => function() {
				// TODO
			},
			'formatter-factory-callback' => function( $format, FormatterOptions $options ) {
				return WikibaseEdtf::getGlobalInstance()->getEdtfFormatter();
			},
			'rdf-builder-factory-callback' => function () {
				// TODO
			},
			'rdf-data-type' => function() {
				// TODO
			},
		];
	}

	public static function onWikibaseClientDataTypes( array &$dataTypeDefinitions ): void {
		$dataTypeDefinitions['PT:edtf'] = [
			'value-type' => 'string',
			'formatter-factory-callback' => function( $format, FormatterOptions $options ) {
				// TODO
			},
		];
	}

}
