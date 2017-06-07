<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1496791672.
 * Generated on 2017-06-06 23:27:52 by cuneyt
 */
class PropelMigration_1496791672
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

ALTER TABLE "opportunity"

  ADD "sfdc_id" VARCHAR(127),

  DROP COLUMN "stage_id",

  DROP COLUMN "name",

  DROP COLUMN "amount",

  DROP COLUMN "close_date";

ALTER TABLE "opportunity_history"

  ADD "sfdc_opportunity_id" VARCHAR(127);

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

ALTER TABLE "opportunity"

  ADD "stage_id" INTEGER,

  ADD "name" VARCHAR(255),

  ADD "amount" VARCHAR,

  ADD "close_date" TIMESTAMP,

  DROP COLUMN "sfdc_id";

ALTER TABLE "opportunity_history"

  DROP COLUMN "sfdc_opportunity_id";

COMMIT;
',
);
    }

}