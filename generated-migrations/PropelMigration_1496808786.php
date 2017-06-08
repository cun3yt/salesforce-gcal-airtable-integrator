<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1496808786.
 * Generated on 2017-06-07 04:13:06 by cuneyt
 */
class PropelMigration_1496808786
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

CREATE TABLE "account_history"
(
    "id" serial NOT NULL,
    "account_id" INTEGER,
    "name" VARCHAR(127),
    "num_employees" INTEGER,
    "arr" VARCHAR,
    "website" VARCHAR(255),
    "annual_revenue" VARCHAR(63),
    "industry" VARCHAR(63),
    "type" VARCHAR(63),
    "billing_latitude" VARCHAR(63),
    "billing_longitude" VARCHAR(63),
    "billing_postal_code" VARCHAR(63),
    "billing_state" VARCHAR(63),
    "billing_cycle_id" INTEGER,
    "billing_city" VARCHAR(255),
    "billing_street" VARCHAR(255),
    "billing_country" VARCHAR(255),
    "last_activity_date" DATE,
    "owner_id" VARCHAR(127),
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

ALTER TABLE "account_history" ADD CONSTRAINT "account_history_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id");

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

DROP TABLE IF EXISTS "account_history" CASCADE;

COMMIT;
',
);
    }

}