<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1494461226.
 * Generated on 2017-05-11 00:07:06 by cuneyt
 */
class PropelMigration_1494461226
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

DROP TABLE IF EXISTS "account_has_contact" CASCADE;

ALTER TABLE "meeting" DROP CONSTRAINT "meeting_fk_103f5d";

ALTER TABLE "meeting" DROP CONSTRAINT "meeting_fk_103f5e";

ALTER TABLE "meeting" ADD CONSTRAINT "meeting_fk_ea2144"
    FOREIGN KEY ("event_creator_id")
    REFERENCES "meeting_attendee" ("id");

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

CREATE TABLE "account_has_contact"
(
    "id" serial NOT NULL,
    "account_id" INTEGER,
    "contact_id" INTEGER,
    PRIMARY KEY ("id")
);

ALTER TABLE "meeting" DROP CONSTRAINT "meeting_fk_ea2144";

ALTER TABLE "meeting" ADD CONSTRAINT "meeting_fk_103f5d"
    FOREIGN KEY ("event_creator_id")
    REFERENCES "meeting_attendee" ("id");

ALTER TABLE "meeting" ADD CONSTRAINT "meeting_fk_103f5e"
    FOREIGN KEY ("event_owner_id")
    REFERENCES "meeting_attendee" ("id");

COMMIT;
',
);
    }

}