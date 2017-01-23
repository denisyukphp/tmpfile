# tmpfile
Класс для работы с временным файлом на PHP. Альтернатива стандартной функции tmpfile().
Подробнее написано на [Хабре](https://habrahabr.ru/post/320078/).

```php
<?php

require __DIR__ . '/vendor/autoload.php';

// Создать временный файл
$tmpfile = new tmpfile;

// Сразу с контентом
$tmpfile = new tmpfile('Hello, world!');

/* ... */

// Передать в объект
new SplFileInfo($tmpfile);

// Переместить в другую папку
rename($tmpfile, __DIR__ . '/picture.jpg');

// Проверить на наличие
file_exists($tmpfile);
```
