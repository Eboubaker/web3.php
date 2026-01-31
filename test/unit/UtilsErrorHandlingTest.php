<?php

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Web3\Utils;
use InvalidArgumentException;

class UtilsErrorHandlingTest extends TestCase
{
    /**
     * testToHexInvalidArgument
     * 
     * @return void
     */
    public function testToHexInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value to toHex function is not support.');
        
        Utils::toHex([]); // Arrays are not supported
    }

    /**
     * testHexToBinInvalidArgument
     * 
     * @return void
     */
    public function testHexToBinInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value to hexToBin function must be string.');
        
        Utils::hexToBin(123); // Must be string
    }

    /**
     * testIsZeroPrefixedInvalidArgument
     * 
     * @return void
     */
    public function testIsZeroPrefixedInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value to isZeroPrefixed function must be string.');
        
        Utils::isZeroPrefixed(null); // Must be string
    }

    /**
     * testIsNegativeInvalidArgument
     * 
     * @return void
     */
    public function testIsNegativeInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value to isNegative function must be string.');
        
        Utils::isNegative(123); // Must be string
    }

    /**
     * testIsAddressInvalidArgument
     * 
     * @return void
     */
    public function testIsAddressInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value to isAddress function must be string.');
        
        Utils::isAddress(123); // Must be string
    }

    /**
     * testIsAddressChecksumInvalidArgument
     * 
     * @return void
     */
    public function testIsAddressChecksumInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value to isAddressChecksum function must be string.');
        
        Utils::isAddressChecksum(null); // Must be string
    }

    /**
     * testToChecksumAddressInvalidArgument
     * 
     * @return void
     */
    public function testToChecksumAddressInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value to toChecksumAddress function must be string.');
        
        Utils::toChecksumAddress([]); // Must be string
    }

    /**
     * testSha3InvalidArgument
     * 
     * @return void
     */
    public function testSha3InvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value to sha3 function must be string.');
        
        Utils::sha3(123); // Must be string
    }

    /**
     * testToWeiInvalidNumberType
     * 
     * @return void
     */
    public function testToWeiInvalidNumberType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('toWei number must be string or bignumber.');
        
        Utils::toWei([], 'ether'); // Must be string or BigNumber
    }

    /**
     * testToWeiInvalidUnitType
     * 
     * @return void
     */
    public function testToWeiInvalidUnitType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('toWei unit must be string.');
        
        Utils::toWei('1', 123); // Unit must be string
    }

    /**
     * testToWeiUnsupportedUnit
     * 
     * @return void
     */
    public function testToWeiUnsupportedUnit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('toWei doesn\'t support invalidunit unit.');
        
        Utils::toWei('1', 'invalidunit'); // Unit not supported
    }

    /**
     * testToWeiFractionTooLong
     * 
     * @return void
     */
    public function testToWeiFractionTooLong()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('toWei fraction part is out of limit.');
        
        // Wei has 1 digit, trying to convert 0.12 (2 fraction digits) should fail
        Utils::toWei('0.12', 'wei');
    }

    /**
     * testFromWeiInvalidUnitType
     * 
     * @return void
     */
    public function testFromWeiInvalidUnitType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('fromWei unit must be string.');
        
        Utils::fromWei('1000', 123); // Unit must be string
    }

    /**
     * testFromWeiUnsupportedUnit
     * 
     * @return void
     */
    public function testFromWeiUnsupportedUnit()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('fromWei doesn\'t support invalidunit unit.');
        
        Utils::fromWei('1000', 'invalidunit'); // Unit not supported
    }

    /**
     * testJsonMethodToStringInvalidArgument
     * 
     * @return void
     */
    public function testJsonMethodToStringInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('jsonMethodToString json must be array or stdClass.');
        
        Utils::jsonMethodToString('invalid'); // Must be array or stdClass
    }

    /**
     * testToBnInvalidNumberType
     * 
     * @return void
     */
    public function testToBnInvalidNumberType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('toBn number must be BigNumber, string or int.');
        
        Utils::toBn([]); // Must be BigNumber, string, or int
    }

    /**
     * testToBnInvalidNumber
     * 
     * @return void
     */
    public function testToBnInvalidNumber()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('toBn number must be valid hex string.');
        
        Utils::toBn('1.2.3'); // Invalid number format - treated as invalid hex
    }

    /**
     * testToBnInvalidHexString
     * 
     * @return void
     */
    public function testToBnInvalidHexString()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('toBn number must be valid hex string.');
        
        Utils::toBn('xyz'); // Invalid hex string
    }
}
