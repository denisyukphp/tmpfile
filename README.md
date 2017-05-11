# tmpfile

[![Build Status](https://travis-ci.org/denisyukphp/tmpfile.svg?branch=master)](https://travis-ci.org/denisyukphp/tmpfile) [![Total Downloads](https://poser.pugx.org/denisyukphp/tmpfile/downloads)](https://packagist.org/packages/denisyukphp/tmpfile) [![License](https://poser.pugx.org/denisyukphp/tmpfile/license)](https://packagist.org/packages/denisyukphp/tmpfile)

Класс для работы с временным файлом на PHP как альтернатива стандартной функции `tmpfile()`. Подробнее об устройстве класса написано на [Хабре](https://habrahabr.ru/post/320078/). В свой проект можно подключить через Composer:

```
composer require denisyukphp/tmpfile
```

Класс поддерживает стандартный набор CRUD-операций. Методы для записи и чтения являются обёртками для `file_put_contents()` и `file_get_contents()`. Для открытия временного файла в потоке используйте `fopen()`. Ниже приведены возможные варианты применения в боевых условиях:

```php
<?php

require 'vendor/autoload.php';

// Создать временный файл
$tmpfile = new tmpfile;

// Сразу с контентом
$tmpfile = new tmpfile('Hello, world!');

/* ... */

// Записать в файл
$tmpfile->write('abc');

// Дописать в конец
$tmpfile->write('def', FILE_APPEND);

// Способ короче
$tmpfile->puts('def');

// Прочитать весь файл
$tmpfile->read();

// Какую-то часть
$tmpfile->read(7, 5);

// Досрочно удалить
$tmpfile->delete();

/* ... */

// Работа в потоке
$fh = fopen($tmpfile, 'w+');
fputs($fh, 'Hello, world!');
fclose($fh);

/* ... */

// Передать URI в объект
new SplFileInfo($tmpfile);

// Переместить в другую папку
rename($tmpfile, __DIR__ . '/data.txt');

// Проверить на наличие
file_exists($tmpfile);
```

## Магический метод `__toString()` при `declare(strict_types=1)`

Начиная с версии *7.0*, при указании строгой типизации `declare(strict_types=1)`, имя временного файла нельзя передать используя возможности `__toString()`, т. к. принимающая функция или класс ожидают строку, а по факту мы передаём объект. В таком случае будет сгенерирована ошибка *TypeError*. Это поведение можно обойти, если сразу передать имя временного файла через свойство объекта `$tmpfile->filename`:

```php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

$tmpfile = new tmpfile;

// Uncaught TypeError: SplFileInfo::__construct() expects parameter 1 to be string, object given
new SplFileInfo($tmpfile);

// SplFileInfo Object
// (
//     [pathName:SplFileInfo:private] => home\user\temp\phpDC08.tmp
//     [fileName:SplFileInfo:private] => phpDC08.tmp
// )
new SplFileInfo($tmpfile->filename);
```

## Отслеживание открытых потоков `fopen(new tmpfile, 'r+')`

Класс `new tmpfile` не полностью повторяет функцию `tmpfile()`, т. к. при открытии потока `fopen(new tmpfile, 'r+')` временный файл *блокируется* и не будет автоматически удалён. Решить эту проблему можно, если самостоятельно отследить не закрытый поток временного файла по URI и закрыть его через `fclose()`:

```php
<?php

require 'vendor/autoload.php';

$tmpfile = new tmpfile;

$fh = fopen($tmpfile, 'r+');

/* ... */

// Ручное закрытие ресурса
fclose($fh);

/* ... */

// Автоматическое закрытие ресурса
foreach (get_resources('stream') as $resource) {

    // Отклоняем не локальные потоки
    if ( ! stream_is_local($resource) ) {
        continue;
    }

    // Получаем URI ресурса
    $uri = @stream_get_meta_data($resource)['uri'];

    // Сверяем путь временного файла с URI потока
    if ($tmpfile->filename === $uri) {
        
        // Закрываем ресурс
        fclose($resource);
        
        // Выходим из цикла
        break;
    }
}
```

Я не добавил этот код в метод `$tmpfile->delete()` в силу того, что удобнее сразу работать с `tmpfile()` для хранения временных данных в контексте решения локальных задач, где вся логика инкапсулирована в одном методе или классе. Автоматическое закрытие ресурса было бы избыточно, т. к. его можно и нужно отслеживать самостоятельно. Класс `new tmpfile` предназначен, всё же, для перемещения URI временного файла между объектами и дальнейшей работы с ним как с обычным файлом.

[![Favicon](https://hsto.org/files/e9b/a97/31d/e9ba9731d607484cb3abfdd51fd494d5.png)](https://denisyuk.by) [Александр Денисюк](https://denisyuk.by), 2017
