<?php


namespace Wheregroup\DoctrineDbalShims\Pgsql10;


use Doctrine\DBAL\Driver\PDOPgSql\Driver;
use Wheregroup\DoctrineDbalShims\DependencyInjection\Compiler\AutoShimAllPass;

/**
 * Extension of Doctrine DBAL PostgreSQL driver that integrates the
 * (minimally) extended ShimmedSchemaManager ONLY IF the detected PostgreSQL
 * server version is >= 10 AND we are running on a DBAL version pre 2.7, before
 * full PostgreSQL 10 support was added.
 */
class ShimmedDriver extends Driver
{
    public function getSchemaManager(\Doctrine\DBAL\Connection $conn)
    {
        /** @var \Doctrine\DBAL\Driver\PDOConnection $wrappedConnection */
        $wrappedConnection = $conn->getWrappedConnection();
        $major = intval(preg_replace('#[^\d].*$#', '', $wrappedConnection->getServerVersion()));
        /**
         * DBAL 2.7 introduced full support for PostgreSQL 10. We do not need to act
         * on >= 2.7. In fact, this entire method should not execute on >= 2.7, because this
         * Driver replacement class should not have been registered.
         * @see AutoShimAllPass::process takes care of that
         * We (re-)check this condition here anyway, to support other means of integration.
         */
        if (version_compare(\Doctrine\DBAL\Version::VERSION, '2.7-dev', '<') && $major >= 10) {
            return new ShimmedSchemaManager($conn);
        } else {
            return parent::getSchemaManager($conn);
        }
    }
}
