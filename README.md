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
$tmpfile->write('abc');

// Дописать в конец
$tmpfile->write('def', FILE_APPEND);

// Способ короче
$tmpfile->puts('def');

// Прочитать весь файл
$tmpfile->read();

// Какую-то часть
$tmpfile->read(7, 5);

// Удалить файл
$tmpfile->delete();

/* ... */

// Передать URI в объект
new SplFileInfo($tmpfile);

// Переместить в другую папку
rename($tmpfile, __DIR__ . '/picture.jpg');

// Проверить на наличие
file_exists($tmpfile);
```
![Favicon](https://hsto.org/files/e9b/a97/31d/e9ba9731d607484cb3abfdd51fd494d5.png) [Александр Денисюк](https://denisyuk.by), 2017
