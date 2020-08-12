<?php

declare(strict_types=1);

namespace Tests\Unit;

use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Incubator\Test\PHPUnit\UnitTestCase;
use PHPUnit\Framework\IncompleteTestError;

require_once '..\..\public\bootstrap.php';

abstract class AbstractUnitTest extends UnitTestCase
{
    private bool $loaded = false;

    protected function setUp(): void
    {
        parent::setUp();

        $di = new FactoryDefault();

        Di::reset();
        Di::setDefault($di);

        $this->loaded = true;
    }

    public function __destruct()
    {
        //This is false if we have 2 or more unit tests defined.. Need to figure out why.
        //Commenting out IncompleteTestError until this is resolved.
//        if (!$this->loaded)
//        {
//            throw new IncompleteTestError("Please run parent::setUp().");
//        }
    }
}
