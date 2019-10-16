<?php


namespace Wheregroup\DoctrineDbalShims\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Single compiler pass that automatically executes all applicable shim pass logic.
 */
class AutoShimAllPass implements AutoShimPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach ($this->getAllShimPasses($container) as $pass) {
            // Invariantly add class file modification time to container rebuild criteria
            $container->addObjectResource($pass);
            if ($pass->isShimRequired($container)) {
                $pass->process($container);
            }
        }
    }

    public function isShimRequired(ContainerBuilder $container)
    {
        foreach ($this->getAllShimPasses($container) as $pass) {
            if ($pass->isShimRequired($container)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param ContainerBuilder $container
     * @return AutoShimPassInterface[]
     */
    public function getAllShimPasses(ContainerBuilder $container)
    {
        return array(
            new ShimPgsql10DriverPass(),
        );
    }
}
