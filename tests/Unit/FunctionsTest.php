<?php

declare(strict_types=1);

namespace Tests\Unit;

class FunctionsTest extends AbstractUnitTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateFileOrDirectoryIfNotExists(): void
    {
        $pathSourceFileTestA = APP_PATH . '/test1.txt';
        $pathSourceDirectoryTestA = APP_PATH . 'test1';

        $isFileCreated = createFileOrDirectoryIfNotExists($pathSourceFileTestA);

        $this->assertTrue(unlink($pathSourceFileTestA));
        $this->assertTrue($isFileCreated);

        $isDirectoryCreated = createFileOrDirectoryIfNotExists($pathSourceDirectoryTestA);

        $this->assertTrue(rmdir($pathSourceDirectoryTestA));
        $this->assertTrue($isDirectoryCreated);
    }

    public function testStartsWith(): void
    {
        $testInput = "testString";
        $this->assertTrue(startsWith($testInput, "test"));
    }

    public function testReplace(): void
    {
        $fileA = "myFile.mp4";
        $fileB = "myFile.mp4.mp4";

        $fileAExpected = "myFile";
        $fileBExpected = "myFile.mp4";

        $this->assertTrue(removeFileSuffix($fileA) === $fileAExpected);
        $this->assertTrue(removeFileSuffix($fileB) === $fileBExpected);
    }
}
