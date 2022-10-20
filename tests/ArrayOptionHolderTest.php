<?php

namespace Bx\OptionHolder\Tests;

use Bx\OptionHolder\ArrayOptionHolder;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Bx\OptionHolder\ArrayOptionHolder
 */
class ArrayOptionHolderTest extends TestCase
{
    public function testSetAndGetOptionValue()
    {
        $arrayHolder = new ArrayOptionHolder('main_new');
        $arrayHolder->setOptionValue('test', 'test_value');

        $this->assertEquals('test_value', $arrayHolder->getOptionValue('test'));
        $this->assertEquals('test_value', $arrayHolder->getOptionValue('test', 'main_new'));

        $arrayHolder->setOptionValue('new', 'new_value', 'new_keyspace');

        $this->assertEquals('new_value', $arrayHolder->getOptionValue('new', 'new_keyspace'));
        $this->assertEmpty($arrayHolder->getOptionValue('new'));
    }
}
