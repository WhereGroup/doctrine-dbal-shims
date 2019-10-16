<?php


namespace Wheregroup\DoctrineDbalShims\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface AutoShimPassInterface extends CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @return boolean
     */
    public function isShimRequired(ContainerBuilder $container);
}
