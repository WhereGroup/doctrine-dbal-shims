<?php


namespace Wheregroup\DoctrineDbalShims\DependencyInjection\Compiler;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class AddOracleSesssionInitPass implements AutoShimPassInterface
{
    /** @var FileLocator */
    protected $fileLocator;

    public function __construct()
    {
        $this->fileLocator = new FileLocator(realpath(__DIR__ . '/../../Resources/config')); ///../Resources/config'));
    }

    public function isShimRequired(ContainerBuilder $container)
    {
        // Can't hurt
        return true;
    }

    public function process(ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, $this->fileLocator);
        $loader->import('oracle-listener-init-session-vars.xml');
    }
}
