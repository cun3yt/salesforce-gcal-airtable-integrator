<?php

namespace DataModels\DataModels\Map;

use DataModels\DataModels\ClientCalendarUserOAuth;
use DataModels\DataModels\ClientCalendarUserOAuthQuery;
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
 * This class defines the structure of the 'client_calendar_user_oauth' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ClientCalendarUserOAuthTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'DataModels.DataModels.Map.ClientCalendarUserOAuthTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'client_calendar_user_oauth';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\DataModels\\DataModels\\ClientCalendarUserOAuth';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'DataModels.DataModels.ClientCalendarUserOAuth';

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
    const COL_ID = 'client_calendar_user_oauth.id';

    /**
     * the column name for the client_calendar_user_id field
     */
    const COL_CLIENT_CALENDAR_USER_ID = 'client_calendar_user_oauth.client_calendar_user_id';

    /**
     * the column name for the type field
     */
    const COL_TYPE = 'client_calendar_user_oauth.type';

    /**
     * the column name for the status field
     */
    const COL_STATUS = 'client_calendar_user_oauth.status';

    /**
     * the column name for the data field
     */
    const COL_DATA = 'client_calendar_user_oauth.data';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'client_calendar_user_oauth.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'client_calendar_user_oauth.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'ClientCalendarUserId', 'Type', 'Status', 'Data', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'clientCalendarUserId', 'type', 'status', 'data', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(ClientCalendarUserOAuthTableMap::COL_ID, ClientCalendarUserOAuthTableMap::COL_CLIENT_CALENDAR_USER_ID, ClientCalendarUserOAuthTableMap::COL_TYPE, ClientCalendarUserOAuthTableMap::COL_STATUS, ClientCalendarUserOAuthTableMap::COL_DATA, ClientCalendarUserOAuthTableMap::COL_CREATED_AT, ClientCalendarUserOAuthTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'client_calendar_user_id', 'type', 'status', 'data', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'ClientCalendarUserId' => 1, 'Type' => 2, 'Status' => 3, 'Data' => 4, 'CreatedAt' => 5, 'UpdatedAt' => 6, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'clientCalendarUserId' => 1, 'type' => 2, 'status' => 3, 'data' => 4, 'createdAt' => 5, 'updatedAt' => 6, ),
        self::TYPE_COLNAME       => array(ClientCalendarUserOAuthTableMap::COL_ID => 0, ClientCalendarUserOAuthTableMap::COL_CLIENT_CALENDAR_USER_ID => 1, ClientCalendarUserOAuthTableMap::COL_TYPE => 2, ClientCalendarUserOAuthTableMap::COL_STATUS => 3, ClientCalendarUserOAuthTableMap::COL_DATA => 4, ClientCalendarUserOAuthTableMap::COL_CREATED_AT => 5, ClientCalendarUserOAuthTableMap::COL_UPDATED_AT => 6, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'client_calendar_user_id' => 1, 'type' => 2, 'status' => 3, 'data' => 4, 'created_at' => 5, 'updated_at' => 6, ),
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
        $this->setName('client_calendar_user_oauth');
        $this->setPhpName('ClientCalendarUserOAuth');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\DataModels\\DataModels\\ClientCalendarUserOAuth');
        $this->setPackage('DataModels.DataModels');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('client_calendar_user_oauth_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('client_calendar_user_id', 'ClientCalendarUserId', 'INTEGER', 'client_calendar_user', 'id', false, null, null);
        $this->addColumn('type', 'Type', 'VARCHAR', false, 255, null);
        $this->addColumn('status', 'Status', 'VARCHAR', false, 255, null);
        $this->addColumn('data', 'Data', 'VARCHAR', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ClientCalendarUser', '\\DataModels\\DataModels\\ClientCalendarUser', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':client_calendar_user_id',
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
        return $withPrefix ? ClientCalendarUserOAuthTableMap::CLASS_DEFAULT : ClientCalendarUserOAuthTableMap::OM_CLASS;
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
     * @return array           (ClientCalendarUserOAuth object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ClientCalendarUserOAuthTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ClientCalendarUserOAuthTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ClientCalendarUserOAuthTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ClientCalendarUserOAuthTableMap::OM_CLASS;
            /** @var ClientCalendarUserOAuth $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ClientCalendarUserOAuthTableMap::addInstanceToPool($obj, $key);
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
            $key = ClientCalendarUserOAuthTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ClientCalendarUserOAuthTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var ClientCalendarUserOAuth $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ClientCalendarUserOAuthTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ClientCalendarUserOAuthTableMap::COL_ID);
            $criteria->addSelectColumn(ClientCalendarUserOAuthTableMap::COL_CLIENT_CALENDAR_USER_ID);
            $criteria->addSelectColumn(ClientCalendarUserOAuthTableMap::COL_TYPE);
            $criteria->addSelectColumn(ClientCalendarUserOAuthTableMap::COL_STATUS);
            $criteria->addSelectColumn(ClientCalendarUserOAuthTableMap::COL_DATA);
            $criteria->addSelectColumn(ClientCalendarUserOAuthTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(ClientCalendarUserOAuthTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.client_calendar_user_id');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.status');
            $criteria->addSelectColumn($alias . '.data');
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
        return Propel::getServiceContainer()->getDatabaseMap(ClientCalendarUserOAuthTableMap::DATABASE_NAME)->getTable(ClientCalendarUserOAuthTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ClientCalendarUserOAuthTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ClientCalendarUserOAuthTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ClientCalendarUserOAuthTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a ClientCalendarUserOAuth or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ClientCalendarUserOAuth object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ClientCalendarUserOAuthTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \DataModels\DataModels\ClientCalendarUserOAuth) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ClientCalendarUserOAuthTableMap::DATABASE_NAME);
            $criteria->add(ClientCalendarUserOAuthTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ClientCalendarUserOAuthQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ClientCalendarUserOAuthTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ClientCalendarUserOAuthTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the client_calendar_user_oauth table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ClientCalendarUserOAuthQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ClientCalendarUserOAuth or Criteria object.
     *
     * @param mixed               $criteria Criteria or ClientCalendarUserOAuth object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ClientCalendarUserOAuthTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ClientCalendarUserOAuth object
        }

        if ($criteria->containsKey(ClientCalendarUserOAuthTableMap::COL_ID) && $criteria->keyContainsValue(ClientCalendarUserOAuthTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ClientCalendarUserOAuthTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = ClientCalendarUserOAuthQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ClientCalendarUserOAuthTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ClientCalendarUserOAuthTableMap::buildTableMap();
