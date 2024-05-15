<?php declare(strict_types=1);

namespace Unit\Core\Utility\Input;

use PamutProba\Core\Utility\Input\ImmutableInput;

final class RealImmutableInput extends ImmutableInput
{
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->data = unserialize(serialize($data));
    }
}

class ImmutableInputTest extends \PHPUnit\Framework\TestCase
{
    public function testFunctionality(): void
    {
        $date = new \DateTime();
        $data = [
            "foo" => "bar",
            "bar" => 12,
            "date" => $date,
            "input" => new ImmutableInput(["a" => "b"])
        ];
        $input = new ImmutableInput($data);
        $this->assertTrue($input->has("foo"));
        $this->assertTrue($input->has("bar"));
        $this->assertTrue($input->has("date"));
        $this->assertFalse($input->has("invalid"));

        $this->assertSame($input->get("foo"), "bar");
        $this->assertSame($input->get("bar"), 12);
        $this->assertSame($input->get("date"), $date);

        $this->assertSame($input->all(), $data);
        $this->assertNotSame($input, new ImmutableInput($data));
        $this->assertEquals($input, new ImmutableInput($data));

        $data["foo"] = "bar2";
        $this->assertNotSame($input->all(), $data);

        // Nem igazi immutable adatszerkezet...
        $this->assertSame(
            (new ImmutableInput($data))->get("date"),
            (new ImmutableInput($data))->get("date")
        );
        // ... de ha készítünk hozzá egy deep-copy eljárást, akkor az lenne
        $this->assertNotSame(
            (new RealImmutableInput($data))->get("date"),
            (new RealImmutableInput($data))->get("date")
        );
    }

    public function testGetInvalidKey(): void
    {
        $this->expectExceptionMessageMatches("/(.*)Missing key(.*)\[test\]/");

        $input = new ImmutableInput([]);
        $input->get("test");
    }
}