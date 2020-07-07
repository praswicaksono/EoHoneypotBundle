<?php

namespace Eo\HoneypotBundle\Tests\Form\Type;

use Eo\HoneypotBundle\Events;
use Eo\HoneypotBundle\Form\Type\HoneypotType;
use Eo\HoneypotBundle\Model\HoneypotPrey;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;

class HoneypotTypeTest extends TestCase
{
    public $triggered;

    protected function setUp(): void
    {
        $this->triggered = false;
    }

    public function buildFormDataProvider()
    {
        return array(
            array(null,         false),
            array('',           false),
            array('Stupid bot', true),
        );
    }

    /**
     * @dataProvider buildFormDataProvider
     */
    public function testBuildForm($data, $trigger)
    {
        $honeypotManager = $this->getMockBuilder('Eo\HoneypotBundle\Manager\HoneypotManager')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $honeypotManager
            ->expects($this->any())
            ->method('createNew')
            ->will($this->returnValue(new HoneypotPrey()))
        ;

        $requestStack = $this->getMockBuilder('Symfony\Component\HttpFoundation\RequestStack')
            ->disableOriginalConstructor()
            ->setMethods(array('getCurrentRequest'))
            ->getMock()
        ;

        $requestStack
            ->expects($this->any())
            ->method('getCurrentRequest')
            ->will($this->returnValue(new Request()))
        ;

        $that = $this;

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addListener(Events::BIRD_IN_CAGE, function () use ($that) {
            $that->triggered = true;
        });

        $factory = $this->getMockForAbstractClass('Symfony\Component\Form\FormFactoryInterface');

        $builder = new FormBuilder('honeypot', null, $eventDispatcher, $factory);

        $type = new HoneypotType($requestStack, $honeypotManager, $eventDispatcher);
        $type->buildForm($builder, array('causesError' => false));

        $parent = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $form = $builder->getForm();
        $form->setParent($parent);

        $eventDispatcher->dispatch(new FormEvent($form, $data), FormEvents::PRE_SUBMIT);

        $this->assertEquals($this->triggered, $trigger);
    }
}
