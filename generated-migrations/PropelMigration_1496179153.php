<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1496179153.
 * Generated on 2017-05-30 21:19:13 by cuneyt
 */
class PropelMigration_1496179153
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

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "account_history"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "account_status"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "billing_cycle"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "buyer_stage"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "client"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "client_calendar_user"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "client_calendar_user_oauth"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "contact"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "contact_history"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "contact_history" ADD CONSTRAINT "contact_history_fk_afc73e"
    FOREIGN KEY ("contact_id")
    REFERENCES "contact" ("id");

ALTER TABLE "meeting_attendee"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "meeting_has_account_history"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "meeting_has_attendee"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "meeting_has_opportunity_history"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "opportunity"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "opportunity_history"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

ALTER TABLE "opportunity_stage"

  ADD "created_at" TIMESTAMP,

  ADD "updated_at" TIMESTAMP;

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

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "account_history"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "account_status"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "billing_cycle"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "buyer_stage"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "client"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "client_calendar_user"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "client_calendar_user_oauth"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "contact"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "contact_history" DROP CONSTRAINT "contact_history_fk_afc73e";

ALTER TABLE "contact_history"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "meeting_attendee"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "meeting_has_account_history"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "meeting_has_attendee"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "meeting_has_opportunity_history"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "opportunity"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "opportunity_history"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

ALTER TABLE "opportunity_stage"

  DROP COLUMN "created_at",

  DROP COLUMN "updated_at";

COMMIT;
',
);
    }

}