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
        if ($this->detectPostgreSQL($params)) {
            unset($params['driver']);
            $params['driverClass'] = 'Wheregroup\DoctrineDbalShims\Pgsql10\ShimmedDriver';
            if (isset($params['url']) && false !== strpos($params['url'], ':')) {
                // Remove scheme. For non-empty url schemes, DriverManager would just reset
                // the driverClass parameter back to default
                $params['url'] = substr($params['url'], strpos($params['url'], ':') + 1);
            }
        }
        return parent::createConnection($params, $config, $eventManager, $mappingTypes);
    }

    /**
     * @param mixed[] $connectionParams
     * @return bool
     */
    protected function detectPostgreSQL(array $connectionParams)
    {
        if (isset($connectionParams['url'])) {
            $scheme = \parse_url($connectionParams['url'], PHP_URL_SCHEME);
            /** @see DriverManager::$driverSchemeAliases */
            return \in_array($scheme, array(
                'pdo_pgsql',
                'postgres',
                'postgresql' => 'pdo_pgsql',
                'pgsql',
                'pdo-pgsql',    /** @see DriverManager::parseDatabaseUrlScheme for dash-underscore equivalence */
            ));
        }
        /** @see DriverManager::$_driverMap */
        if (isset($connectionParams['driver'])) {
            return $connectionParams['driver'] === 'pdo_pgsql';
        } elseif (isset($connectionParams['driverClass'])) {
            return $connectionParams['driverClass'] === 'Doctrine\DBAL\Driver\PDOPgSql\Driver';
        }
        return false;
    }
}
