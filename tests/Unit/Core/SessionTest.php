<?php declare(strict_types=1);

namespace Unit\Core;

use PamutProba\Core\App\Session;
use PamutProba\Core\Utility\Input\MutableInput;

final class SessionTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (session_id())
        {
            session_destroy();
        }
    }

    private function createFakeSessionData(): array
    {
        return [
            "foo" => "bar",
            "bar" => 42
        ];
    }

    private function createFakeSession($data = null): Session
    {
        $data = $data ?? $this->createFakeSessionData();
        return Session::from($data);
    }

    public function testStart()
    {
        $this->assertTrue(Session::start());
        $this->assertFalse(Session::start());
        $this->assertFalse(Session::start());

        $this->assertSame($_SESSION, []);
    }
    
    public function testInstantiation(): void
    {
        $data = $this->createFakeSessionData();
        $session = new Session(new MutableInput($data));
        $this->assertSame($session->all(), Session::from($data)->all());

        $data["foo"] = "bar2";
        $this->assertSame($session->all(), Session::from($data)->all());
    }

    public function testUsingRealSession(): void
    {
        Session::start();
        $session = Session::from($_SESSION);
        $this->assertFalse($session->has("foo"));
        $session->set("foo", "bar");
        $this->assertTrue($session->has("foo"));
        $this->assertSame($session->all(), $_SESSION);
    }

    public function testInvalidKey(): void
    {
        $session = $this->createFakeSession();
        $this->assertNotNull($session->get("bar"));
        $this->assertNull($session->get("invalid"));
    }

    public function testDelete(): void
    {
        $session = $this->createFakeSession();
        $key = "bar";

        $this->assertNotNull($session->get($key));
        $this->assertTrue($session->delete($key));
        $this->assertNull($session->get($key));
        $this->assertFalse($session->delete($key));
    }

    public function testFlash(): void
    {
        $data = $this->createFakeSessionData();
        $session = Session::from($data);

        $session->flash("foo", 25);
        $session->flash("special", "foo");

        $this->assertSame($session->get("foo"), "bar");
        $this->assertNull($session->getFlashed("foo"));

        $this->assertTrue($session->delete("foo"));
        $this->assertTrue($session->delete("bar"));

        $this->assertNull($session->getFlashed("foo"));

        $session = Session::from($data);

        $this->assertSame($session->getFlashed("foo"), 25);
        $this->assertNull($session->getFlashed("foo"));

        $session = Session::from($data);

        $this->assertNull($session->getFlashed("foo"));
        $this->assertNull($session->getFlashed("special"));
    }

    public function testExceptionFlashSet(): void
    {
        $this->expectExceptionMessageMatches("/^(.*)You cannot flash(.*)flash method/");
        $session = $this->createFakeSession();
        $session->set("flashed", true);
    }

    public function testExceptionFlashDelete(): void
    {
        $this->expectExceptionMessageMatches("/^(.*)You cannot delete(.*)clearFlash/");
        $session = $this->createFakeSession();
        $session->delete("flashed");
    }
}