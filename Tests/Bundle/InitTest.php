<?php

namespace Eo\HoneypotBundle\Tests\Bundle;

use Eo\HoneypotBundle\EoHoneypotBundle;
use Eo\HoneypotBundle\EventListener\RedirectListener;
use Eo\HoneypotBundle\Form\Type\HoneypotType;
use Eo\HoneypotBundle\Manager\HoneypotManager;
use Nyholm\BundleTest\BaseBundleTestCase;
use Nyholm\BundleTest\CompilerPass\PublicServicePass;

class InitTest extends BaseBundleTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Make all services public
        $this->addCompilerPass(new PublicServicePass());
    }

    protected function getBundleClass()
    {
        return EoHoneypotBundle::class;
    }

    public function testInitBundle()
    {
        $this->bootKernel();

        $container = $this->getContainer();

        $this->assertTrue($container->has('eo_honeypot.manager'));
        $service = $container->get('eo_honeypot.manager');
        $this->assertInstanceOf(HoneypotManager::class, $service);

        $this->assertTrue($container->has('eo_honeypot.form.type.honeypot'));
        $service = $container->get('eo_honeypot.form.type.honeypot');
        $this->assertInstanceOf(HoneypotType::class, $service);
    }
}