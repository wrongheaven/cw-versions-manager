<?php declare(strict_types=1);
namespace Codewars\Tests;

use PHPUnit\Framework\TestCase;
use Codewars\VersionManager;

class VersionManagerTest extends TestCase
{
    public function testInitialization()
    {
        try {
            $this->assertSame("0.0.1", (new VersionManager())->release());
            $this->assertSame("0.0.1", (new VersionManager(""))->release());
            $this->assertSame("1.2.3", (new VersionManager("1.2.3"))->release());
            $this->assertSame("1.2.3", (new VersionManager("1.2.3.4"))->release());
            $this->assertSame("1.2.3", (new VersionManager("1.2.3.d"))->release());
            $this->assertSame("1.0.0", (new VersionManager("1"))->release());
            $this->assertSame("1.1.0", (new VersionManager("1.1"))->release());
        }
        catch (\Exception $e) {
            $this->fail();
        }
    }

    public function testMajorReleases()
    {
        try {
            $this->assertSame("1.0.0", (new VersionManager())->major()->release());
            $this->assertSame("2.0.0", (new VersionManager("1.2.3"))->major()->release());
            $this->assertSame("3.0.0", (new VersionManager("1.2.3"))->major()->major()->release());
        }
        catch (\Exception $e) {
            $this->fail();
        }
    }

    public function testMinorReleases()
    {
        try {
            $this->assertSame("0.1.0", (new VersionManager())->minor()->release());
            $this->assertSame("1.3.0", (new VersionManager("1.2.3"))->minor()->release());
            $this->assertSame("1.1.0", (new VersionManager("1"))->minor()->release());
            $this->assertSame("4.2.0", (new VersionManager("4"))->minor()->minor()->release());
        }
        catch (\Exception $e) {
            $this->fail();
        }
    }

    public function testPatchReleases()
    {
        try {
            $this->assertSame("0.0.2", (new VersionManager())->patch()->release());
            $this->assertSame("1.2.4", (new VersionManager("1.2.3"))->patch()->release());
            $this->assertSame("4.0.2", (new VersionManager("4"))->patch()->patch()->release());
        }
        catch(\Exception $e) {
            $this->fail();
        }
    }

    public function testRollbacks()
    {
        try {
            $this->assertSame("0.0.1", (new VersionManager())->major()->rollback()->release());
            $this->assertSame("0.0.1", (new VersionManager())->minor()->rollback()->release());
            $this->assertSame("0.0.1", (new VersionManager())->patch()->rollback()->release());
            $this->assertSame("1.0.0", (new VersionManager())->major()->patch()->rollback()->release());
            $this->assertSame("1.0.0", (new VersionManager())->major()->patch()->rollback()->major()->rollback()->release());
        }
        catch (\Exception $e) {
            $this->fail();
        }
    }

    public function testSeparatedCalls()
    {
        try {
            $vm = new VersionManager("1.2.3");
            $vm->major();
            $vm->minor();
            $this->assertSame("2.1.0", $vm->release());
        }
        catch (\Exception $e) {
            $this->fail();
        }
    }

    public function testExceptions()
    {
        try {
            new VersionManager("a.b.c");
            $this->fail("Expected an Excpetion to be thrown");
        }
        catch (\Exception $e) {
            $this->assertSame("Error occured while parsing version!", $e->getMessage());
        }

        try {
            (new VersionManager())->rollback();
            $this->fail("Expected an Exception to be thrown");
        }
        catch (\Exception $e) {
            $this->assertSame("Cannot rollback!", $e->getMessage());
        }
    }
}


