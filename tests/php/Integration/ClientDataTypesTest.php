<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use DataValues\StringValue;
use PHPUnit\Framework\TestCase;
use ValueFormatters\FormatterOptions;
use Wikibase\Client\WikibaseClient;
use Wikibase\EDTF\HookHandlers;
use Wikibase\Lib\DataTypeDefinitions;
use Wikibase\Lib\Formatters\SnakFormatter;
use Wikibase\Repo\WikibaseRepo;

/**
 * @covers \Wikibase\EDTF\HookHandlers
 */
class ClientDataTypesTest extends TestCase {

	private const VALID_DATE_AND_TIME = '1985-04-12T23:20:30';

	public function testCanConstructDataTypeDefinitions() {
		$definitions = new DataTypeDefinitions( $this->newTypeArray() );

		$this->assertSame( [ 'edtf' ], $definitions->getTypeIds() );
	}

	private function newTypeArray(): array {
		$types = [];
		HookHandlers::onWikibaseClientDataTypes( $types );
		return $types;
	}

	public function testTypeIsRegistered() {
		$this->assertContains(
			'edtf',
			WikibaseClient::getDefaultInstance()->getDataTypeFactory()->getTypeIds()
		);
	}

}
