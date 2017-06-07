<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1496785441.
 * Generated on 2017-06-06 21:44:01 by cuneyt
 */
class PropelMigration_1496785441
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

ALTER TABLE "opportunity" ADD CONSTRAINT "opportunity_fk_474870"
    FOREIGN KEY ("account_id")
    REFERENCES "account" ("id");

ALTER TABLE "opportunity_history" ADD CONSTRAINT "opportunity_history_fk_b40fa9"
    FOREIGN KEY ("opportunity_id")
    REFERENCES "opportunity" ("id");

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

ALTER TABLE "opportunity" DROP CONSTRAINT "opportunity_fk_474870";

ALTER TABLE "opportunity_history" DROP CONSTRAINT "opportunity_history_fk_b40fa9";

COMMIT;
',
);
    }

}