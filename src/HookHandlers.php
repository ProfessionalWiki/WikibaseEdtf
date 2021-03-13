<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF;

use ValueFormatters\FormatterOptions;
use Wikibase\Repo\Rdf\DedupeBag;
use Wikibase\Repo\Rdf\RdfVocabulary;
use Wikimedia\Purtle\RdfWriter;

final class HookHandlers {

	public static function onExtensionRegistration(): void {

	}

	public static function onWikibaseRepoDataTypes( array &$dataTypeDefinitions ): void {
		$dataTypeDefinitions['PT:edtf'] = [
			'value-type' => 'string',
			'expert-module' => 'jquery.valueview.experts.edtf',
			'validator-factory-callback' => function() {
				return [ WikibaseEdtf::getGlobalInstance()->getValidator() ];
			},
			'parser-factory-callback' => function() {
				return WikibaseEdtf::getGlobalInstance()->getParser();
			},
			'formatter-factory-callback' => function( string $format, FormatterOptions $options ) {
				return WikibaseEdtf::getGlobalInstance()->getFormatter( $format, $options );
			},
			'rdf-builder-factory-callback' => function ( int $flags, RdfVocabulary $vocab, RdfWriter $writer, $tracker, DedupeBag $dedupe ) {
				return WikibaseEdtf::getGlobalInstance()->getRdfBuilder( $flags, $vocab, $writer, $dedupe );
			},
//			'rdf-data-type' => function() {
//			},
		];
	}

	public static function onWikibaseClientDataTypes( array &$dataTypeDefinitions ): void {
		$dataTypeDefinitions['PT:edtf'] = [
			'value-type' => 'string',
			'formatter-factory-callback' => function( string $format, FormatterOptions $options ) {
				return WikibaseEdtf::getGlobalInstance()->getFormatter( $format, $options );
			},
		];
	}

}
