<?php

namespace Sculpin\Bundle\AsseticBundle;

use sculpin\Sculpin;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Assetic\Factory\AssetFactory;
use Assetic\Extension\Twig\AsseticExtension;

/**
 * Assetic integration.
 */
class AsseticBundle extends Bundle implements EventSubscriberInterface
{
    protected $sculpin;
    protected $configuration;

    public function build(ContainerBuilder $container)
    {
        $this->sculpin = $container->get('sculpin');
        $this->configuration = $container->get('sculpin.configuration');
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Sculpin::EVENT_BEFORE_RUN => 'eventBeforeRun'
        );
    }

    public function eventBeforeRun()
    {
        $twig = $sculpin->formatter('twig')->twig();
        $source = $this->configuration->getPath('source_dir');
        $output = $this->configuration->getPath('output_dir');
        $factory = new AssetFactory($source, true);
        $twig->addExtension(new AsseticExtension($factory));
    }
}
