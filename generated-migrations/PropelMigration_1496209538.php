<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1496209538.
 * Generated on 2017-05-31 05:45:38 by cuneyt
 */
class PropelMigration_1496209538
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

  ADD "sfdc_last_check_time" TIMESTAMP;

ALTER TABLE "contact"

  ADD "sfdc_last_check_time" TIMESTAMP;

ALTER TABLE "opportunity"

  ADD "sfdc_last_check_time" TIMESTAMP;

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

  DROP COLUMN "sfdc_last_check_time";

ALTER TABLE "contact"

  DROP COLUMN "sfdc_last_check_time";

ALTER TABLE "opportunity"

  DROP COLUMN "sfdc_last_check_time";

COMMIT;
',
);
    }

}