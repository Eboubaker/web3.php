<?php

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Web3\Utils;
use phpseclib3\Math\BigInteger as BigNumber;

class UtilsAdvancedTest extends TestCase
{
    /**
     * testToHexWithFloat
     * 
     * @return void
     */
    public function testToHexWithFloat()
    {
        $hex = Utils::toHex(3);
        $this->assertIsString($hex);
        $this->assertEquals('3', $hex);
    }

    /**
     * testToHexWithBigNumber
     * 
     * @return void
     */
    public function testToHexWithBigNumber()
    {
        $bn = new BigNumber('1000000000000000000');
        $hex = Utils::toHex($bn);
        $this->assertEquals('de0b6b3a7640000', $hex);
        
        $hexWithPrefix = Utils::toHex($bn, true);
        $this->assertEquals('0xde0b6b3a7640000', $hexWithPrefix);
    }

    /**
     * testStripZero
     * 
     * @return void
     */
    public function testStripZero()
    {
        $this->assertEquals('1234', Utils::stripZero('0x1234'));
        $this->assertEquals('abcd', Utils::stripZero('0xabcd'));
        $this->assertEquals('5678', Utils::stripZero('5678')); // No 0x prefix
    }

    /**
     * testIsNegative
     * 
     * @return void
     */
    public function testIsNegative()
    {
        $this->assertTrue(Utils::isNegative('-123'));
        $this->assertTrue(Utils::isNegative('-0.5'));
        $this->assertFalse(Utils::isNegative('123'));
        $this->assertFalse(Utils::isNegative('0'));
    }

    /**
     * testIsHex
     * 
     * @return void
     */
    public function testIsHex()
    {
        $this->assertTrue(Utils::isHex('0x1234'));
        $this->assertTrue(Utils::isHex('abcdef'));
        $this->assertTrue(Utils::isHex('0xabcdef'));
        // Note: isHex only accepts lowercase hex chars
        $this->assertFalse(Utils::isHex('0xABCDEF')); // Uppercase not allowed
        $this->assertFalse(Utils::isHex('xyz'));
        $this->assertFalse(Utils::isHex('12.34'));
        $this->assertFalse(Utils::isHex(123)); // Not a string
    }

    /**
     * testSha3EmptyString
     * 
     * @return void
     */
    public function testSha3EmptyString()
    {
        $hash = Utils::sha3('');
        // Empty string produces the null hash, which returns null
        $this->assertNull($hash);
    }

    /**
     * testSha3WithHexInput
     * 
     * @return void
     */
    public function testSha3WithHexInput()
    {
        $hash = Utils::sha3('0x1234');
        $this->assertIsString($hash);
        $this->assertEquals(66, strlen($hash));
    }

    /**
     * testToWeiWithNegativeNumber
     * 
     * @return void
     */
    public function testToWeiWithNegativeNumber()
    {
        $wei = Utils::toWei('-1', 'ether');
        $this->assertEquals('-1000000000000000000', $wei->toString());
    }

    /**
     * testToWeiWithFractionAndNegative
     * 
     * @return void
     */
    public function testToWeiWithFractionAndNegative()
    {
        $wei = Utils::toWei('-1.5', 'ether');
        $this->assertEquals('-1500000000000000000', $wei->toString());
    }

    /**
     * testToWeiWithBigNumber
     * 
     * @return void
     */
    public function testToWeiWithBigNumber()
    {
        $bn = new BigNumber('10');
        $wei = Utils::toWei($bn, 'ether');
        $this->assertEquals('10000000000000000000', $wei->toString());
    }

    /**
     * testToEther
     * 
     * @return void
     */
    public function testToEther()
    {
        // 1 kwei = 1000 wei, need 10^18 wei for 1 ether
        // So 1000 wei = 0.000000000000001 ether
        list($ether, $remainder) = Utils::toEther('1000', 'kwei');
        $this->assertEquals('0', $ether->toString());
        // 1000 kwei = 1,000,000 wei
        $this->assertEquals('1000000', $remainder->toString());
        
        list($ether2, $remainder2) = Utils::toEther('1', 'kether');
        $this->assertEquals('1000', $ether2->toString());
        $this->assertEquals('0', $remainder2->toString());
    }

