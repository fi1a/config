# PHP работа с файлами конфигураций. Поддерживает файлы в PHP и JSON форматах.

[![Latest Version][badge-release]][packagist]
[![Software License][badge-license]][license]
[![PHP Version][badge-php]][php]
![Coverage Status][badge-coverage]
[![Total Downloads][badge-downloads]][downloads]

Пакет представляет собой загрузчик конфигурационных файлов, который поддерживает PHP и JSON форматы.

## Установка

Установить этот пакет можно как зависимость, используя Composer.

``` bash
composer require fi1a/config ~1.0
```

## Пример загрузки и сохранения конфигурационного файла

Объект со значениями конфигурационного файла ```Fi1a\Config\ConfigValuesInterface``` можно создать с помощью фабричного метода load(),
в который необходимо передать объект ```Fi1a\Config\Readers\ReaderInterface``` для чтения из файла и
```Fi1a\Config\Parsers\ParserInterface``` для парсинга:

```php
use Fi1a\Config\Config;
use Fi1a\Config\ConfigValuesInterface;
use Fi1a\Config\Parsers\PHPParser;
use Fi1a\Config\Readers\FileReader;
use Fi1a\Config\Writers\FileWriter;

$filePath = __DIR__ . '/config.php';

$reader = new FileReader($filePath);
$writer = new FileWriter($filePath);

$parser = new PHPParser();

$config = Config::load($reader, $parser); // ConfigValuesInterface

$config->get('path:to:value', true);
$config->set('path:to:value', 'value');

Config::write($config, $parser, $writer); // true
```

## Пример загрузки конфигурационных файлов

Значения можно получить из нескольких файлов используя метод ```Fi1a\Config\Config::batchLoad```
и передав массив с объектами ```Fi1a\Config\Readers\ReaderInterface``` для чтения из файла и
```Fi1a\Config\Parsers\ParserInterface``` для парсинга:

```php
use Fi1a\Config\Config;
use Fi1a\Config\ConfigValuesInterface;
use Fi1a\Config\Parsers\PHPParser;
use Fi1a\Config\Readers\FileReader;

$parser = new PHPParser();

$config = Config::batchLoad([
    [
        new FileReader(__DIR__ . '/config1.php'),
        $parser,
    ],
    [
        new FileReader(__DIR__ . '/config2.php'),
        $parser,
    ],
    [
        new FileReader(__DIR__ . '/config3.php'),
        $parser,
    ],
]); // ConfigValuesInterface

$config->get('path:to:value', true);
```

## Класс со значениями

