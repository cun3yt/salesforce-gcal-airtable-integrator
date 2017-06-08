<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495752184.
 * Generated on 2017-05-25 22:43:04 by cuneyt
 */
class PropelMigration_1495752184
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

ALTER TABLE "contact"

  ADD "client_id" INTEGER;

ALTER TABLE "contact" ADD CONSTRAINT "contact_fk_90166c"
    FOREIGN KEY ("client_id")
    REFERENCES "client" ("id");

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

ALTER TABLE "contact" DROP CONSTRAINT "contact_fk_90166c";

ALTER TABLE "contact"

  DROP COLUMN "client_id";

COMMIT;
',
);
    }

}