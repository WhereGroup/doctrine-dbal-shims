<?php


namespace Wheregroup\DoctrineDbalShims\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;

class ShimPgsql10DriverPass implements AutoShimPassInterface
{
    public function isShimRequired(ContainerBuilder $container)
    {
        /**
         * Note: DBAL 2.7 provides full, official support for PostgreSQL 10,
         * and does so much more robustly than our minimal shim, which only
         * really attempts to work around doctrine schema update errors
         * See @link https://github.com/doctrine/dbal/releases/tag/v2.7.0
          */
        return version_compare(\Doctrine\DBAL\Version::VERSION, '2.7-dev', '<');
    }

    public function process(ContainerBuilder $container)
    {
        if (!$this->isShimRequired($container)) {
            @trigger_error("WARNING: Pgsql10 shimming no longer advisable on Doctrine DBAL " . \Doctrine\DBAL\Version::VERSION, E_USER_DEPRECATED);
        }
        $classParamName = 'doctrine.dbal.connection_factory.class';
        $expectedClass = 'Doctrine\Bundle\DoctrineBundle\ConnectionFactory';
        $classBefore = $container->getParameter($classParamName);
        if ($classBefore !== $expectedClass) {
            throw new \LogicException("Cannot override {$classParamName}; expected to see {$expectedClass}, got {$classBefore}");
        }
        $container->setParameter($classParamName, 'Wheregroup\DoctrineDbalShims\ShimmedConnectionFactory');
    }
}