Методы   ```Fi1a\Config\Config::load``` и ```Fi1a\Config\Config::batchLoad``` возвращают
объект ```Fi1a\Config\ConfigValues``` реализующий интерфейс ```Fi1a\Collection\DataType\IPathAccess```
из пакета [fi1a/collection](https://github.com/fi1a/collection).

Данный класс позволяет получать доступ к ключам массива по пути (foo:bar:baz).

```php
use Fi1a\Config\ConfigValues;

$register = new ConfigValues(['foo' => ['bar' => ['baz' => 1], 'qux' => 2,],]);

$register->get('foo:bar:baz'); // 1
$register->get('foo:qux'); // 2
$register->get('foo:bar:baz:bat'); // null
$register->get('foo:bar:baz:bat', false); // false

$register->has('foo:bar'); // true
$register->has('foo:bar:baz'); // true
$register->has('foo:bar:baz:bat'); // false
```

## Чтение

За чтение конфигураций отвечают классы реализующие интерфейс ```Fi1a\Config\Readers\ReaderInterface```.

## Чтение конфигурационного файла

Класс ```Fi1a\Config\Readers\FileReader``` осуществляет чтение кодированной строки из файла.

| Аргумент         | Описание     |
|------------------|--------------|
| string $filePath | Путь к файлу |

```php
use Fi1a\Config\Config;
use Fi1a\Config\ConfigValuesInterface;
use Fi1a\Config\Parsers\PHPParser;
use Fi1a\Config\Readers\FileReader;

$filePath = __DIR__ . '/config.php';

$reader = new FileReader($filePath);
$parser = new PHPParser();

$config = Config::load($reader, $parser); // ConfigValuesInterface

$config->get('path:to:value', true);
$config->set('path:to:value', 'value');
```

## Чтение конфигурационных файлов из директории

Класс ```Fi1a\Config\Readers\DirectoryReader``` осуществляет чтение файлов конфигураций из переданной директории по маске.
Аргументы конструктора:

| Аргумент              | Описание               |
|-----------------------|------------------------|
| string $directoryPath | Путь до директории     |
| string $regex         | Макска для имен файлов |

```php
use Fi1a\Config\Config;
use Fi1a\Config\ConfigValuesInterface;
use Fi1a\Config\Parsers\PHPParser;
use Fi1a\Config\Readers\DirectoryReader;

$directory = __DIR__ . '/tests/Fixtures';

$reader = new DirectoryReader($directory, '/^(.+)\.config\.php$/');
$parser = new PHPParser();

$config = Config::load($reader, $parser); // ConfigValuesInterface

$config->get('path:to:value', true);
$config->set('path:to:value', 'value');
```

## Запись

За запись конфигураций отвечают классы реализующие интерфейс ```Fi1a\Config\Writers\WriterInterface```.

## Запись конфигурационного файла

Класс ```Fi1a\Config\Writers\FileWriter``` осуществляет запись кодированной строки в файла.

| Аргумент         | Описание               |
|------------------|------------------------|
| string $filePath | Путь для записи в файл |

```php
use Fi1a\Config\Config;
use Fi1a\Config\ConfigValues;
use Fi1a\Config\Parsers\PHPParser;
use Fi1a\Config\Writers\FileWriter;

$filePath = __DIR__ . '/config.php';

$writer = new FileWriter($filePath);

$parser = new PHPParser();

$config = new ConfigValues(['foo' => 'bar', 'baz' => [1, 2, 3]]);

Config::write($config, $parser, $writer); // true
```

## Кодирование

За кодирование конфигураций отвечают классы реализующие интерфейс ```Fi1a\Config\Parsers\ParserInterface```.

## Кодирование в PHP формат

Для кодирование в PHP формат следует использовать класс ```Fi1a\Config\Parsers\PHPParser```.
В конструктор  можно передать в качестве аргументов следующие значения:

| Аргумент                         | Описание                                                           |
|----------------------------------|--------------------------------------------------------------------|
| string $encoding = 'UTF-8'       | Кодировка                                                          |
| bool $useShortArraySyntax = true | Использовать короткую нотацию массивов или нет                     |
| string $indent = '4spaces'       | Определяет значение отступов ('4spaces', '1tab' или ваше значение) |

Пример:

```php
use Fi1a\Config\Config;
use Fi1a\Config\ConfigValuesInterface;
use Fi1a\Config\Parsers\PHPParser;
use Fi1a\Config\Readers\FileReader;
use Fi1a\Config\Writers\FileWriter;

$filePath = __DIR__ . '/config.php';

$reader = new FileReader($filePath);
$writer = new FileWriter($filePath);
$parser = new PHPParser('UTF-8', false, '1tab');

$config = Config::load($reader, $parser); // ConfigValuesInterface

$config->get('path:to:value', true);
$config->set('path:to:value', 'value');

Config::write($config, $parser, $writer); // true
```

## Кодирование в JSON формат

Для кодирование в JSON формат следует использовать класс ```Fi1a\Config\Parsers\JSONParser```.
В конструктор можно передать в качестве аргументов следующие значения:

| Аргумент           | Описание                                                                                                     |
|--------------------|--------------------------------------------------------------------------------------------------------------|
| ?int $depth = null | Максимальная глубина вложенности структуры, для которой будет производиться декодирование. По умолчанию: 512 |
| ?int $flags = null | Битовая маска из [констант](https://www.php.net/manual/ru/json.constants.php).                               |

Пример:

```php
use Fi1a\Config\Config;
use Fi1a\Config\ConfigValuesInterface;
use Fi1a\Config\Parsers\JSONParser;
use Fi1a\Config\Readers\FileReader;

$filePath = __DIR__ . '/config.json';

$reader = new FileReader($filePath);
$parser = new JSONParser(64, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

$config = Config::load($reader, $parser); // ConfigValuesInterface

$config->get('path:to:value', true);
$config->set('path:to:value', 'value');
```

[badge-release]: https://img.shields.io/packagist/v/fi1a/config?label=release
[badge-license]: https://img.shields.io/github/license/fi1a/config?style=flat-square
[badge-php]: https://img.shields.io/packagist/php-v/fi1a/config?style=flat-square
[badge-coverage]: https://img.shields.io/badge/coverage-100%25-green
[badge-downloads]: https://img.shields.io/packagist/dt/fi1a/config.svg?style=flat-square&colorB=mediumvioletred

[packagist]: https://packagist.org/packages/fi1a/config
[license]: https://github.com/fi1a/config/blob/master/LICENSE
[php]: https://php.net
[downloads]: https://packagist.org/packages/fi1a/config