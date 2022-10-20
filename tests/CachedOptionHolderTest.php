<?php

namespace Bx\OptionHolder\Tests;

use Bx\OptionHolder\ArrayOptionHolder;
use Bx\OptionHolder\CachedOptionHolder;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

/**
 * @covers \Bx\OptionHolder\CachedOptionHolder
 */
class CachedOptionHolderTest extends TestCase
{
    public function testSetAndGetOptionValue()
    {
        $arrayHolder = new ArrayOptionHolder('main_new');

        $ttl = 3600;
        $cacheMock = $this->createMock(CacheInterface::class);
        $cacheMock->expects($this->exactly(3))
            ->method('set')
            ->withConsecutive(
                ['main_new.test', 'test_value', $ttl],
                ['main_new.test_cache', 'test_cache_value', $ttl],
                ['new_keyspace.new', 'new_value', $ttl]
            )
            ->willReturn(true, true, true);
        $cacheMock->expects($this->exactly(3))
            ->method('get')
            ->withConsecutive(
                ['main_new.test'],
                ['main_new.test'],
                ['new_keyspace.new']
            )
            ->willReturn(null, 'test_value', null);

        $cachedArrayHolder = new CachedOptionHolder($arrayHolder, $cacheMock, $ttl);
        $arrayHolder->setOptionValue('test', 'test_value');

        $this->assertEquals('test_value', $arrayHolder->getOptionValue('test'));
        $this->assertEquals('test_value', $arrayHolder->getOptionValue('test', 'main_new'));
        $this->assertEquals('test_value', $cachedArrayHolder->getOptionValue('test'));
        $this->assertEquals('test_value', $cachedArrayHolder->getOptionValue('test', 'main_new'));

        $cachedArrayHolder->setOptionValue('test_cache', 'test_cache_value');
        $this->assertEquals('test_cache_value', $arrayHolder->getOptionValue('test_cache'));
        $this->assertEquals(
            'test_cache_value',
            $arrayHolder->getOptionValue('test_cache', 'main_new')
        );

        $arrayHolder->setOptionValue('new', 'new_value', 'new_keyspace');

        $this->assertEquals('new_value', $arrayHolder->getOptionValue('new', 'new_keyspace'));
        $this->assertEmpty($arrayHolder->getOptionValue('new'));
        $this->assertEquals('new_value', $cachedArrayHolder->getOptionValue('new', 'new_keyspace'));
    }
}
