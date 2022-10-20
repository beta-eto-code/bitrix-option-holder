<?php

namespace Bx\OptionHolder\Tests;

use Bitrix\Main\Config\Option;
use Bx\OptionHolder\ArrayOptionHolder;
use Bx\OptionHolder\BitrixOptionHolder;
use Bx\OptionHolder\ComplexOptionHolder;
use Bx\OptionHolder\OptionHolderInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Bx\OptionHolder\ComplexOptionHolder
 */
class ComplexOptionHolderTest extends TestCase
{
    public function testAddHolderOption()
    {
        Option::$options = [];
        $defaultOptionHolder = new ArrayOptionHolder('test');
        $complexOptionHolder = new ComplexOptionHolder($defaultOptionHolder, 'test');
        $bitrixOptionHolder = new BitrixOptionHolder('test2');
        $complexOptionHolder->addHolderOption($bitrixOptionHolder, 'test2', 'key1', 'key2');

        $complexOptionHolder->setOptionValue('key1', 'value1', 'test2');
        $this->assertEquals('value1', Option::$options['test2']['key1']);
        $this->assertEmpty($defaultOptionHolder->getOptionValue('key1', 'test2'));

        $complexOptionHolder->setOptionValue('key1', 'new_value');
        $this->assertEquals('value1', Option::$options['test2']['key1']);
        $this->assertEquals('value1', $complexOptionHolder->getOptionValue('key1', 'test2'));
        $this->assertEquals('new_value', $defaultOptionHolder->getOptionValue('key1', 'test'));

        $defaultOptionHolder->setOptionValue('key1', 'valueFromDefaultHolder', 'test2');
        $this->assertEquals('value1', Option::$options['test2']['key1']);
        $this->assertEquals(
            'valueFromDefaultHolder',
            $defaultOptionHolder->getOptionValue('key1', 'test2')
        );
        $this->assertEquals('value1', $complexOptionHolder->getOptionValue('key1', 'test2'));
    }

    public function testSetCallbackHolder()
    {
        Option::$options = [];
        $defaultOptionHolder = new ArrayOptionHolder('test');
        $complexOptionHolder = new ComplexOptionHolder($defaultOptionHolder, 'test');
        $bitrixOptionHolder = new BitrixOptionHolder('test2');
        $complexOptionHolder->setCallbackHolder(function (
            string $key,
            string $keySpace
        ) use ($bitrixOptionHolder) : ?OptionHolderInterface {
            if (in_array($key, ['key1', 'key2']) && $keySpace === 'test2') {
                return $bitrixOptionHolder;
            }

            return null;
        });

        $complexOptionHolder->setOptionValue('key1', 'value1', 'test2');
        $this->assertEquals('value1', Option::$options['test2']['key1']);
        $this->assertEmpty($defaultOptionHolder->getOptionValue('key1', 'test2'));

        $complexOptionHolder->setOptionValue('key1', 'new_value');
        $this->assertEquals('value1', Option::$options['test2']['key1']);
        $this->assertEquals('value1', $complexOptionHolder->getOptionValue('key1', 'test2'));
        $this->assertEquals('new_value', $defaultOptionHolder->getOptionValue('key1', 'test'));

        $defaultOptionHolder->setOptionValue('key1', 'valueFromDefaultHolder', 'test2');
        $this->assertEquals('value1', Option::$options['test2']['key1']);
        $this->assertEquals(
            'valueFromDefaultHolder',
            $defaultOptionHolder->getOptionValue('key1', 'test2')
        );
        $this->assertEquals('value1', $complexOptionHolder->getOptionValue('key1', 'test2'));
    }
}
