<?php

namespace Eo\HoneypotBundle\Tests\Event;

use Eo\HoneypotBundle\Document\HoneypotPrey;
use Eo\HoneypotBundle\Event\BirdInCageEvent;
use PHPUnit\Framework\TestCase;

class BirdInCageEventTest extends TestCase
{
    public function testPrey()
    {
        $prey = $stub = $this->getMockForAbstractClass('Eo\HoneypotBundle\Model\HoneypotPrey');
        $event = new BirdInCageEvent($prey);

        $this->assertEquals($event->getPrey(), $prey);
    }
}
