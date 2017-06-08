<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1496881521.
 * Generated on 2017-06-08 00:25:21 by cuneyt
 */
class PropelMigration_1496881521
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

  DROP COLUMN "sfdc_opportunity_id";

ALTER TABLE "opportunity_history" RENAME COLUMN "account_name" TO "account_sfdc_id";


ALTER TABLE "opportunity_history" RENAME COLUMN "opportunity_name" TO "name";


ALTER TABLE "opportunity_history" RENAME COLUMN "opportunity_owner" TO "owner_id";

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

  ADD "sfdc_opportunity_id" VARCHAR(127);

ALTER TABLE "opportunity_history" RENAME COLUMN "account_sfdc_id" TO "account_name";


ALTER TABLE "opportunity_history" RENAME COLUMN "name" TO "opportunity_name";


ALTER TABLE "opportunity_history" RENAME COLUMN "owner_id" TO "opportunity_owner";

COMMIT;
',
);
    }

}