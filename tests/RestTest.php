<?php
declare(strict_types=1);

use TrafficForecast\Rest;
use PHPUnit\Framework\TestCase;

final class RestTest extends TestCase
{
    public function testCanInstantiate() : void
    {
        $i = new Rest();
        self::assertTrue($i instanceof Rest);
    }
}
