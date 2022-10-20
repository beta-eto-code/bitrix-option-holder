<?php

namespace Bx\OptionHolder\Tests;

use Bitrix\Main\Config\Option;
use Bx\OptionHolder\BitrixOptionHolder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Bx\OptionHolder\BitrixOptionHolder
 */
class BitrixOptionHolderTest extends TestCase
{
    public function testSetAndGetOptionValue()
    {
        Option::$options = [];
        $bitrixHolder = new BitrixOptionHolder('main_new');
        $bitrixHolder->setOptionValue('test', 'test_value');

        $this->assertEquals('test_value', Option::$options['main_new']['test']);
        $this->assertEquals('test_value', $bitrixHolder->getOptionValue('test'));
        $this->assertEquals('test_value', $bitrixHolder->getOptionValue('test', 'main_new'));

        $bitrixHolder->setOptionValue('new', 'new_value', 'new_keyspace');

        $this->assertEquals('new_value', Option::$options['new_keyspace']['new']);
        $this->assertEquals('new_value', $bitrixHolder->getOptionValue('new', 'new_keyspace'));
        $this->assertEmpty($bitrixHolder->getOptionValue('new'));
    }
}
