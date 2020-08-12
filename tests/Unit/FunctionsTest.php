<?php

declare(strict_types=1);

namespace Tests\Unit;

use Phalcon\Security;

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

    public function testRemoveFileSuffix(): void
    {
        $fileA = "myFile.mp4";
        $fileB = "myFile.mp4.mp4";

        $fileAExpected = "myFile";
        $fileBExpected = "myFile.mp4";

        $this->assertTrue(removeFileSuffix($fileA) === $fileAExpected);
        $this->assertTrue(removeFileSuffix($fileB) === $fileBExpected);
    }

    public function testAuthentication(): void
    {
        $password = 'Phalcon';

        $security = new Security();
        $hashed = $security->hash('Phalcon');

        $authenticated = $security->checkHash($password, $hashed);
        $this->assertTrue($authenticated);
    }

    public function testReplaceStringTokens(): void
    {
        $string1 = "{String1}";
        $string2 = "{String2}";

        $string1New = "Value1";
        $string2New = "Value2";

        $stringUnderTest = "This is a test string for string substitution. $string1,  $string2";
        $stringUnderTestNewExpected = "This is a test string for string substitution. $string1New,  $string2New";

        $stringUnderTestNew = replaceStringTokens($stringUnderTest,
            array($string1, $string2),
            array($string1New, $string2New));

        $this->assertTrue($stringUnderTestNew === $stringUnderTestNewExpected,
            "Expected strings to be equal, instead got the following for string under test [$stringUnderTestNew] and 
        expected string [$stringUnderTestNewExpected].");
    }

    public function testSendHTMLEmail(): void
    {
        $emailTokens = ["{{UserName}}" => "Dale Foley",
                        "{{LinkTimeout}}" => "10 minutes",
                        "{{ResetPasswordURL}}" => "https://videoplayer.com/noncehere"];

        $this->assertTrue(sendHTMLEmail("noreplay@videoplayer.com",
            "webadmin@videoplayer.com",
            "Test Email",
            $emailTokens,
            APP_PATH . "\\email_templates\\password_reset.html"));
    }
}
