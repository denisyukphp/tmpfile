<?php

class tmpfileTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $tmpfile = new tmpfile;

        $this->assertFileExists($tmpfile->filename);
    }

    public function testCreateWithData()
    {
        $data = $this->getTestData();
        $tmpfile = new tmpfile($data);

        $this->assertStringEqualsFile($tmpfile->filename, $data);
    }

    public function testWrite()
    {
        $data = $this->getTestData();
        $tmpfile = new tmpfile;
        $tmpfile->write($data);

        $this->assertEquals($data, file_get_contents($tmpfile));
    }

    public function testWriteWithFlags()
    {
        $tmpfile = new tmpfile('abc');
        $tmpfile->write('def', FILE_APPEND);

        $this->assertEquals('abcdef', file_get_contents($tmpfile));
    }

    public function testPuts()
    {
        $tmpfile = new tmpfile('def');
        $tmpfile->puts('ghi');

        $this->assertEquals('defghi', file_get_contents($tmpfile));
    }

    public function testRead()
    {
        $inputData = $this->getTestData();
        $tmpfile = new tmpfile($inputData);
        $outputData = $tmpfile->read();

        $this->assertEquals($inputData, $outputData);
    }

    public function testReadWithOffsetAndMaxlen()
    {
        $tmpfile = new tmpfile('Hello, world!');
        $data = $tmpfile->read(7, 5);

        $this->assertEquals('world', $data);
    }

    public function testDelete()
    {
        $tmpfile = new tmpfile;
        $tmpfile->delete();

        $this->assertFileNotExists($tmpfile->filename);
    }

    public function testStringMatch()
    {
        $tmpfile = new tmpfile;

        $this->assertEquals($tmpfile, $tmpfile->filename);
    }

    public function getTestData()
    {
        return random_bytes(1024);
    }
}