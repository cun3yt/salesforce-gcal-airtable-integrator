<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495496819.
 * Generated on 2017-05-22 23:46:59 by cuneyt
 */
class PropelMigration_1495496819
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

ALTER TABLE "meeting"

  ADD "client_calendar_user_id" INTEGER;

ALTER TABLE "meeting" ADD CONSTRAINT "meeting_fk_c1d8a5"
    FOREIGN KEY ("client_calendar_user_id")
    REFERENCES "client_calendar_user" ("id");

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

ALTER TABLE "meeting" DROP CONSTRAINT "meeting_fk_c1d8a5";

ALTER TABLE "meeting"

  DROP COLUMN "client_calendar_user_id";

COMMIT;
',
);
    }

}