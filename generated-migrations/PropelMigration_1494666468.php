<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1494666468.
 * Generated on 2017-05-13 09:07:48 by cuneyt
 */
class PropelMigration_1494666468
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

  ALTER COLUMN "id" DROP DEFAULT;

ALTER TABLE "contact" ADD CONSTRAINT "contact_fk_5860de"
    FOREIGN KEY ("id")
    REFERENCES "meeting_attendee" ("id")
    ON DELETE CASCADE;

ALTER TABLE "customer_contact"

  ALTER COLUMN "id" DROP DEFAULT;

ALTER TABLE "customer_contact" ADD CONSTRAINT "customer_contact_fk_5860de"
    FOREIGN KEY ("id")
    REFERENCES "meeting_attendee" ("id")
    ON DELETE CASCADE;

ALTER TABLE "meeting_attendee"

  ADD "descendant_class" VARCHAR(100),

  DROP COLUMN "ref_type",

  DROP COLUMN "ref_id";

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

CREATE SEQUENCE contact_id_seq;

ALTER TABLE "contact" DROP CONSTRAINT "contact_fk_5860de";

ALTER TABLE "contact"

  ALTER COLUMN "id" SET DEFAULT nextval(\'contact_id_seq\'::regclass);

CREATE SEQUENCE customer_contact_id_seq;

ALTER TABLE "customer_contact" DROP CONSTRAINT "customer_contact_fk_5860de";

ALTER TABLE "customer_contact"

  ALTER COLUMN "id" SET DEFAULT nextval(\'customer_contact_id_seq\'::regclass);

ALTER TABLE "meeting_attendee"

  ADD "ref_type" VARCHAR(50) DEFAULT \'"contact"\',

  ADD "ref_id" INTEGER,

  DROP COLUMN "descendant_class";

COMMIT;
',
);
    }

}