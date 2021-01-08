<?php

declare( strict_types = 1 );

namespace Wikibase\EDTF\Tests\Unit;

use EDTF\EdtfParser;
use PHPUnit\Framework\TestCase;
use Wikibase\EDTF\Services\Parser;

/**
 * @covers \Wikibase\EDTF\Services\Parser
 */
class ParserTest extends TestCase {

	private const VALID_DATE_AND_TIME = '1985-04-12T23:20:30';

	public function testHappyPath() {
		$this->assertSame(
			self::VALID_DATE_AND_TIME,
			$this->newParser()->parse( self::VALID_DATE_AND_TIME )->getValue()
		);
	}

	private function newParser(): Parser {
		return new Parser( new EdtfParser() );
	}

	public function testNonStringCausesInvalidArgumentException() {
		$parser = $this->newParser();
		$this->expectException( \InvalidArgumentException::class );
		$parser->parse( 35505 );
	}

}
