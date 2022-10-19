# KV manager for Bitrix

```php
use Bx\OptionHolder\ConfigurationOptionHolder;
use Bx\OptionHolder\BitrixOptionHolder;
use Bx\OptionHolder\ComplexOptionHolder;
use Bx\OptionHolder\ArrayOptionHolder;
use Bx\OptionHolder\CachedOptionHolder;
use Psr\SimpleCache\CacheInterface;

$settingsHolder = new ConfigurationOptionHolder('settings'); // read/write bitrix settings (default .setting.php)
$optionHolder = new BitrixOptionHolder('main'); // work with Bitrix\Main\Config\Option class
$arrayHolder = new ArrayOptionHolder('custom');
$externalHolder = new SomeProtectedExternalVault();

/**
 * @var CacheInterface $cache
 */
$cache = new SomeSimpleCacheImplementation();
$cachedExternalHolder = new CachedOptionHolder($externalHolder, $cache, 3600);

$complexHolder = new ComplexOptionHolder($optionHolder, 'main');
$complexHolder->addHolderOption($settingsHolder, 'settings');
$complexHolder->addHolderOption($arrayHolder, 'custom', 'firstKey', 'secondKey');
$complexHolder->addHolderOption($cachedExternalHolder, 'main', 'PUBLIC_KEY', 'PRIVATE_KEY');
$complexHolder->addHolderOption($cachedExternalHolder, 'my.module', 'some_key', 'one_more_key', 'last_key');

$complexHolder->setOptionValue('firstKey', 'firstValue', 'custom'); // saved in ArrayHolder
$complexHolder->setOptionValue('secondKey', 'secondValue', 'custom'); // saved in ArrayHolder
$complexHolder->setOptionValue('thirdKey', 'thirdValue'); // saved in BitrixOptionHolder with main keySpase
$complexHolder->setOptionValue('oneMoreKey', 'oneMoreValue', 'custom'); // saved in BitrixOptionHolder with custom keySpase
$complexHolder->setOptionValue('someSecret', 'some secret', 'settings'); // saved in .setting.php
$complexHolder->setOptionValue('PUBLIC_KEY', '....', 'main'); // saved in SomeProtectedExternalVault
$complexHolder->setOptionValue('PUBLIC_KEY_2', '....', 'main'); // saved in BitrixOptionHolder

$bitrixConnectionSettings = $complexHolder->getOptionValue('connections', 'settings', []);
$flagUseCrontab = $complexHolder->getOptionValue('agents_use_crontab', 'main', 'N');
$privateKey = $complexHolder->getOptionValue('PRIVATE_KEY', 'main'); // get value from SomeProtectedExternalVault with cache in hour
```