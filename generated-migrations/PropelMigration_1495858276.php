<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495858276.
 * Generated on 2017-05-27 04:11:16 by cuneyt
 */
class PropelMigration_1495858276
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

  ALTER COLUMN "sfdc_account_id" TYPE VARCHAR(127);

ALTER TABLE "contact"

  ALTER COLUMN "sfdc_contact_id" TYPE VARCHAR(127),

  ADD "sfdc_account_id" VARCHAR(127);

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

  ALTER COLUMN "sfdc_account_id" TYPE INTEGER
   USING CASE WHEN trim(sfdc_account_id) SIMILAR TO \'[0-9]+\'
        THEN CAST(trim(sfdc_account_id) AS integer)
        ELSE NULL END;

ALTER TABLE "contact"

  ALTER COLUMN "sfdc_contact_id" TYPE INTEGER
   USING CASE WHEN trim(sfdc_contact_id) SIMILAR TO \'[0-9]+\'
        THEN CAST(trim(sfdc_contact_id) AS integer)
        ELSE NULL END,

  DROP COLUMN "sfdc_account_id";

COMMIT;
',
);
    }

}