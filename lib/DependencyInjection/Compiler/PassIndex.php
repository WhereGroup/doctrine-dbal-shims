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
     * @return ShimPass[]
     */
    public static function getAllPasses()
    {
        return array(
            new AddOracleSesssionInitPass(),
        );
    }

    public static function autoRegisterAll(ContainerBuilder $container)
    {
        foreach (static::getAllPasses() as $pass) {
            $pass->autoRegister($container);
        }
    }
}
