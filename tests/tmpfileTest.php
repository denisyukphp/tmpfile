<?php

use PHPUnit\Framework\TestCase;
use tmpfile\tmpfile;

class tmpfileTest extends TestCase
{
    public function testInit(): void
    {
        $tmpfile = new tmpfile();

        $this->assertFileExists($tmpfile);
    }
}