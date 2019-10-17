<?php


namespace Wheregroup\DoctrineDbalShims\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Static methods required on ShimPass.
 * PHP < 7 does not agree with the utility of abstract static methods, but allows them
 * in interfaces.
 *
 * @internal
 */
interface ShimPassInterface extends CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     * @return boolean
     */
    public static function isShimRequired(ContainerBuilder $container);

    /**
     * @param ContainerBuilder $container
     * @return void
     */
    public static function register(ContainerBuilder $container);
}
