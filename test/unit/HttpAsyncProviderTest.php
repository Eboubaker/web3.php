<?php

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Web3\Providers\HttpAsyncProvider;

class HttpAsyncProviderTest extends TestCase
{
    /**
     * provider
     * 
     * @var \Web3\Providers\HttpAsyncProvider
     */
    protected $provider;

    /**
     * setUp
     * 
     * @return void
     */
    public function setUp(): void
    {
        $this->provider = new HttpAsyncProvider('http://localhost:8545');
    }

    /**
     * testConstruct
     * 
     * @return void
     */
    public function testConstruct()
    {
        $provider = $this->provider;

        $this->assertEquals('http://localhost:8545', $provider->host);
        $this->assertEquals(1, $provider->timeout);
    }

    /**
     * testConstructWithTimeout
     * 
     * @return void
     */
    public function testConstructWithTimeout()
    {
        $provider = new HttpAsyncProvider('http://localhost:8545', 5);

        $this->assertEquals('http://localhost:8545', $provider->host);
        $this->assertEquals(5, $provider->timeout);
    }

    /**
     * testBatch
     * 
     * @return void
     */
    public function testBatch()
    {
        $provider = $this->provider;

        $this->assertFalse($provider->isBatch);
        
        $provider->batch(true);
        $this->assertTrue($provider->isBatch);
        
        $provider->batch(false);
        $this->assertFalse($provider->isBatch);
    }

    /**
     * testClose
     * 
     * @return void
     */
    public function testClose()
    {
        $provider = $this->provider;
        
        // close() should not throw any exception
        $provider->close();
        
        $this->assertTrue(true); // If we get here, test passed
    }
}
