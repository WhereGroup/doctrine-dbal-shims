<?php


namespace Wheregroup\DoctrineDbalShims\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class ShimPass implements AutoShimPassInterface
{
    public static function register(ContainerBuilder $container)
    {
        $container->addCompilerPass(new static());
    }

    /**
     * Utility method to register a compiler pass instance of the current class
     * before a certain other pass (by class name).
     * Performs no action unless a compiler pass of given $className was already
     * added to the builder.
     *
     * @param ContainerBuilder $container
     * @param string $className
     * @return bool if pass was injected
     */
    protected static function registerBefore(ContainerBuilder $container, $className)
    {
        $passConfig = $container->getCompilerPassConfig();
        /** @var CompilerPassInterface[] $searchPasses */
        $searchPasses = $passConfig->getBeforeOptimizationPasses();
        foreach ($searchPasses as $i => $pass) {
            if (is_a($pass, $className, true)) {
                array_splice($searchPasses, $i, 0, array(new static()));
                $passConfig->setBeforeOptimizationPasses($searchPasses);
                return true;
            }
        }
        return false;
    }
}
