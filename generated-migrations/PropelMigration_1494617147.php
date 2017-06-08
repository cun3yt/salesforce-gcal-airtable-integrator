<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1494617147.
 * Generated on 2017-05-12 19:25:47 by cuneyt
 */
class PropelMigration_1494617147
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

DROP TABLE IF EXISTS "content" CASCADE;

CREATE TABLE "contact"
(
    "id" serial NOT NULL,
    "email" VARCHAR(255),
    "full_name" VARCHAR(255),
    "account_id" INTEGER,
    "sfdc_contact_id" INTEGER,
    "sfdc_contact_name" VARCHAR(255),
    PRIMARY KEY ("id")
);

CREATE TABLE "book"
(
    "id" serial NOT NULL,
    "title" VARCHAR(100),
    "class_key" INTEGER,
    PRIMARY KEY ("id")
);

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

DROP TABLE IF EXISTS "contact" CASCADE;

DROP TABLE IF EXISTS "book" CASCADE;

CREATE TABLE "content"
(
    "id" serial NOT NULL,
    "title" VARCHAR(100),
    "category_id" INTEGER,
    "descendant_class" VARCHAR(100),
    PRIMARY KEY ("id")
);

COMMIT;
',
);
    }

}