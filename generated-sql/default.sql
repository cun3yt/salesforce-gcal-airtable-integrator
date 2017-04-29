
BEGIN;

-----------------------------------------------------------------------
-- account
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "account" CASCADE;

CREATE TABLE "account"
(
    "id" serial NOT NULL,
    "name" VARCHAR(255),
    "email_domain" VARCHAR(255),
    "website" VARCHAR(255),
    "sfdc_account_id" INTEGER,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- account_has_contact
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "account_has_contact" CASCADE;

CREATE TABLE "account_has_contact"
(
    "id" serial NOT NULL,
    "account_id" INTEGER,
    "contact_id" INTEGER,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- account_history
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "account_history" CASCADE;

CREATE TABLE "account_history"
(
    "id" serial NOT NULL,
    "account_id" INTEGER,
    "account_status_id" INTEGER,
    "billing_cycle_id" INTEGER,
    "billing_city" VARCHAR(255),
    "renewal_date" DATE,
    "num_employees" INTEGER,
    "ARR" VARCHAR,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- account_status
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "account_status" CASCADE;

CREATE TABLE "account_status"
(
    "id" serial NOT NULL,
    "customer_id" INTEGER,
    "status" VARCHAR(255),
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- billing_cycle
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "billing_cycle" CASCADE;

CREATE TABLE "billing_cycle"
(
    "id" serial NOT NULL,
    "customer_id" INTEGER,
    "type" VARCHAR(255),
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- buyer_stage
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "buyer_stage" CASCADE;

CREATE TABLE "buyer_stage"
(
    "id" serial NOT NULL,
    "customer_id" INTEGER,
    "stage" VARCHAR(255),
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- contact
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "contact" CASCADE;

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

-----------------------------------------------------------------------
-- contact_history
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "contact_history" CASCADE;

CREATE TABLE "contact_history"
(
    "id" serial NOT NULL,
    "contact_id" INTEGER,
    "account_id" INTEGER,
    "sfdc_title" TEXT,
    "sfdc_mailing_city" TEXT,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- customer
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "customer" CASCADE;

CREATE TABLE "customer"
(
    "id" serial NOT NULL,
    "name" VARCHAR(255) NOT NULL,
    "website" VARCHAR(255),
    "email_domain" VARCHAR(255),
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- customer_contact
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "customer_contact" CASCADE;

CREATE TABLE "customer_contact"
(
    "id" serial NOT NULL,
    "name" VARCHAR(255),
    "surname" VARCHAR(255),
    "title" VARCHAR(127),
    "email" VARCHAR(255),
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- customer_contact_integration
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "customer_contact_integration" CASCADE;

CREATE TABLE "customer_contact_integration"
(
    "id" serial NOT NULL,
    "customer_contact_id" INTEGER,
    "type" VARCHAR(255),
    "status" VARCHAR(255),
    "data" VARCHAR,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- meeting
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "meeting" CASCADE;

CREATE TABLE "meeting"
(
    "id" serial NOT NULL,
    "name" VARCHAR(255),
    "event_datetime" TIMESTAMP,
    "event_creator_id" INTEGER,
    "event_owner_id" INTEGER,
    "event_description" TEXT,
    "account_id" INTEGER,
    "additional_data" VARCHAR,
    "created" TIMESTAMP,
    "updated" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- meeting_attendee
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "meeting_attendee" CASCADE;

CREATE TABLE "meeting_attendee"
(
    "id" serial NOT NULL,
    "ref_type" VARCHAR(50) DEFAULT '"contact"',
    "ref_id" INTEGER,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- meeting_has_account_history
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "meeting_has_account_history" CASCADE;

CREATE TABLE "meeting_has_account_history"
(
    "id" serial NOT NULL,
    "meeting_id" INTEGER,
    "account_history_id" INTEGER,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- meeting_has_attendee
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "meeting_has_attendee" CASCADE;

CREATE TABLE "meeting_has_attendee"
(
    "id" serial NOT NULL,
    "meeting_id" INTEGER,
    "meeting_attendee_id" INTEGER,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- meeting_has_opportunity_history
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "meeting_has_opportunity_history" CASCADE;

CREATE TABLE "meeting_has_opportunity_history"
(
    "id" serial NOT NULL,
    "meeting_id" INTEGER,
    "opportunity_history_id" INTEGER,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- opportunity
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "opportunity" CASCADE;

CREATE TABLE "opportunity"
(
    "id" serial NOT NULL,
    "account_id" INTEGER,
    "stage_id" INTEGER,
    "name" VARCHAR(255),
    "amount" VARCHAR,
    "close_date" TIMESTAMP,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- opportunity_history
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "opportunity_history" CASCADE;

CREATE TABLE "opportunity_history"
(
    "id" serial NOT NULL,
    "opportunity_id" INTEGER,
    "user_account_id" INTEGER,
    "opportunity_stage_id" INTEGER,
    "buyer_stage_id" INTEGER,
    "amount" VARCHAR,
    "close_date" DATE,
    PRIMARY KEY ("id")
);

-----------------------------------------------------------------------
-- opportunity_stage
-----------------------------------------------------------------------

DROP TABLE IF EXISTS "opportunity_stage" CASCADE;

CREATE TABLE "opportunity_stage"
(
    "id" serial NOT NULL,
    "customer_id" TEXT,
    "stage" VARCHAR(255),
    PRIMARY KEY ("id")
);

COMMIT;
