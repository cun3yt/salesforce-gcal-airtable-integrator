<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1496348671.
 * Generated on 2017-06-01 20:24:31 by cuneyt
 */
class PropelMigration_1496348671
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

ALTER TABLE "account_history" DROP CONSTRAINT "account_history_fk_474870";

COMMIT;
',
);
    }

}