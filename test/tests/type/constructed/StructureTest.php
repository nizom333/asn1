<?php

use ASN1\Element;
use ASN1\Type\Constructed\Sequence;
use ASN1\Type\Primitive\Boolean;
use ASN1\Type\Primitive\NullType;
use ASN1\Type\Structure;


/**
 * @group type
 * @group structure
 */
class StructureTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider hasProvider
	 *
	 * @param int $idx
	 * @param bool $result
	 */
	public function testHas($idx, $result) {
		$seq = new Sequence(new NullType(), new Boolean(true), new NullType());
		$this->assertEquals($seq->has($idx), $result);
	}
	
	public function hasProvider() {
		// @formatter:off
		return array(
			[0, true],
			[1, true],
			[2, true],
			[3, false]
		);
		// @formatter:on
	}
	
	/**
	 * @dataProvider hasTypeProvider
	 *
	 * @param int $idx
	 * @param int $type
	 * @param bool $result
	 */
	public function testHasType($idx, $type, $result) {
		$seq = new Sequence(new NullType(), new Boolean(true));
		$this->assertEquals($seq->has($idx, $type), $result);
	}
	
	public function hasTypeProvider() {
		// @formatter:off
		return array(
			[0, Element::TYPE_NULL, true],
			[0, Element::TYPE_INTEGER, false],
			[1, Element::TYPE_BOOLEAN, true],
			[2, Element::TYPE_NULL, false]
		);
		// @formatter:on
	}
	
	public function testExplode() {
		$el = new Sequence(new NullType(), new NullType(), new NullType());
		$der = $el->toDER();
		$parts = Structure::explodeDER($der);
		$null = "\x5\x0";
		$this->assertEquals([$null, $null, $null], $parts);
	}
	
	/**
	 * @expectedException ASN1\Exception\DecodeException
	 */
	public function testExplodePrimitiveFail() {
		$el = new NullType();
		$der = $el->toDER();
		Structure::explodeDER($der);
	}
}