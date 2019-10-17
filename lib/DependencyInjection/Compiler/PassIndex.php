<?php


namespace Wheregroup\DoctrineDbalShims\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Index of available compiler passes, plus utility method to register all
 * applicable passes.
 */
class PassIndex
{
    /**
     * @param ContainerBuilder $container
     * @return ShimPass[]
     */
    public static function getAllPasses(ContainerBuilder $container)
    {
        return array(
            new ShimPgsql10DriverPass(),
            new AddOracleSesssionInitPass(),
        );
    }

    public static function autoRegisterAll(ContainerBuilder $container)
    {
        foreach (static::getAllPasses($container) as $pass) {
            $pass->autoRegister($container);
        }
    }
}
