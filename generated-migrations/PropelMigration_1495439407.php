<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495439407.
 * Generated on 2017-05-22 07:50:07 by cuneyt
 */
class PropelMigration_1495439407
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

  ADD "customer_id" INTEGER;

ALTER TABLE "account" ADD CONSTRAINT "account_fk_7e8f3e"
    FOREIGN KEY ("customer_id")
    REFERENCES "customer" ("id");

ALTER TABLE "contact" DROP CONSTRAINT "contact_fk_7e8f3e";

ALTER TABLE "contact"

  DROP COLUMN "customer_id";

ALTER TABLE "contact" ADD CONSTRAINT "contact_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id");

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

ALTER TABLE "account" DROP CONSTRAINT "account_fk_7e8f3e";

ALTER TABLE "account"

  DROP COLUMN "customer_id";

ALTER TABLE "contact" DROP CONSTRAINT "contact_fk_474870";

ALTER TABLE "contact"

  ADD "customer_id" INTEGER;

ALTER TABLE "contact" ADD CONSTRAINT "contact_fk_7e8f3e"
    FOREIGN KEY ("customer_id")
    REFERENCES "customer" ("id");

COMMIT;
',
);
    }

}