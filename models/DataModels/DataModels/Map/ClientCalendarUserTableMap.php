<?php

namespace DataModels\DataModels\Map;

use DataModels\DataModels\ClientCalendarUser;
use DataModels\DataModels\ClientCalendarUserQuery;
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
 * This class defines the structure of the 'client_calendar_user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class ClientCalendarUserTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'DataModels.DataModels.Map.ClientCalendarUserTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'client_calendar_user';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\DataModels\\DataModels\\ClientCalendarUser';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'DataModels.DataModels.ClientCalendarUser';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 8;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 8;

    /**
     * the column name for the client_id field
     */
    const COL_CLIENT_ID = 'client_calendar_user.client_id';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'client_calendar_user.name';

    /**
     * the column name for the surname field
     */
    const COL_SURNAME = 'client_calendar_user.surname';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'client_calendar_user.title';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'client_calendar_user.email';

    /**
     * the column name for the id field
     */
    const COL_ID = 'client_calendar_user.id';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'client_calendar_user.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'client_calendar_user.updated_at';

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
        self::TYPE_PHPNAME       => array('ClientId', 'Name', 'Surname', 'Title', 'Email', 'Id', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('clientId', 'name', 'surname', 'title', 'email', 'id', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(ClientCalendarUserTableMap::COL_CLIENT_ID, ClientCalendarUserTableMap::COL_NAME, ClientCalendarUserTableMap::COL_SURNAME, ClientCalendarUserTableMap::COL_TITLE, ClientCalendarUserTableMap::COL_EMAIL, ClientCalendarUserTableMap::COL_ID, ClientCalendarUserTableMap::COL_CREATED_AT, ClientCalendarUserTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('client_id', 'name', 'surname', 'title', 'email', 'id', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('ClientId' => 0, 'Name' => 1, 'Surname' => 2, 'Title' => 3, 'Email' => 4, 'Id' => 5, 'CreatedAt' => 6, 'UpdatedAt' => 7, ),
        self::TYPE_CAMELNAME     => array('clientId' => 0, 'name' => 1, 'surname' => 2, 'title' => 3, 'email' => 4, 'id' => 5, 'createdAt' => 6, 'updatedAt' => 7, ),
        self::TYPE_COLNAME       => array(ClientCalendarUserTableMap::COL_CLIENT_ID => 0, ClientCalendarUserTableMap::COL_NAME => 1, ClientCalendarUserTableMap::COL_SURNAME => 2, ClientCalendarUserTableMap::COL_TITLE => 3, ClientCalendarUserTableMap::COL_EMAIL => 4, ClientCalendarUserTableMap::COL_ID => 5, ClientCalendarUserTableMap::COL_CREATED_AT => 6, ClientCalendarUserTableMap::COL_UPDATED_AT => 7, ),
        self::TYPE_FIELDNAME     => array('client_id' => 0, 'name' => 1, 'surname' => 2, 'title' => 3, 'email' => 4, 'id' => 5, 'created_at' => 6, 'updated_at' => 7, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, )
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
        $this->setName('client_calendar_user');
        $this->setPhpName('ClientCalendarUser');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\DataModels\\DataModels\\ClientCalendarUser');
        $this->setPackage('DataModels.DataModels');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignKey('client_id', 'ClientId', 'INTEGER', 'client', 'id', false, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('surname', 'Surname', 'VARCHAR', false, 255, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 127, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 255, null);
        $this->addForeignPrimaryKey('id', 'Id', 'INTEGER' , 'meeting_attendee', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
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
        $this->addRelation('ClientCalendarUserOAuth', '\\DataModels\\DataModels\\ClientCalendarUserOAuth', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':client_calendar_user_id',
    1 => ':id',
  ),
), null, null, 'ClientCalendarUserOAuths', false);
        $this->addRelation('Meeting', '\\DataModels\\DataModels\\Meeting', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':client_calendar_user_id',
    1 => ':id',
  ),
), null, null, 'Meetings', false);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 5 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 5 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 5 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 5 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 5 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 5 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
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
                ? 5 + $offset
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
        return $withPrefix ? ClientCalendarUserTableMap::CLASS_DEFAULT : ClientCalendarUserTableMap::OM_CLASS;
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
     * @return array           (ClientCalendarUser object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = ClientCalendarUserTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = ClientCalendarUserTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + ClientCalendarUserTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ClientCalendarUserTableMap::OM_CLASS;
            /** @var ClientCalendarUser $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            ClientCalendarUserTableMap::addInstanceToPool($obj, $key);
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
            $key = ClientCalendarUserTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = ClientCalendarUserTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var ClientCalendarUser $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ClientCalendarUserTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(ClientCalendarUserTableMap::COL_CLIENT_ID);
            $criteria->addSelectColumn(ClientCalendarUserTableMap::COL_NAME);
            $criteria->addSelectColumn(ClientCalendarUserTableMap::COL_SURNAME);
            $criteria->addSelectColumn(ClientCalendarUserTableMap::COL_TITLE);
            $criteria->addSelectColumn(ClientCalendarUserTableMap::COL_EMAIL);
            $criteria->addSelectColumn(ClientCalendarUserTableMap::COL_ID);
            $criteria->addSelectColumn(ClientCalendarUserTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(ClientCalendarUserTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.client_id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.surname');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.email');
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
        return Propel::getServiceContainer()->getDatabaseMap(ClientCalendarUserTableMap::DATABASE_NAME)->getTable(ClientCalendarUserTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(ClientCalendarUserTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(ClientCalendarUserTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new ClientCalendarUserTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a ClientCalendarUser or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ClientCalendarUser object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(ClientCalendarUserTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \DataModels\DataModels\ClientCalendarUser) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ClientCalendarUserTableMap::DATABASE_NAME);
            $criteria->add(ClientCalendarUserTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = ClientCalendarUserQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            ClientCalendarUserTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                ClientCalendarUserTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the client_calendar_user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return ClientCalendarUserQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a ClientCalendarUser or Criteria object.
     *
     * @param mixed               $criteria Criteria or ClientCalendarUser object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ClientCalendarUserTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from ClientCalendarUser object
        }


        // Set the correct dbName
        $query = ClientCalendarUserQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // ClientCalendarUserTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
ClientCalendarUserTableMap::buildTableMap();
