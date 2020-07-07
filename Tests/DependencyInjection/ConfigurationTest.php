<?php

namespace Eo\HoneypotBundle\Tests\DependencyInjection;

use Eo\HoneypotBundle\DependencyInjection\Configuration;
use Eo\HoneypotBundle\DependencyInjection\EoHoneypotExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new EoHoneypotExtension()
        ];
    }

    public function testDefaultConfig()
    {
        $this->load();

        $this->assertContainerBuilderHasParameter('eo_honeypot.options', [
            'redirect' => array(
                'enabled' => false,
                'url' => null,
                'route' => null,
                'route_parameters' => array(),
            ),
            'storage' => array(
                'database' => array(
                    'enabled' => false,
                    'class' => 'ApplicationEoHoneypotBundle:HoneypotPrey',
                    'driver' => 'mongodb'
                ),
                'file' => array(
                    'enabled' => false,
                    'output' => '/var/log/honeypot.log'
                )
            )
        ]);
    }
}
