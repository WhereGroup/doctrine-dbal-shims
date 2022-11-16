<?php


namespace Wheregroup\DoctrineDbalShims\Event\Listeners;


use Doctrine\DBAL\Driver\AbstractOracleDriver;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Event\Listeners\OracleSessionInit;

/**
 * Ensures DBAL-required session variables (like date format) are set on
 * Oracle connections. Unlike upstream OracleSessionInit, this listener automatically
 * checks if the connection is actually an Oracle connection. This allows mixing
 * multiple connections to different database servers, without the need to
 * preconfigure the listener to only process certain connections by name.
 */
class OnDemandOracleSessionInit extends OracleSessionInit
{
    public function postConnect(ConnectionEventArgs $args)
    {
        if ($args->getConnection()->getDriver() instanceof AbstractOracleDriver) {
            parent::postConnect($args);
        }
    }
}
