<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1493488615.
 * Generated on 2017-04-29 17:56:55 by cuneyt
 */
class PropelMigration_1493488615
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

ALTER TABLE "customer_contact"

  ADD "customer_id" INTEGER;

ALTER TABLE "customer_contact" ADD CONSTRAINT "customer_contact_fk_7e8f3e"
    FOREIGN KEY ("customer_id")
    REFERENCES "customer" ("id");

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

ALTER TABLE "customer_contact" DROP CONSTRAINT "customer_contact_fk_7e8f3e";

ALTER TABLE "customer_contact"

  DROP COLUMN "customer_id";

COMMIT;
',
);
    }

}