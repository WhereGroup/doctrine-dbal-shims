<?php


namespace Wheregroup\DoctrineDbalShims\DependencyInjection\Compiler;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class AddOracleSesssionInitPass extends ShimPass
{
    /** @var FileLocator */
    protected $fileLocator;

    public function __construct()
    {
        $this->fileLocator = new FileLocator(realpath(__DIR__ . '/../../Resources/config')); ///../Resources/config'));
    }

    public static function isShimRequired(ContainerBuilder $container)
    {
        // Can't hurt
        return true;
    }

    public function process(ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, $this->fileLocator);
        $loader->import('oracle-listener-init-session-vars.xml');
    }

    public static function register(ContainerBuilder $container)
    {
        /**
         * Register for execution BEFORE doctrine event listeners are collected and
         * become immutable.
         * @see \Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterEventListenersAndSubscribersPass::addTaggedSubscribers()
         * @see \Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterEventListenersAndSubscribersPass::addTaggedListeners()
         */
        $insertBeforeClass = 'Symfony\Bridge\Doctrine\DependencyInjection\CompilerPass\RegisterEventListenersAndSubscribersPass';
        if (!static::registerBefore($container, $insertBeforeClass)) {
            parent::register($container);
        }
    }
}
