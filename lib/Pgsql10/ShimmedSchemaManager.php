<?php


namespace Wheregroup\DoctrineDbalShims\Pgsql10;


use Doctrine\DBAL\Schema\PostgreSqlSchemaManager;
use Doctrine\DBAL\Schema\Sequence;

/**
 * SchemaManager with (limited) PostgreSQL 10 support on doctrine/dbal 2.5 / 2.6.
 * Fixes BC break in sequence access encountered when running e.g. doctrine:schema:update
 * PostgreSQL >= 10 must use pg_sequences for sequence metadata. Selecting from
 * the sequence name is no longer valid usage.
 * @link https://github.com/doctrine/dbal/issues/2868
 * @link https://www.postgresql.org/docs/10/release-10.html#id-1.11.6.13.4
 *
 * NOTE that this class is INCOMPATIBLE with PostgreSQL server 9.x.
 * The decision to either use the default DBAL class or this one is made in
 * @see ShimmedDriver::getSchemaManager.
 */
class ShimmedSchemaManager extends PostgreSqlSchemaManager
{
    protected function _getPortableSequenceDefinition($sequence)
    {
        $sql = 'SELECT min_value, increment_by FROM pg_sequences WHERE schemaname = :schemaName AND sequencename = :sequenceName';
        $params = array(
            ':sequenceName' => $sequence['relname'],
            ':schemaName' => $sequence['schemaname'],
        );
        $data = $this->_conn->fetchAll($sql, $params);

        if ($sequence['schemaname'] != 'public') {
            $sequenceName = $sequence['schemaname'] . "." . $sequence['relname'];
        } else {
            $sequenceName = $sequence['relname'];
        }
        return new Sequence($sequenceName, $data[0]['increment_by'], $data[0]['min_value']);
    }
}
