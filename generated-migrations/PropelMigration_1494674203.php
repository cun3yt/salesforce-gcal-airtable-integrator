<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1494674203.
 * Generated on 2017-05-13 11:16:43 by cuneyt
 */
class PropelMigration_1494674203
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

ALTER TABLE "meeting_has_attendee"

  DROP CONSTRAINT "meeting_has_attendee_pkey",

  ALTER COLUMN "meeting_id" SET NOT NULL,

  ALTER COLUMN "meeting_attendee_id" SET NOT NULL,

  DROP COLUMN "id",

  ADD PRIMARY KEY ("meeting_id","meeting_attendee_id");

ALTER TABLE "meeting_has_attendee" ADD CONSTRAINT "meeting_has_attendee_fk_0f110d"
    FOREIGN KEY ("meeting_id")
    REFERENCES "meeting" ("id");

ALTER TABLE "meeting_has_attendee" ADD CONSTRAINT "meeting_has_attendee_fk_932b1e"
    FOREIGN KEY ("meeting_attendee_id")
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

ALTER TABLE "meeting_has_attendee" DROP CONSTRAINT "meeting_has_attendee_fk_0f110d";

ALTER TABLE "meeting_has_attendee" DROP CONSTRAINT "meeting_has_attendee_fk_932b1e";

ALTER TABLE "meeting_has_attendee"

  DROP CONSTRAINT "meeting_has_attendee_pkey",

  ALTER COLUMN "meeting_id" DROP NOT NULL,

  ALTER COLUMN "meeting_attendee_id" DROP NOT NULL,

  ADD "id" serial NOT NULL,

  ADD PRIMARY KEY ("id");

COMMIT;
',
);
    }

}