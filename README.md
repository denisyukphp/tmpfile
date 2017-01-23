# tmpfile
Класс для работы с временным файлом на PHP и альтернатива стандартной функции `tmpfile()`. Подробнее об устройстве класса написано на [Хабре](https://habrahabr.ru/post/320078/). В свой проект можно подключить через Composer:

```
composer require denisyukphp/tmpfile
```

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

&copy; [Александр Денисюк](https://denisyuk.by), 2017
