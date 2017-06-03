<?php

namespace DataModels\DataModels\Map;

use DataModels\DataModels\AccountHistory;
use DataModels\DataModels\AccountHistoryQuery;
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
 * This class defines the structure of the 'account_history' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class AccountHistoryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'DataModels.DataModels.Map.AccountHistoryTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'account_history';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\DataModels\\DataModels\\AccountHistory';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'DataModels.DataModels.AccountHistory';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 11;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 11;

    /**
     * the column name for the id field
     */
    const COL_ID = 'account_history.id';

    /**
     * the column name for the account_id field
     */
    const COL_ACCOUNT_ID = 'account_history.account_id';

    /**
     * the column name for the sfdc_name field
     */
    const COL_SFDC_NAME = 'account_history.sfdc_name';

    /**
     * the column name for the account_status_id field
     */
    const COL_ACCOUNT_STATUS_ID = 'account_history.account_status_id';

    /**
     * the column name for the billing_cycle_id field
     */
    const COL_BILLING_CYCLE_ID = 'account_history.billing_cycle_id';

    /**
     * the column name for the billing_city field
     */
    const COL_BILLING_CITY = 'account_history.billing_city';

    /**
     * the column name for the renewal_date field
     */
    const COL_RENEWAL_DATE = 'account_history.renewal_date';

    /**
     * the column name for the num_employees field
     */
    const COL_NUM_EMPLOYEES = 'account_history.num_employees';

    /**
     * the column name for the ARR field
     */
    const COL_ARR = 'account_history.ARR';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'account_history.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'account_history.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'AccountId', 'SFDCName', 'AccountStatusId', 'BillingCycleId', 'BillingCity', 'RenewalDate', 'NumEmployees', 'Arr', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'accountId', 'sFDCName', 'accountStatusId', 'billingCycleId', 'billingCity', 'renewalDate', 'numEmployees', 'arr', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(AccountHistoryTableMap::COL_ID, AccountHistoryTableMap::COL_ACCOUNT_ID, AccountHistoryTableMap::COL_SFDC_NAME, AccountHistoryTableMap::COL_ACCOUNT_STATUS_ID, AccountHistoryTableMap::COL_BILLING_CYCLE_ID, AccountHistoryTableMap::COL_BILLING_CITY, AccountHistoryTableMap::COL_RENEWAL_DATE, AccountHistoryTableMap::COL_NUM_EMPLOYEES, AccountHistoryTableMap::COL_ARR, AccountHistoryTableMap::COL_CREATED_AT, AccountHistoryTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'account_id', 'sfdc_name', 'account_status_id', 'billing_cycle_id', 'billing_city', 'renewal_date', 'num_employees', 'ARR', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'AccountId' => 1, 'SFDCName' => 2, 'AccountStatusId' => 3, 'BillingCycleId' => 4, 'BillingCity' => 5, 'RenewalDate' => 6, 'NumEmployees' => 7, 'Arr' => 8, 'CreatedAt' => 9, 'UpdatedAt' => 10, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'accountId' => 1, 'sFDCName' => 2, 'accountStatusId' => 3, 'billingCycleId' => 4, 'billingCity' => 5, 'renewalDate' => 6, 'numEmployees' => 7, 'arr' => 8, 'createdAt' => 9, 'updatedAt' => 10, ),
        self::TYPE_COLNAME       => array(AccountHistoryTableMap::COL_ID => 0, AccountHistoryTableMap::COL_ACCOUNT_ID => 1, AccountHistoryTableMap::COL_SFDC_NAME => 2, AccountHistoryTableMap::COL_ACCOUNT_STATUS_ID => 3, AccountHistoryTableMap::COL_BILLING_CYCLE_ID => 4, AccountHistoryTableMap::COL_BILLING_CITY => 5, AccountHistoryTableMap::COL_RENEWAL_DATE => 6, AccountHistoryTableMap::COL_NUM_EMPLOYEES => 7, AccountHistoryTableMap::COL_ARR => 8, AccountHistoryTableMap::COL_CREATED_AT => 9, AccountHistoryTableMap::COL_UPDATED_AT => 10, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'account_id' => 1, 'sfdc_name' => 2, 'account_status_id' => 3, 'billing_cycle_id' => 4, 'billing_city' => 5, 'renewal_date' => 6, 'num_employees' => 7, 'ARR' => 8, 'created_at' => 9, 'updated_at' => 10, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, )
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
        $this->setName('account_history');
        $this->setPhpName('AccountHistory');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\DataModels\\DataModels\\AccountHistory');
        $this->setPackage('DataModels.DataModels');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('account_history_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('account_id', 'AccountId', 'INTEGER', 'account', 'id', false, null, null);
        $this->addColumn('sfdc_name', 'SFDCName', 'VARCHAR', false, 127, null);
        $this->addColumn('account_status_id', 'AccountStatusId', 'INTEGER', false, null, null);
        $this->addColumn('billing_cycle_id', 'BillingCycleId', 'INTEGER', false, null, null);
        $this->addColumn('billing_city', 'BillingCity', 'VARCHAR', false, 255, null);
        $this->addColumn('renewal_date', 'RenewalDate', 'DATE', false, null, null);
        $this->addColumn('num_employees', 'NumEmployees', 'INTEGER', false, null, null);
        $this->addColumn('ARR', 'Arr', 'VARCHAR', false, null, null);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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
                ? 0 + $offset
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
        return $withPrefix ? AccountHistoryTableMap::CLASS_DEFAULT : AccountHistoryTableMap::OM_CLASS;
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
     * @return array           (AccountHistory object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AccountHistoryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AccountHistoryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AccountHistoryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AccountHistoryTableMap::OM_CLASS;
            /** @var AccountHistory $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AccountHistoryTableMap::addInstanceToPool($obj, $key);
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
            $key = AccountHistoryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AccountHistoryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var AccountHistory $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AccountHistoryTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AccountHistoryTableMap::COL_ID);
            $criteria->addSelectColumn(AccountHistoryTableMap::COL_ACCOUNT_ID);
            $criteria->addSelectColumn(AccountHistoryTableMap::COL_SFDC_NAME);
            $criteria->addSelectColumn(AccountHistoryTableMap::COL_ACCOUNT_STATUS_ID);
            $criteria->addSelectColumn(AccountHistoryTableMap::COL_BILLING_CYCLE_ID);
            $criteria->addSelectColumn(AccountHistoryTableMap::COL_BILLING_CITY);
            $criteria->addSelectColumn(AccountHistoryTableMap::COL_RENEWAL_DATE);
            $criteria->addSelectColumn(AccountHistoryTableMap::COL_NUM_EMPLOYEES);
            $criteria->addSelectColumn(AccountHistoryTableMap::COL_ARR);
            $criteria->addSelectColumn(AccountHistoryTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(AccountHistoryTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.account_id');
            $criteria->addSelectColumn($alias . '.sfdc_name');
            $criteria->addSelectColumn($alias . '.account_status_id');
            $criteria->addSelectColumn($alias . '.billing_cycle_id');
            $criteria->addSelectColumn($alias . '.billing_city');
            $criteria->addSelectColumn($alias . '.renewal_date');
            $criteria->addSelectColumn($alias . '.num_employees');
            $criteria->addSelectColumn($alias . '.ARR');
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
        return Propel::getServiceContainer()->getDatabaseMap(AccountHistoryTableMap::DATABASE_NAME)->getTable(AccountHistoryTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(AccountHistoryTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(AccountHistoryTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new AccountHistoryTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a AccountHistory or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or AccountHistory object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AccountHistoryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \DataModels\DataModels\AccountHistory) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AccountHistoryTableMap::DATABASE_NAME);
            $criteria->add(AccountHistoryTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = AccountHistoryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AccountHistoryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AccountHistoryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the account_history table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AccountHistoryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a AccountHistory or Criteria object.
     *
     * @param mixed               $criteria Criteria or AccountHistory object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AccountHistoryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from AccountHistory object
        }

        if ($criteria->containsKey(AccountHistoryTableMap::COL_ID) && $criteria->keyContainsValue(AccountHistoryTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AccountHistoryTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AccountHistoryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // AccountHistoryTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
AccountHistoryTableMap::buildTableMap();
