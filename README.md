# tmpfile
Класс для работы с временным файлом на PHP. Альтернатива стандартной функции tmpfile(). Поддерживает CRUD-команды. Временный файл удаляется по завершению выполнения скрипта.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

// Создать временный файл
$tmpfile = new tmpfile;

// Создать временный файл с контентом
$tmpfile = new tmpfile('Hello, world!');

// Записать в файл
$tmpfile->write('Lorem '); # file_put_contents($tmpfile, 'Lorem ');

// Записать в конец файла
$tmpfile->write('ipsum.', FILE_APPEND); # file_put_contents($tmpfile, 'ipsum.', FILE_APPEND);

// Короткий способ записать в конец файла
$tmpfile->puts('ipsum.'); # file_put_contents($tmpfile, 'ipsum.', FILE_APPEND);

// Прочитать весь файл в строку
$tmpfile->read(); # file_get_contents($tmpfile);

// Прочитать часть файла
$tmpfile->read(0, 5); # file_get_contents($tmpfile, false, null, 0, 5);

// Досрочно удалить файл
$tmpfile->delete(); # unlink($tmpfile);

/* ... */

new SplFileInfo($tmpfile);
rename($tmpfile, __DIR__ . '/picture.jpg');
file_exists($tmpfile);
```
