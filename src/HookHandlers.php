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
				return [ WikibaseEdtf::getGlobalInstance()->getEdtfValidator() ];
			},
			'parser-factory-callback' => function() {
				return WikibaseEdtf::getGlobalInstance()->getEdtfParser();
			},
			'formatter-factory-callback' => function() {
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
				return WikibaseEdtf::getGlobalInstance()->getEdtfFormatter();
			},
		];
	}

}
