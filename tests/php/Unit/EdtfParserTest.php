<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use EDTF\Parser;
use PHPUnit\Framework\TestCase;
use Wikibase\EDTF\Services\EdtfParser;

/**
 * @covers \Wikibase\EDTF\Services\EdtfParser
 */
class EdtfParserTest extends TestCase {

	private const VALID_DATE_AND_TIME = '1985-04-12T23:20:30';

	public function testHappyPath() {
		$this->assertSame(
			self::VALID_DATE_AND_TIME,
			$this->newParser()->parse( self::VALID_DATE_AND_TIME )->getValue()
		);
	}

	private function newParser(): EdtfParser {
		return new EdtfParser( new Parser() );
	}

	public function testNonStringCausesInvalidArgumentException() {
		$parser = $this->newParser();
		$this->expectException( \InvalidArgumentException::class );
		$parser->parse( 35505 );
	}

}
