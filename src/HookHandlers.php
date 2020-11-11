<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF;

use ValueFormatters\FormatterOptions;

final class HookHandlers {

	public static function onExtensionRegistration(): void {

	}

	public static function onWikibaseRepoDataTypes( array &$dataTypeDefinitions ): void {
		$dataTypeDefinitions['PT:edtf'] = [
			'value-type' => 'string',
			'expert-module' => 'jquery.valueview.experts.edtf',
			'validator-factory-callback' => function() {

			},
			'formatter-factory-callback' => function( $format, FormatterOptions $options ) {

			},
			'rdf-builder-factory-callback' => function () {

			},
			'rdf-data-type' => function() {

			},
		];
	}

	public static function onWikibaseClientDataTypes( array &$dataTypeDefinitions ): void {
		$dataTypeDefinitions['PT:edtf'] = [
			'value-type' => 'string',
			'formatter-factory-callback' => function( $format, FormatterOptions $options ) {

			},
		];
	}

}
