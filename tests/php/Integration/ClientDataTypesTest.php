<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wikibase\Client\WikibaseClient;
use Wikibase\EDTF\HookHandlers;
use Wikibase\Lib\DataTypeDefinitions;

/**
 * @covers \Wikibase\EDTF\HookHandlers
 */
class ClientDataTypesTest extends TestCase {

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
			WikibaseClient::getDataTypeFactory()->getTypeIds()
		);
	}

}
