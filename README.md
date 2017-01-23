# tmpfile
Класс для работы с временным файлом на PHP. Альтернатива стандартной функции tmpfile().

```php
<?php

require __DIR__ . '/vendor/autoload.php';

// Создать временный файл
$tmpfile = new tmpfile;

// Создать временный файл с контентом
$tmpfile = new tmpfile('Hello, world!');
```
