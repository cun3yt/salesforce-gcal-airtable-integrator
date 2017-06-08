<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495476420.
 * Generated on 2017-05-22 18:07:00 by cuneyt
 */
class PropelMigration_1495476420
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

ALTER TABLE "internal_client" RENAME TO "client";

ALTER TABLE "account" DROP CONSTRAINT "account_fk_24beb3";

ALTER TABLE "account" RENAME COLUMN "internal_client_id" TO "client_id";

ALTER TABLE "account" ADD CONSTRAINT "account_fk_90166c"
    FOREIGN KEY ("client_id")
    REFERENCES "client" ("id");

ALTER TABLE "account_status" RENAME COLUMN "internal_client" TO "client";

ALTER TABLE "billing_cycle" RENAME COLUMN "internal_client_id" TO "client_id";

ALTER TABLE "buyer_stage" RENAME COLUMN "internal_client_id" TO "client_id";

ALTER TABLE "client_calendar_user" DROP CONSTRAINT "client_calendar_user_fk_24beb3";

ALTER TABLE "client_calendar_user" RENAME COLUMN "internal_client_id" TO "client_id";

ALTER TABLE "client_calendar_user" ADD CONSTRAINT "client_calendar_user_fk_90166c"
    FOREIGN KEY ("client_id")
    REFERENCES "client" ("id");

ALTER TABLE "opportunity_stage" RENAME COLUMN "internal_client_id" TO "client_id";

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

ALTER TABLE "client" RENAME TO "internal_client";

ALTER TABLE "account" DROP CONSTRAINT "account_fk_90166c";

ALTER TABLE "account" RENAME COLUMN "client_id" TO "internal_client_id";

ALTER TABLE "account" ADD CONSTRAINT "account_fk_24beb3"
    FOREIGN KEY ("internal_client_id")
    REFERENCES "internal_client" ("id");

ALTER TABLE "account_status" RENAME COLUMN "client" TO "internal_client";

ALTER TABLE "billing_cycle" RENAME COLUMN "client_id" TO "internal_client_id";

ALTER TABLE "buyer_stage" RENAME COLUMN "client_id" TO "internal_client_id";

ALTER TABLE "client_calendar_user" DROP CONSTRAINT "client_calendar_user_fk_90166c";

ALTER TABLE "client_calendar_user" RENAME COLUMN "client_id" TO "internal_client_id";

ALTER TABLE "client_calendar_user" ADD CONSTRAINT "client_calendar_user_fk_24beb3"
    FOREIGN KEY ("internal_client_id")
    REFERENCES "internal_client" ("id");

ALTER TABLE "opportunity_stage" RENAME COLUMN "client_id" TO "internal_client_id";

COMMIT;
',
);
    }

}