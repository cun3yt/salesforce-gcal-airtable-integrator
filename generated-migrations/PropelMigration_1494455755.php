<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1494455755.
 * Generated on 2017-05-10 22:35:55 by cuneyt
 */
class PropelMigration_1494455755
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

ALTER TABLE "meeting" RENAME COLUMN "created" TO "created_at";


ALTER TABLE "meeting" RENAME COLUMN "updated" TO "updated_at";

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

ALTER TABLE "meeting" RENAME COLUMN "created_at" TO "created";


ALTER TABLE "meeting" RENAME COLUMN "updated_at" TO "updated";

COMMIT;
',
);
    }

}