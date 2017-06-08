<?php

namespace DataModels\DataModels\Map;

use DataModels\DataModels\Contact;
use DataModels\DataModels\ContactQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'contact' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ContactTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'DataModels.DataModels.Map.ContactTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'contact';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\DataModels\\DataModels\\Contact';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'DataModels.DataModels.Contact';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 12;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 12;

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'contact.email';

    /**
     * the column name for the full_name field
     */
    const COL_FULL_NAME = 'contact.full_name';

    /**
     * the column name for the client_id field
     */
    const COL_CLIENT_ID = 'contact.client_id';

    /**
     * the column name for the account_id field
     */
    const COL_ACCOUNT_ID = 'contact.account_id';

    /**
     * the column name for the sfdc_contact_id field
     */
    const COL_SFDC_CONTACT_ID = 'contact.sfdc_contact_id';

    /**
     * the column name for the sfdc_account_id field
     */
    const COL_SFDC_ACCOUNT_ID = 'contact.sfdc_account_id';

    /**
     * the column name for the sfdc_contact_name field
     */
    const COL_SFDC_CONTACT_NAME = 'contact.sfdc_contact_name';

    /**
     * the column name for the sfdc_contact_title field
     */
    const COL_SFDC_CONTACT_TITLE = 'contact.sfdc_contact_title';

    /**
     * the column name for the sfdc_last_check_time field
     */
    const COL_SFDC_LAST_CHECK_TIME = 'contact.sfdc_last_check_time';

    /**
     * the column name for the id field
     */
    const COL_ID = 'contact.id';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'contact.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'contact.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Email', 'FullName', 'ClientId', 'AccountId', 'SfdcContactId', 'SfdcAccountId', 'SfdcContactName', 'SfdcTitle', 'SFDCLastCheckTime', 'Id', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('email', 'fullName', 'clientId', 'accountId', 'sfdcContactId', 'sfdcAccountId', 'sfdcContactName', 'sfdcTitle', 'sFDCLastCheckTime', 'id', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(ContactTableMap::COL_EMAIL, ContactTableMap::COL_FULL_NAME, ContactTableMap::COL_CLIENT_ID, ContactTableMap::COL_ACCOUNT_ID, ContactTableMap::COL_SFDC_CONTACT_ID, ContactTableMap::COL_SFDC_ACCOUNT_ID, ContactTableMap::COL_SFDC_CONTACT_NAME, ContactTableMap::COL_SFDC_CONTACT_TITLE, ContactTableMap::COL_SFDC_LAST_CHECK_TIME, ContactTableMap::COL_ID, ContactTableMap::COL_CREATED_AT, ContactTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('email', 'full_name', 'client_id', 'account_id', 'sfdc_contact_id', 'sfdc_account_id', 'sfdc_contact_name', 'sfdc_contact_title', 'sfdc_last_check_time', 'id', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Email' => 0, 'FullName' => 1, 'ClientId' => 2, 'AccountId' => 3, 'SfdcContactId' => 4, 'SfdcAccountId' => 5, 'SfdcContactName' => 6, 'SfdcTitle' => 7, 'SFDCLastCheckTime' => 8, 'Id' => 9, 'CreatedAt' => 10, 'UpdatedAt' => 11, ),
        self::TYPE_CAMELNAME     => array('email' => 0, 'fullName' => 1, 'clientId' => 2, 'accountId' => 3, 'sfdcContactId' => 4, 'sfdcAccountId' => 5, 'sfdcContactName' => 6, 'sfdcTitle' => 7, 'sFDCLastCheckTime' => 8, 'id' => 9, 'createdAt' => 10, 'updatedAt' => 11, ),
        self::TYPE_COLNAME       => array(ContactTableMap::COL_EMAIL => 0, ContactTableMap::COL_FULL_NAME => 1, ContactTableMap::COL_CLIENT_ID => 2, ContactTableMap::COL_ACCOUNT_ID => 3, ContactTableMap::COL_SFDC_CONTACT_ID => 4, ContactTableMap::COL_SFDC_ACCOUNT_ID => 5, ContactTableMap::COL_SFDC_CONTACT_NAME => 6, ContactTableMap::COL_SFDC_CONTACT_TITLE => 7, ContactTableMap::COL_SFDC_LAST_CHECK_TIME => 8, ContactTableMap::COL_ID => 9, ContactTableMap::COL_CREATED_AT => 10, ContactTableMap::COL_UPDATED_AT => 11, ),
        self::TYPE_FIELDNAME     => array('email' => 0, 'full_name' => 1, 'client_id' => 2, 'account_id' => 3, 'sfdc_contact_id' => 4, 'sfdc_account_id' => 5, 'sfdc_contact_name' => 6, 'sfdc_contact_title' => 7, 'sfdc_last_check_time' => 8, 'id' => 9, 'created_at' => 10, 'updated_at' => 11, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('contact');
        $this->setPhpName('Contact');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\DataModels\\DataModels\\Contact');
        $this->setPackage('DataModels.DataModels');
        $this->setUseIdGenerator(false);
        // columns
        $this->addColumn('email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('full_name', 'FullName', 'VARCHAR', false, 255, null);
        $this->addForeignKey('client_id', 'ClientId', 'INTEGER', 'client', 'id', false, null, null);
        $this->addForeignKey('account_id', 'AccountId', 'INTEGER', 'account', 'id', false, null, null);
        $this->addColumn('sfdc_contact_id', 'SfdcContactId', 'VARCHAR', false, 127, null);
        $this->addColumn('sfdc_account_id', 'SfdcAccountId', 'VARCHAR', false, 127, null);
        $this->addColumn('sfdc_contact_name', 'SfdcContactName', 'VARCHAR', false, 127, null);
        $this->addColumn('sfdc_contact_title', 'SfdcTitle', 'VARCHAR', false, 127, null);
        $this->addColumn('sfdc_last_check_time', 'SFDCLastCheckTime', 'TIMESTAMP', false, null, null);
        $this->addForeignPrimaryKey('id', 'Id', 'INTEGER' , 'meeting_attendee', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Account', '\\DataModels\\DataModels\\Account', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':account_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Client', '\\DataModels\\DataModels\\Client', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':client_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('MeetingAttendee', '\\DataModels\\DataModels\\MeetingAttendee', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':id',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('ContactHistory', '\\DataModels\\DataModels\\ContactHistory', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':contact_id',
    1 => ':id',
  ),
), null, null, 'ContactHistories', false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'concrete_inheritance' => array('extends' => 'meeting_attendee', 'descendant_column' => 'descendant_class', 'copy_data_to_parent' => 'true', 'copy_data_to_child' => 'false', 'schema' => '', 'exclude_behaviors' => '', ),
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 9 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 9 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 9 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 9 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 9 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 9 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 9 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? ContactTableMap::CLASS_DEFAULT : ContactTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Contact object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ContactTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ContactTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ContactTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ContactTableMap::OM_CLASS;
            /** @var Contact $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ContactTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = ContactTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ContactTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Contact $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ContactTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(ContactTableMap::COL_EMAIL);
            $criteria->addSelectColumn(ContactTableMap::COL_FULL_NAME);
            $criteria->addSelectColumn(ContactTableMap::COL_CLIENT_ID);
            $criteria->addSelectColumn(ContactTableMap::COL_ACCOUNT_ID);
            $criteria->addSelectColumn(ContactTableMap::COL_SFDC_CONTACT_ID);
            $criteria->addSelectColumn(ContactTableMap::COL_SFDC_ACCOUNT_ID);
            $criteria->addSelectColumn(ContactTableMap::COL_SFDC_CONTACT_NAME);
            $criteria->addSelectColumn(ContactTableMap::COL_SFDC_CONTACT_TITLE);
            $criteria->addSelectColumn(ContactTableMap::COL_SFDC_LAST_CHECK_TIME);
            $criteria->addSelectColumn(ContactTableMap::COL_ID);
            $criteria->addSelectColumn(ContactTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(ContactTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.full_name');
            $criteria->addSelectColumn($alias . '.client_id');
            $criteria->addSelectColumn($alias . '.account_id');
            $criteria->addSelectColumn($alias . '.sfdc_contact_id');
            $criteria->addSelectColumn($alias . '.sfdc_account_id');
            $criteria->addSelectColumn($alias . '.sfdc_contact_name');
            $criteria->addSelectColumn($alias . '.sfdc_contact_title');
            $criteria->addSelectColumn($alias . '.sfdc_last_check_time');
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(ContactTableMap::DATABASE_NAME)->getTable(ContactTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ContactTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ContactTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ContactTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Contact or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Contact object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ContactTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \DataModels\DataModels\Contact) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ContactTableMap::DATABASE_NAME);
            $criteria->add(ContactTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ContactQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ContactTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ContactTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the contact table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ContactQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Contact or Criteria object.
     *
     * @param mixed               $criteria Criteria or Contact object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ContactTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Contact object
        }


        // Set the correct dbName
        $query = ContactQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ContactTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ContactTableMap::buildTableMap();
