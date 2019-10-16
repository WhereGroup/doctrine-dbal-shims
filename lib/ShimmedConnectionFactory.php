<?php


namespace Wheregroup\DoctrineDbalShims;


use Doctrine\Bundle\DoctrineBundle\ConnectionFactory;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class ShimmedConnectionFactory extends ConnectionFactory
{
    /**
     * @param array $params
     * @param Configuration|null $config
     * @param EventManager|null $eventManager
     * @param array $mappingTypes
     * @return \Doctrine\DBAL\Connection
     *
     * NOTE: We use the `= array()` default initializer for $mappingTypes to remain
     *       compatible with PHP 5.3. Upstream changed to `= []` in 1.9.0, but all
     *       PHP versions >= 5.4 allow this initializer "mismatch" without issue.
     *       We can only do this because the method in question is not an interface
     *       method implementation.
     */
    public function createConnection(array $params, Configuration $config = null, EventManager $eventManager = null, array $mappingTypes = array())
    {
        // detect PostgreSQL driver code or driver class
        /** @see DriverManager::$_driverMap */
        if (isset($params['driver'])) {
            $isPgsql = $params['driver'] === 'pdo_pgsql';
        } elseif (isset($params['driverClass'])) {
            $isPgsql = $params['driverClass'] === 'Doctrine\DBAL\Driver\PDOPgSql\Driver';
        } else {
            $isPgsql = false;
        }
        if ($isPgsql) {
            unset($params['driver']);
            $params['driverClass'] = 'Wheregroup\DoctrineDbalShims\Pgsql10\ShimmedDriver';
        }

        return parent::createConnection($params, $config, $eventManager, $mappingTypes);
    }
}
