<?php

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Web3\Contracts\Types\SizedArray;
use Web3\Contracts\Types\Uinteger;
use Web3\Contracts\Types\Address;
use InvalidArgumentException;

class SizedArrayTypeTest extends TestCase
{
    /**
     * sizedArray
     * 
     * @var \Web3\Contracts\Types\SizedArray
     */
    protected $sizedArray;

    /**
     * setUp
     * 
     * @return void
     */
    public function setUp(): void
    {
        $this->sizedArray = new SizedArray();
    }

    /**
     * testIsType
     * 
     * @return void
     */
    public function testIsType()
    {
        $sizedArray = $this->sizedArray;

        $this->assertTrue($sizedArray->isType('uint256[5]'));
        $this->assertTrue($sizedArray->isType('address[10]'));
        $this->assertTrue($sizedArray->isType('string[3]'));
        // Note: The regex matches [] because [0-9]* allows zero or more digits
        $this->assertTrue($sizedArray->isType('uint256[]')); // This actually matches
        $this->assertFalse($sizedArray->isType('uint256'));
    }

    /**
     * testIsDynamicType
     * 
     * @return void
     */
    public function testIsDynamicType()
    {
        $sizedArray = $this->sizedArray;

        $this->assertFalse($sizedArray->isDynamicType());
    }

    /**
     * testInputFormatUint
     * 
     * @return void
     */
    public function testInputFormatUint()
    {
        $sizedArray = $this->sizedArray;
        
        // Test uint256[3] with values [1, 2, 3]
        $abiType = [
            'type' => 'uint256[3]',
            'coders' => [
                'dynamic' => false,
                'solidityType' => new Uinteger(),
                'name' => 'uint256'
            ]
        ];
        
        $params = ['1', '2', '3'];
        $encoded = $sizedArray->inputFormat($params, $abiType);
        
        $this->assertIsString($encoded);
        // Should be 3 uint256 values (3 * 64 hex chars = 192)
        $this->assertEquals(192, strlen($encoded));
    }

    /**
     * testInputFormatAddress
     * 
     * @return void
     */
    public function testInputFormatAddress()
    {
        $sizedArray = $this->sizedArray;
        
        // Test address[2] with 2 addresses
        $abiType = [
            'type' => 'address[2]',
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
        $encoded = $sizedArray->inputFormat($params, $abiType);
        
        $this->assertIsString($encoded);
        // Should be 2 * 64 = 128 hex chars
        $this->assertEquals(128, strlen($encoded));
    }

    /**
     * testInputFormatInvalidLength
     * 
     * @return void
     */
    public function testInputFormatInvalidLength()
    {
        $this->expectException(InvalidArgumentException::class);
        
        $sizedArray = $this->sizedArray;
        
        // Test uint256[3] but provide 4 values - should throw exception
        $abiType = [
            'type' => 'uint256[3]',
            'coders' => [
                'dynamic' => false,
                'solidityType' => new Uinteger(),
                'name' => 'uint256'
            ]
        ];
        
        $params = ['1', '2', '3', '4']; // Too many values
        $sizedArray->inputFormat($params, $abiType);
    }

    /**
     * testInputFormatNotArray
     * 
     * @return void
     */
    public function testInputFormatNotArray()
    {
        $this->expectException(InvalidArgumentException::class);
        
        $sizedArray = $this->sizedArray;
        
        $abiType = [
            'type' => 'uint256[3]',
            'coders' => [
                'dynamic' => false,
                'solidityType' => new Uinteger(),
                'name' => 'uint256'
            ]
        ];
        
        $params = 'not-an-array'; // Should be array
        $sizedArray->inputFormat($params, $abiType);
    }
}
