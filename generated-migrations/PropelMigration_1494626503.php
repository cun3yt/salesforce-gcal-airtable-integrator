<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1494626503.
 * Generated on 2017-05-12 22:01:43 by cuneyt
 */
class PropelMigration_1494626503
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

ALTER TABLE "book"

  ALTER COLUMN "class_key" TYPE VARCHAR(32);

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

ALTER TABLE "book"

  ALTER COLUMN "class_key" TYPE INTEGER
   USING CASE WHEN trim(class_key) SIMILAR TO \'[0-9]+\'
        THEN CAST(trim(class_key) AS integer)
        ELSE NULL END;

COMMIT;
',
);
    }

}