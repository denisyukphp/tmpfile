# tmpfile
Класс для работы с временным файлом на PHP как альтернатива стандартной функции `tmpfile()`. Подробнее об устройстве класса написано на [Хабре](https://habrahabr.ru/post/320078/). В свой проект можно подключить через Composer:

```
composer require denisyukphp/tmpfile
```

Класс поддерживает стандартный набор CRUD-операций. Методы для записи и чтения являются обёртками для `file_put_contents()` и `file_get_contents()`. Для открытия временного файла в потоке используйте `fopen()`. Ниже приведены возможные варианты применения в боевых условиях:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

// Создать временный файл
$tmpfile = new tmpfile;

// Сразу с контентом
$tmpfile = new tmpfile('Hello, world!');

/* ... */

// Записать в файл
$tmpfile->write('abc'); # file_put_contents($tmpfile, 'abc');

// Дописать в конец файла
$tmpfile->write('def', FILE_APPEND); # file_put_contents($tmpfile, 'def', FILE_APPEND);

// Короткий способ
$tmpfile->puts('ghi'); # file_put_contents($tmpfile, 'ghi', FILE_APPEND);

// Прочитать весь файл
$tmpfile->read(); # file_get_contents($tmpfile);

// Какую-то часть файла
$tmpfile->read(7, 5); # file_get_contents($tmpfile, false, null, 7, 5);

// Удалить файл
$tmpfile->delete(); # unlink($tmpfile);

/* ... */

// Передать в объект
new SplFileInfo($tmpfile);

// Переместить в другую папку
rename($tmpfile, __DIR__ . '/picture.jpg');

// Проверить на наличие
file_exists($tmpfile);
```

[![Favicon](https://hsto.org/files/e9b/a97/31d/e9ba9731d607484cb3abfdd51fd494d5.png)](https://denisyuk.by) [Александр Денисюк](https://denisyuk.by), 2017
