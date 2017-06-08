<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1496783013.
 * Generated on 2017-06-06 21:03:33 by cuneyt
 */
class PropelMigration_1496783013
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

DROP TABLE IF EXISTS "buyer_stage" CASCADE;

DROP TABLE IF EXISTS "opportunity_stage" CASCADE;

ALTER TABLE "opportunity_history"

  ALTER COLUMN "amount" TYPE NUMERIC(16,2),

  ADD "account_name" VARCHAR(127),

  ADD "last_modified_by" VARCHAR(127),

  ADD "next_step" VARCHAR(255),

  ADD "opportunity_name" VARCHAR(120),

  ADD "opportunity_owner" VARCHAR(127),

  ADD "stage" VARCHAR(63),

  ADD "type" VARCHAR(63),

  ADD "contact" VARCHAR(127),

  ADD "created_by" VARCHAR(127),

  ADD "description" VARCHAR,

  ADD "expected_revenue" NUMERIC(16,2),

  ADD "forecast_category" VARCHAR(127),

  ADD "lead_source" VARCHAR(127),

  ADD "price_book" VARCHAR(127),

  ADD "primary_campaign_source" VARCHAR(127),

  ADD "is_private" BOOLEAN,

  ADD "probability" NUMERIC(5,2),

  ADD "quantity" NUMERIC(16,2),

  ADD "synced_quote" VARCHAR(63),

  DROP COLUMN "user_account_id",

  DROP COLUMN "opportunity_stage_id",

  DROP COLUMN "buyer_stage_id";

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

CREATE TABLE "buyer_stage"
(
    "id" serial NOT NULL,
    "client_id" INTEGER,
    "stage" VARCHAR(255),
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

CREATE TABLE "opportunity_stage"
(
    "id" serial NOT NULL,
    "client_id" TEXT,
    "stage" VARCHAR(255),
    "created_at" TIMESTAMP,
    "updated_at" TIMESTAMP,
    PRIMARY KEY ("id")
);

ALTER TABLE "opportunity_history"

  ALTER COLUMN "amount" TYPE VARCHAR,

  ADD "user_account_id" INTEGER,

  ADD "opportunity_stage_id" INTEGER,

  ADD "buyer_stage_id" INTEGER,

  DROP COLUMN "account_name",

  DROP COLUMN "last_modified_by",

  DROP COLUMN "next_step",

  DROP COLUMN "opportunity_name",

  DROP COLUMN "opportunity_owner",

  DROP COLUMN "stage",

  DROP COLUMN "type",

  DROP COLUMN "contact",

  DROP COLUMN "created_by",

  DROP COLUMN "description",

  DROP COLUMN "expected_revenue",

  DROP COLUMN "forecast_category",

  DROP COLUMN "lead_source",

  DROP COLUMN "price_book",

  DROP COLUMN "primary_campaign_source",

  DROP COLUMN "is_private",

  DROP COLUMN "probability",

  DROP COLUMN "quantity",

  DROP COLUMN "synced_quote";

COMMIT;
',
);
    }

}