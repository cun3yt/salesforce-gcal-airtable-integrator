<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1496658001.
 * Generated on 2017-06-05 10:20:01 by cuneyt
 */
class PropelMigration_1496658001
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
BEGIN;

ALTER TABLE "account"

  DROP COLUMN "website";

ALTER TABLE "account_history"

  ADD "website" VARCHAR(255),

  ADD "name" VARCHAR(255),

  ADD "annual_revenue" VARCHAR(63),

  ADD "industry" VARCHAR(63),

  ADD "type" VARCHAR(63),

  ADD "billing_latitude" VARCHAR(63),

  ADD "billing_longitude" VARCHAR(63),

  ADD "billing_postal_code" VARCHAR(63),

  ADD "billing_state" VARCHAR(63),

  ADD "billing_street" VARCHAR(255),

  ADD "billing_country" VARCHAR(255),

  ADD "last_activity_date" DATE;

COMMIT;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
BEGIN;

ALTER TABLE "account"

  ADD "website" VARCHAR(255);

ALTER TABLE "account_history"

  DROP COLUMN "website",

  DROP COLUMN "name",

  DROP COLUMN "annual_revenue",

  DROP COLUMN "industry",

  DROP COLUMN "type",

  DROP COLUMN "billing_latitude",

  DROP COLUMN "billing_longitude",

  DROP COLUMN "billing_postal_code",

  DROP COLUMN "billing_state",

  DROP COLUMN "billing_street",

  DROP COLUMN "billing_country",

  DROP COLUMN "last_activity_date";

COMMIT;
',
);
    }

}