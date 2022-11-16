# v1.0.1
* Fix errors on DSN-style (e.g. `url: pgsql://host:port/dbname`) connection configurations

# v1.1.0
* Drop compatibility workaround for PostgreSQL 10 on PHP 5.x
* Require dbal >= 2.7 (for proper upstream PostgreSQL >= 10 support)
