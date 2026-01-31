<?php

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Web3\Contracts\Types\Tuple;
use Web3\Contracts\Types\Address;
use Web3\Contracts\Types\Uinteger;
use Web3\Contracts\Types\Boolean;

class TupleTypeTest extends TestCase
{
    /**
     * tuple
     * 
     * @var \Web3\Contracts\Types\Tuple
     */
    protected $tuple;

    /**
     * setUp
     * 
     * @return void
     */
    public function setUp(): void
    {
        $this->tuple = new Tuple();
    }

    /**
     * testIsType
     * 
     * @return void
     */
    public function testIsType()
    {
        $tuple = $this->tuple;

        $this->assertTrue($tuple->isType('(uint256,address)'));
        $this->assertTrue($tuple->isType('tuple(uint256,address)'));
        $this->assertTrue($tuple->isType('(bool,string)'));
        $this->assertFalse($tuple->isType('uint256'));
        $this->assertFalse($tuple->isType('address'));
    }

    /**
     * testIsDynamicType
     * 
     * @return void
     */
    public function testIsDynamicType()
    {
        $tuple = $this->tuple;

        $this->assertFalse($tuple->isDynamicType());
    }

    /**
     * testInputFormat
     * 
     * @return void
     */
    public function testInputFormat()
    {
        $tuple = $this->tuple;
        
        // Test simple tuple (uint256, bool)
        $abiType = [
            'coders' => [
                [
                    'dynamic' => false,
                    'solidityType' => new Uinteger(),
                    'name' => 'uint256'
                ],
                [
                    'dynamic' => false,
                    'solidityType' => new Boolean(),
                    'name' => 'bool'
                ]
            ]
        ];
        
        $params = ['123', true];
        $encoded = $tuple->inputFormat($params, $abiType);
        
        // Should be 64 hex chars (32 bytes) for uint256 + 64 hex chars (32 bytes) for bool
        $this->assertIsString($encoded);
        $this->assertEquals(128, strlen($encoded)); // 64 + 64 hex chars
    }

    /**
     * testInputFormatWithAddress
     * 
     * @return void
     */
    public function testInputFormatWithAddress()
    {
        $tuple = $this->tuple;
        
        // Test tuple (address, uint256)
        $abiType = [
            'coders' => [
                [
                    'dynamic' => false,
                    'solidityType' => new Address(),
                    'name' => 'address'
                ],
                [
                    'dynamic' => false,
                    'solidityType' => new Uinteger(),
                    'name' => 'uint256'
                ]
            ]
        ];
        
        $params = ['0x7a250d5630b4cf539739df2c5dacb4c659f2488d', '1000'];
        $encoded = $tuple->inputFormat($params, $abiType);
        
        $this->assertIsString($encoded);
        $this->assertEquals(128, strlen($encoded)); // 64 + 64 hex chars
    }
}
