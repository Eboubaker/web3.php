<?php

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Web3\Contracts\Types\DynamicArray;
use Web3\Contracts\Types\Uinteger;
use Web3\Contracts\Types\Address;
use Web3\Contracts\Types\Str;

class DynamicArrayTypeTest extends TestCase
{
    /**
     * dynamicArray
     * 
     * @var \Web3\Contracts\Types\DynamicArray
     */
    protected $dynamicArray;

    /**
     * setUp
     * 
     * @return void
     */
    public function setUp(): void
    {
        $this->dynamicArray = new DynamicArray();
    }

    /**
     * testIsType
     * 
     * @return void
     */
    public function testIsType()
    {
        $dynamicArray = $this->dynamicArray;

        $this->assertTrue($dynamicArray->isType('uint256[]'));
        $this->assertTrue($dynamicArray->isType('address[]'));
        $this->assertTrue($dynamicArray->isType('string[]'));
        $this->assertFalse($dynamicArray->isType('uint256'));
        $this->assertFalse($dynamicArray->isType('uint256[5]'));
    }

    /**
     * testIsDynamicType
     * 
     * @return void
     */
    public function testIsDynamicType()
    {
        $dynamicArray = $this->dynamicArray;

        $this->assertTrue($dynamicArray->isDynamicType());
    }

    /**
     * testInputFormatUint
     * 
     * @return void
     */
    public function testInputFormatUint()
    {
        $dynamicArray = $this->dynamicArray;
        
        // Test uint256[] with values [1, 2, 3]
        $abiType = [
            'coders' => [
                'dynamic' => false,
                'solidityType' => new Uinteger(),
                'name' => 'uint256'
            ]
        ];
        
        $params = ['1', '2', '3'];
        $encoded = $dynamicArray->inputFormat($params, $abiType);
        
        $this->assertIsString($encoded);
        // Should start with length (3) encoded as 32 bytes (64 hex chars)
        // Then 3 uint256 values (3 * 64 hex chars)
        $this->assertEquals(256, strlen($encoded)); // 64 (length) + 192 (3 * 64) hex chars
        
        // Check that length is encoded correctly (3 = 0x3)
        $lengthPart = substr($encoded, 0, 64);
        $this->assertStringEndsWith('3', $lengthPart);
    }

    /**
     * testInputFormatAddress
     * 
     * @return void
     */
    public function testInputFormatAddress()
    {
        $dynamicArray = $this->dynamicArray;
        
        // Test address[] with 2 addresses
        $abiType = [
            'coders' => [
                'dynamic' => false,
                'solidityType' => new Address(),
                'name' => 'address'
            ]
        ];
        
        $params = [
            '0x7a250d5630b4cf539739df2c5dacb4c659f2488d',
            '0x5c69bee701ef814a2b6a3edd4b1652cb9cc5aa6f'
        ];
        $encoded = $dynamicArray->inputFormat($params, $abiType);
        
        $this->assertIsString($encoded);
        // Should be 64 (length) + 128 (2 * 64) = 192 hex chars
        $this->assertEquals(192, strlen($encoded));
    }

    /**
     * testInputFormatEmpty
     * 
     * @return void
     */
    public function testInputFormatEmpty()
    {
        $dynamicArray = $this->dynamicArray;
        
        // Test empty array
        $abiType = [
            'coders' => [
                'dynamic' => false,
                'solidityType' => new Uinteger(),
                'name' => 'uint256'
            ]
        ];
        
        $params = [];
        $encoded = $dynamicArray->inputFormat($params, $abiType);
        
        $this->assertIsString($encoded);
        // Should be just the length (0) encoded as 32 bytes (64 hex chars)
        $this->assertEquals(64, strlen($encoded));
        
        // Check that length is 0
        $lengthPart = substr($encoded, 0, 64);
        $this->assertEquals(str_repeat('0', 64), $lengthPart);
    }
}