    /**
     * testFromWei
     * 
     * @return void
     */
    public function testFromWei()
    {
        list($quotient, $remainder) = Utils::fromWei('1500000000000000000', 'ether');
        $this->assertEquals('1', $quotient->toString());
        $this->assertEquals('500000000000000000', $remainder->toString());
    }

    /**
     * testToBnWithZeroPrefixedHex
     * 
     * @return void
     */
    public function testToBnWithZeroPrefixedHex()
    {
        $bn = Utils::toBn('0x1234');
        $this->assertEquals('4660', $bn->toString());
        
        $bn2 = Utils::toBn('0xff');
        $this->assertEquals('255', $bn2->toString());
    }

    /**
     * testToBnWithNegativeHex
     * 
     * @return void
     */
    public function testToBnWithNegativeHex()
    {
        $bn = Utils::toBn('-0x10');
        $this->assertEquals('-16', $bn->toString());
    }

    /**
     * testToBnWithEmptyString
     * 
     * @return void
     */
    public function testToBnWithEmptyString()
    {
        $bn = Utils::toBn('');
        $this->assertEquals('0', $bn->toString());
    }

    /**
     * testToBnWithFraction
     * 
     * @return void
     */
    public function testToBnWithFraction()
    {
        $result = Utils::toBn('123.456');
        $this->assertIsArray($result);
        $this->assertCount(4, $result);
        $this->assertEquals('123', $result[0]->toString()); // whole part
        $this->assertEquals('456', $result[1]->toString()); // fraction part
        $this->assertEquals(3, $result[2]); // fraction length
        $this->assertFalse($result[3]); // not negative
    }

    /**
     * testToBnWithNegativeFraction
     * 
     * @return void
     */
    public function testToBnWithNegativeFraction()
    {
        $result = Utils::toBn('-123.456');
        $this->assertIsArray($result);
        $this->assertCount(4, $result);
        $this->assertEquals('123', $result[0]->toString());
        $this->assertEquals('456', $result[1]->toString());
        $this->assertEquals(3, $result[2]);
        $this->assertInstanceOf(BigNumber::class, $result[3]); // negative flag
        $this->assertEquals('-1', $result[3]->toString());
    }

    /**
     * testHexToNumber
     * 
     * @return void
     */
    public function testHexToNumber()
    {
        $num = Utils::hexToNumber('0x10');
        $this->assertEquals(16, $num);
        
        $num2 = Utils::hexToNumber('ff'); // Without 0x prefix
        $this->assertEquals(255, $num2);
        
        $num3 = Utils::hexToNumber('0x0');
        $this->assertEquals(0, $num3);
    }

    /**
     * testJsonToArray
     * 
     * @return void
     */
    public function testJsonToArray()
    {
        $obj = new \stdClass();
        $obj->name = 'test';
        $obj->value = 123;
        
        $arr = Utils::jsonToArray($obj);
        $this->assertIsArray($arr);
        $this->assertEquals('test', $arr['name']);
        $this->assertEquals(123, $arr['value']);
    }

    /**
     * testJsonToArrayNested
     * 
     * @return void
     */
    public function testJsonToArrayNested()
    {
        $inner = new \stdClass();
        $inner->foo = 'bar';
        
        $outer = new \stdClass();
        $outer->nested = $inner;
        $outer->value = 456;
        
        $arr = Utils::jsonToArray($outer);
        $this->assertIsArray($arr);
        $this->assertIsArray($arr['nested']);
        $this->assertEquals('bar', $arr['nested']['foo']);
        $this->assertEquals(456, $arr['value']);
    }

    /**
     * testHexToBinWithOddLength
     * 
     * @return void
     */
    public function testHexToBinWithOddLength()
    {
        $bin = Utils::hexToBin('0x123'); // Odd length after stripping 0x
        $this->assertIsString($bin);
        
        // Should pad with leading zero
        $hex = bin2hex($bin);
        $this->assertEquals('0123', $hex);
    }

    /**
     * testIsAddressWithMixedCase
     * 
     * @return void
     */
    public function testIsAddressWithMixedCase()
    {
        // Valid checksum address
        $this->assertTrue(Utils::isAddress('0xCA35b7d915458EF540aDe6068dFe2F44E8fa733c'));
        
        // Invalid checksum (wrong case)
        $this->assertFalse(Utils::isAddress('0Xca35b7d915458ef540ade6068dfe2f44e8fa733C'));
    }
}
