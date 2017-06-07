<?php

namespace DataModels\DataModels\Map;

use DataModels\DataModels\Opportunity;
use DataModels\DataModels\OpportunityQuery;
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
 * This class defines the structure of the 'opportunity' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class OpportunityTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'DataModels.DataModels.Map.OpportunityTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'opportunity';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\DataModels\\DataModels\\Opportunity';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'DataModels.DataModels.Opportunity';

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
    const COL_ID = 'opportunity.id';

    /**
     * the column name for the account_id field
     */
    const COL_ACCOUNT_ID = 'opportunity.account_id';

    /**
     * the column name for the sfdc_id field
     */
    const COL_SFDC_ID = 'opportunity.sfdc_id';

    /**
     * the column name for the sfdc_last_check_time field
     */
    const COL_SFDC_LAST_CHECK_TIME = 'opportunity.sfdc_last_check_time';

    /**
     * the column name for the sfdc_opportunity_id field
     */
    const COL_SFDC_OPPORTUNITY_ID = 'opportunity.sfdc_opportunity_id';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'opportunity.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'opportunity.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'AccountId', 'SFDCId', 'SFDCLastCheckTime', 'SFDCOpportunityId', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'accountId', 'sFDCId', 'sFDCLastCheckTime', 'sFDCOpportunityId', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(OpportunityTableMap::COL_ID, OpportunityTableMap::COL_ACCOUNT_ID, OpportunityTableMap::COL_SFDC_ID, OpportunityTableMap::COL_SFDC_LAST_CHECK_TIME, OpportunityTableMap::COL_SFDC_OPPORTUNITY_ID, OpportunityTableMap::COL_CREATED_AT, OpportunityTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'account_id', 'sfdc_id', 'sfdc_last_check_time', 'sfdc_opportunity_id', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'AccountId' => 1, 'SFDCId' => 2, 'SFDCLastCheckTime' => 3, 'SFDCOpportunityId' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'accountId' => 1, 'sFDCId' => 2, 'sFDCLastCheckTime' => 3, 'sFDCOpportunityId' => 4, 'createdAt' => 5, 'updatedAt' => 6, ),
        self::TYPE_COLNAME       => array(OpportunityTableMap::COL_ID => 0, OpportunityTableMap::COL_ACCOUNT_ID => 1, OpportunityTableMap::COL_SFDC_ID => 2, OpportunityTableMap::COL_SFDC_LAST_CHECK_TIME => 3, OpportunityTableMap::COL_SFDC_OPPORTUNITY_ID => 4, OpportunityTableMap::COL_CREATED_AT => 5, OpportunityTableMap::COL_UPDATED_AT => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'account_id' => 1, 'sfdc_id' => 2, 'sfdc_last_check_time' => 3, 'sfdc_opportunity_id' => 4, 'created_at' => 5, 'updated_at' => 6, ),
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
        $this->setName('opportunity');
        $this->setPhpName('Opportunity');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\DataModels\\DataModels\\Opportunity');
        $this->setPackage('DataModels.DataModels');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('opportunity_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('account_id', 'AccountId', 'INTEGER', 'account', 'id', false, null, null);
        $this->addColumn('sfdc_id', 'SFDCId', 'VARCHAR', false, 127, null);
        $this->addColumn('sfdc_last_check_time', 'SFDCLastCheckTime', 'TIMESTAMP', false, null, null);
        $this->addColumn('sfdc_opportunity_id', 'SFDCOpportunityId', 'VARCHAR', false, 127, null);
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
        $this->addRelation('OpportunityHistory', '\\DataModels\\DataModels\\OpportunityHistory', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':opportunity_id',
    1 => ':id',
  ),
), null, null, 'OpportunityHistories', false);
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
        return $withPrefix ? OpportunityTableMap::CLASS_DEFAULT : OpportunityTableMap::OM_CLASS;
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
     * @return array           (Opportunity object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = OpportunityTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = OpportunityTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + OpportunityTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OpportunityTableMap::OM_CLASS;
            /** @var Opportunity $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            OpportunityTableMap::addInstanceToPool($obj, $key);
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
            $key = OpportunityTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = OpportunityTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Opportunity $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OpportunityTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(OpportunityTableMap::COL_ID);
            $criteria->addSelectColumn(OpportunityTableMap::COL_ACCOUNT_ID);
            $criteria->addSelectColumn(OpportunityTableMap::COL_SFDC_ID);
            $criteria->addSelectColumn(OpportunityTableMap::COL_SFDC_LAST_CHECK_TIME);
            $criteria->addSelectColumn(OpportunityTableMap::COL_SFDC_OPPORTUNITY_ID);
            $criteria->addSelectColumn(OpportunityTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(OpportunityTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.account_id');
            $criteria->addSelectColumn($alias . '.sfdc_id');
            $criteria->addSelectColumn($alias . '.sfdc_last_check_time');
            $criteria->addSelectColumn($alias . '.sfdc_opportunity_id');
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
        return Propel::getServiceContainer()->getDatabaseMap(OpportunityTableMap::DATABASE_NAME)->getTable(OpportunityTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(OpportunityTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(OpportunityTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new OpportunityTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Opportunity or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Opportunity object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(OpportunityTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \DataModels\DataModels\Opportunity) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OpportunityTableMap::DATABASE_NAME);
            $criteria->add(OpportunityTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = OpportunityQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            OpportunityTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                OpportunityTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the opportunity table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return OpportunityQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Opportunity or Criteria object.
     *
     * @param mixed               $criteria Criteria or Opportunity object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OpportunityTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Opportunity object
        }

        if ($criteria->containsKey(OpportunityTableMap::COL_ID) && $criteria->keyContainsValue(OpportunityTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.OpportunityTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = OpportunityQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // OpportunityTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
OpportunityTableMap::buildTableMap();
