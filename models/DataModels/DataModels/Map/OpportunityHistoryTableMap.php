<?php

namespace DataModels\DataModels\Map;

use DataModels\DataModels\OpportunityHistory;
use DataModels\DataModels\OpportunityHistoryQuery;
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
 * This class defines the structure of the 'opportunity_history' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class OpportunityHistoryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'DataModels.DataModels.Map.OpportunityHistoryTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'opportunity_history';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\DataModels\\DataModels\\OpportunityHistory';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'DataModels.DataModels.OpportunityHistory';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 7;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 7;

    /**
     * the column name for the id field
     */
    const COL_ID = 'opportunity_history.id';

    /**
     * the column name for the opportunity_id field
     */
    const COL_OPPORTUNITY_ID = 'opportunity_history.opportunity_id';

    /**
     * the column name for the user_account_id field
     */
    const COL_USER_ACCOUNT_ID = 'opportunity_history.user_account_id';

    /**
     * the column name for the opportunity_stage_id field
     */
    const COL_OPPORTUNITY_STAGE_ID = 'opportunity_history.opportunity_stage_id';

    /**
     * the column name for the buyer_stage_id field
     */
    const COL_BUYER_STAGE_ID = 'opportunity_history.buyer_stage_id';

    /**
     * the column name for the amount field
     */
    const COL_AMOUNT = 'opportunity_history.amount';

    /**
     * the column name for the close_date field
     */
    const COL_CLOSE_DATE = 'opportunity_history.close_date';

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
        self::TYPE_PHPNAME       => array('Id', 'OpportunityId', 'UserAccountId', 'OpportunityStageId', 'BuyerStageId', 'Amount', 'CloseDate', ),
        self::TYPE_CAMELNAME     => array('id', 'opportunityId', 'userAccountId', 'opportunityStageId', 'buyerStageId', 'amount', 'closeDate', ),
        self::TYPE_COLNAME       => array(OpportunityHistoryTableMap::COL_ID, OpportunityHistoryTableMap::COL_OPPORTUNITY_ID, OpportunityHistoryTableMap::COL_USER_ACCOUNT_ID, OpportunityHistoryTableMap::COL_OPPORTUNITY_STAGE_ID, OpportunityHistoryTableMap::COL_BUYER_STAGE_ID, OpportunityHistoryTableMap::COL_AMOUNT, OpportunityHistoryTableMap::COL_CLOSE_DATE, ),
        self::TYPE_FIELDNAME     => array('id', 'opportunity_id', 'user_account_id', 'opportunity_stage_id', 'buyer_stage_id', 'amount', 'close_date', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'OpportunityId' => 1, 'UserAccountId' => 2, 'OpportunityStageId' => 3, 'BuyerStageId' => 4, 'Amount' => 5, 'CloseDate' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'opportunityId' => 1, 'userAccountId' => 2, 'opportunityStageId' => 3, 'buyerStageId' => 4, 'amount' => 5, 'closeDate' => 6, ),
        self::TYPE_COLNAME       => array(OpportunityHistoryTableMap::COL_ID => 0, OpportunityHistoryTableMap::COL_OPPORTUNITY_ID => 1, OpportunityHistoryTableMap::COL_USER_ACCOUNT_ID => 2, OpportunityHistoryTableMap::COL_OPPORTUNITY_STAGE_ID => 3, OpportunityHistoryTableMap::COL_BUYER_STAGE_ID => 4, OpportunityHistoryTableMap::COL_AMOUNT => 5, OpportunityHistoryTableMap::COL_CLOSE_DATE => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'opportunity_id' => 1, 'user_account_id' => 2, 'opportunity_stage_id' => 3, 'buyer_stage_id' => 4, 'amount' => 5, 'close_date' => 6, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
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
        $this->setName('opportunity_history');
        $this->setPhpName('OpportunityHistory');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\DataModels\\DataModels\\OpportunityHistory');
        $this->setPackage('DataModels.DataModels');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('opportunity_history_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('opportunity_id', 'OpportunityId', 'INTEGER', false, null, null);
        $this->addColumn('user_account_id', 'UserAccountId', 'INTEGER', false, null, null);
        $this->addColumn('opportunity_stage_id', 'OpportunityStageId', 'INTEGER', false, null, null);
        $this->addColumn('buyer_stage_id', 'BuyerStageId', 'INTEGER', false, null, null);
        $this->addColumn('amount', 'Amount', 'VARCHAR', false, null, null);
        $this->addColumn('close_date', 'CloseDate', 'DATE', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

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
        return $withPrefix ? OpportunityHistoryTableMap::CLASS_DEFAULT : OpportunityHistoryTableMap::OM_CLASS;
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
     * @return array           (OpportunityHistory object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = OpportunityHistoryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = OpportunityHistoryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + OpportunityHistoryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OpportunityHistoryTableMap::OM_CLASS;
            /** @var OpportunityHistory $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            OpportunityHistoryTableMap::addInstanceToPool($obj, $key);
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
            $key = OpportunityHistoryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = OpportunityHistoryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var OpportunityHistory $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OpportunityHistoryTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_ID);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_OPPORTUNITY_ID);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_USER_ACCOUNT_ID);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_OPPORTUNITY_STAGE_ID);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_BUYER_STAGE_ID);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_AMOUNT);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_CLOSE_DATE);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.opportunity_id');
            $criteria->addSelectColumn($alias . '.user_account_id');
            $criteria->addSelectColumn($alias . '.opportunity_stage_id');
            $criteria->addSelectColumn($alias . '.buyer_stage_id');
            $criteria->addSelectColumn($alias . '.amount');
            $criteria->addSelectColumn($alias . '.close_date');
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
        return Propel::getServiceContainer()->getDatabaseMap(OpportunityHistoryTableMap::DATABASE_NAME)->getTable(OpportunityHistoryTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(OpportunityHistoryTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(OpportunityHistoryTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new OpportunityHistoryTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a OpportunityHistory or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or OpportunityHistory object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(OpportunityHistoryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \DataModels\DataModels\OpportunityHistory) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OpportunityHistoryTableMap::DATABASE_NAME);
            $criteria->add(OpportunityHistoryTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = OpportunityHistoryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            OpportunityHistoryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                OpportunityHistoryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the opportunity_history table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return OpportunityHistoryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a OpportunityHistory or Criteria object.
     *
     * @param mixed               $criteria Criteria or OpportunityHistory object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OpportunityHistoryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from OpportunityHistory object
        }

        if ($criteria->containsKey(OpportunityHistoryTableMap::COL_ID) && $criteria->keyContainsValue(OpportunityHistoryTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.OpportunityHistoryTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = OpportunityHistoryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // OpportunityHistoryTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
OpportunityHistoryTableMap::buildTableMap();
