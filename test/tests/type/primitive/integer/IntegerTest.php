<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Sop\ASN1\Element;
use Sop\ASN1\Type\Primitive\Integer;
use Sop\ASN1\Type\Primitive\NullType;
use Sop\ASN1\Type\UnspecifiedType;

/**
 * @group type
 * @group integer
 *
 * @internal
 */
class IntegerTest extends TestCase
{
    public function testCreate()
    {
        $el = new Integer(1);
        $this->assertInstanceOf(Integer::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testTag(Element $el)
    {
        $this->assertEquals(Element::TYPE_INTEGER, $el->tag());
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     *
     * @return string
     */
    public function testEncode(Element $el): string
    {
        $der = $el->toDER();
        $this->assertIsString($der);
        return $der;
    }

    /**
     * @depends testEncode
     *
     * @param string $data
     *
     * @return Integer
     */
    public function testDecode(string $data): Integer
    {
        $el = Integer::fromDER($data);
        $this->assertInstanceOf(Integer::class, $el);
        return $el;
    }

    /**
     * @depends testCreate
     * @depends testDecode
     *
     * @param Element $ref
     * @param Element $el
     */
    public function testRecoded(Element $ref, Element $el)
    {
        $this->assertEquals($ref, $el);
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testWrapped(Element $el)
    {
        $wrap = new UnspecifiedType($el);
        $this->assertInstanceOf(Integer::class, $wrap->asInteger());
    }

    public function testWrappedFail()
    {
        $wrap = new UnspecifiedType(new NullType());
        $this->expectException(\UnexpectedValueException::class);
        $wrap->asInteger();
    }

    /**
     * @depends testCreate
     *
     * @param Element $el
     */
    public function testIntNumber(Integer $el)
    {
        $this->assertEquals(1, $el->intNumber());
    }

    public function testIntNumberOverflow()
    {
        $num = gmp_init(PHP_INT_MAX, 10) + 1;
        $int = new Integer(gmp_strval($num, 10));
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Integer overflow.');
        $int->intNumber();
    }
}
