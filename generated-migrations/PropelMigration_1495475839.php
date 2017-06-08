<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495475839.
 * Generated on 2017-05-22 17:57:19 by cuneyt
 */
class PropelMigration_1495475839
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

DROP TABLE IF EXISTS "customer_contact" CASCADE;

DROP TABLE IF EXISTS "customer_contact_integration" CASCADE;

ALTER TABLE "customer" RENAME TO "internal_client";

CREATE TABLE "client_calendar_user"
(
    "internal_client_id" INTEGER,
    "name" VARCHAR(255),
    "surname" VARCHAR(255),
    "title" VARCHAR(127),
    "email" VARCHAR(255),
    "id" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

CREATE TABLE "client_calendar_user_oauth"
(
    "id" serial NOT NULL,
    "client_calendar_user_id" INTEGER,
    "type" VARCHAR(255),
    "status" VARCHAR(255),
    "data" VARCHAR,
    PRIMARY KEY ("id")
);

ALTER TABLE "account" DROP CONSTRAINT "account_fk_7e8f3e";

ALTER TABLE "account" RENAME COLUMN "customer_id" TO "internal_client_id";

ALTER TABLE "account" ADD CONSTRAINT "account_fk_24beb3"
    FOREIGN KEY ("internal_client_id")
    REFERENCES "internal_client" ("id");

ALTER TABLE "account_status" RENAME COLUMN "customer_id" TO "internal_client";

ALTER TABLE "billing_cycle" RENAME COLUMN "customer_id" TO "internal_client_id";

ALTER TABLE "buyer_stage" RENAME COLUMN "customer_id" TO "internal_client_id";

ALTER TABLE "opportunity_stage" RENAME COLUMN "customer_id" TO "internal_client_id";

ALTER TABLE "client_calendar_user" ADD CONSTRAINT "client_calendar_user_fk_24beb3"
    FOREIGN KEY ("internal_client_id")
    REFERENCES "internal_client" ("id");

ALTER TABLE "client_calendar_user" ADD CONSTRAINT "client_calendar_user_fk_5860de"
    FOREIGN KEY ("id")
    REFERENCES "meeting_attendee" ("id")
    ON DELETE CASCADE;

ALTER TABLE "client_calendar_user_oauth" ADD CONSTRAINT "client_calendar_user_oauth_fk_c1d8a5"
    FOREIGN KEY ("client_calendar_user_id")
    REFERENCES "client_calendar_user" ("id");

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

DROP TABLE IF EXISTS "client_calendar_user" CASCADE;

DROP TABLE IF EXISTS "client_calendar_user_oauth" CASCADE;

ALTER TABLE "internal_client" RENAME TO "customer";

CREATE TABLE "customer_contact"
(
    "id" INTEGER NOT NULL,
    "name" VARCHAR(255),
    "surname" VARCHAR(255),
    "title" VARCHAR(127),
    "email" VARCHAR(255),
    "customer_id" INTEGER,
    PRIMARY KEY ("id")
);

CREATE TABLE "customer_contact_integration"
(
    "id" serial NOT NULL,
    "customer_contact_id" INTEGER,
    "type" VARCHAR(255),
    "status" VARCHAR(255),
    "data" VARCHAR,
    PRIMARY KEY ("id")
);

ALTER TABLE "account" DROP CONSTRAINT "account_fk_24beb3";

ALTER TABLE "account" RENAME COLUMN "internal_client_id" TO "customer_id";

ALTER TABLE "account" ADD CONSTRAINT "account_fk_7e8f3e"
    FOREIGN KEY ("customer_id")
    REFERENCES "customer" ("id");

ALTER TABLE "account_status" RENAME COLUMN "internal_client" TO "customer_id";

ALTER TABLE "billing_cycle" RENAME COLUMN "internal_client_id" TO "customer_id";

ALTER TABLE "buyer_stage" RENAME COLUMN "internal_client_id" TO "customer_id";

ALTER TABLE "opportunity_stage" RENAME COLUMN "internal_client_id" TO "customer_id";

ALTER TABLE "customer_contact" ADD CONSTRAINT "customer_contact_fk_5860de"
    FOREIGN KEY ("id")
    REFERENCES "meeting_attendee" ("id")
    ON DELETE CASCADE;

ALTER TABLE "customer_contact" ADD CONSTRAINT "customer_contact_fk_7e8f3e"
    FOREIGN KEY ("customer_id")
    REFERENCES "customer" ("id");

ALTER TABLE "customer_contact_integration" ADD CONSTRAINT "customer_contact_integration_fk_6a9ba8"
    FOREIGN KEY ("customer_contact_id")
    REFERENCES "customer_contact" ("id");

COMMIT;
',
);
    }

}