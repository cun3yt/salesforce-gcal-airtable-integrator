<?php

namespace DataModels\DataModels\Map;

use DataModels\DataModels\Meeting;
use DataModels\DataModels\MeetingQuery;
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
 * This class defines the structure of the 'meeting' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MeetingTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'DataModels.DataModels.Map.MeetingTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'meeting';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\DataModels\\DataModels\\Meeting';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'DataModels.DataModels.Meeting';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 15;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 15;

    /**
     * the column name for the id field
     */
    const COL_ID = 'meeting.id';

    /**
     * the column name for the event_id field
     */
    const COL_EVENT_ID = 'meeting.event_id';

    /**
     * the column name for the event_type field
     */
    const COL_EVENT_TYPE = 'meeting.event_type';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'meeting.name';

    /**
     * the column name for the event_datetime field
     */
    const COL_EVENT_DATETIME = 'meeting.event_datetime';

    /**
     * the column name for the event_creator_id field
     */
    const COL_EVENT_CREATOR_ID = 'meeting.event_creator_id';

    /**
     * the column name for the event_owner_id field
     */
    const COL_EVENT_OWNER_ID = 'meeting.event_owner_id';

    /**
     * the column name for the event_description field
     */
    const COL_EVENT_DESCRIPTION = 'meeting.event_description';

    /**
     * the column name for the account_id field
     */
    const COL_ACCOUNT_ID = 'meeting.account_id';

    /**
     * the column name for the additional_data field
     */
    const COL_ADDITIONAL_DATA = 'meeting.additional_data';

    /**
     * the column name for the event_created_at field
     */
    const COL_EVENT_CREATED_AT = 'meeting.event_created_at';

    /**
     * the column name for the event_updated_at field
     */
    const COL_EVENT_UPDATED_AT = 'meeting.event_updated_at';

    /**
     * the column name for the raw_data field
     */
    const COL_RAW_DATA = 'meeting.raw_data';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'meeting.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'meeting.updated_at';

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
        self::TYPE_PHPNAME       => array('Id', 'EventId', 'EventType', 'Name', 'EventDatetime', 'EventCreatorId', 'EventOwnerId', 'EventDescription', 'AccountId', 'AdditionalData', 'EventCreatedAt', 'EventUpdatedAt', 'RawData', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'eventId', 'eventType', 'name', 'eventDatetime', 'eventCreatorId', 'eventOwnerId', 'eventDescription', 'accountId', 'additionalData', 'eventCreatedAt', 'eventUpdatedAt', 'rawData', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(MeetingTableMap::COL_ID, MeetingTableMap::COL_EVENT_ID, MeetingTableMap::COL_EVENT_TYPE, MeetingTableMap::COL_NAME, MeetingTableMap::COL_EVENT_DATETIME, MeetingTableMap::COL_EVENT_CREATOR_ID, MeetingTableMap::COL_EVENT_OWNER_ID, MeetingTableMap::COL_EVENT_DESCRIPTION, MeetingTableMap::COL_ACCOUNT_ID, MeetingTableMap::COL_ADDITIONAL_DATA, MeetingTableMap::COL_EVENT_CREATED_AT, MeetingTableMap::COL_EVENT_UPDATED_AT, MeetingTableMap::COL_RAW_DATA, MeetingTableMap::COL_CREATED_AT, MeetingTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'event_id', 'event_type', 'name', 'event_datetime', 'event_creator_id', 'event_owner_id', 'event_description', 'account_id', 'additional_data', 'event_created_at', 'event_updated_at', 'raw_data', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'EventId' => 1, 'EventType' => 2, 'Name' => 3, 'EventDatetime' => 4, 'EventCreatorId' => 5, 'EventOwnerId' => 6, 'EventDescription' => 7, 'AccountId' => 8, 'AdditionalData' => 9, 'EventCreatedAt' => 10, 'EventUpdatedAt' => 11, 'RawData' => 12, 'CreatedAt' => 13, 'UpdatedAt' => 14, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'eventId' => 1, 'eventType' => 2, 'name' => 3, 'eventDatetime' => 4, 'eventCreatorId' => 5, 'eventOwnerId' => 6, 'eventDescription' => 7, 'accountId' => 8, 'additionalData' => 9, 'eventCreatedAt' => 10, 'eventUpdatedAt' => 11, 'rawData' => 12, 'createdAt' => 13, 'updatedAt' => 14, ),
        self::TYPE_COLNAME       => array(MeetingTableMap::COL_ID => 0, MeetingTableMap::COL_EVENT_ID => 1, MeetingTableMap::COL_EVENT_TYPE => 2, MeetingTableMap::COL_NAME => 3, MeetingTableMap::COL_EVENT_DATETIME => 4, MeetingTableMap::COL_EVENT_CREATOR_ID => 5, MeetingTableMap::COL_EVENT_OWNER_ID => 6, MeetingTableMap::COL_EVENT_DESCRIPTION => 7, MeetingTableMap::COL_ACCOUNT_ID => 8, MeetingTableMap::COL_ADDITIONAL_DATA => 9, MeetingTableMap::COL_EVENT_CREATED_AT => 10, MeetingTableMap::COL_EVENT_UPDATED_AT => 11, MeetingTableMap::COL_RAW_DATA => 12, MeetingTableMap::COL_CREATED_AT => 13, MeetingTableMap::COL_UPDATED_AT => 14, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'event_id' => 1, 'event_type' => 2, 'name' => 3, 'event_datetime' => 4, 'event_creator_id' => 5, 'event_owner_id' => 6, 'event_description' => 7, 'account_id' => 8, 'additional_data' => 9, 'event_created_at' => 10, 'event_updated_at' => 11, 'raw_data' => 12, 'created_at' => 13, 'updated_at' => 14, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, )
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
        $this->setName('meeting');
        $this->setPhpName('Meeting');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\DataModels\\DataModels\\Meeting');
        $this->setPackage('DataModels.DataModels');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('meeting_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('event_id', 'EventId', 'VARCHAR', false, 255, null);
        $this->addColumn('event_type', 'EventType', 'VARCHAR', false, 16, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 255, null);
        $this->addColumn('event_datetime', 'EventDatetime', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('event_creator_id', 'EventCreatorId', 'INTEGER', 'meeting_attendee', 'id', false, null, null);
        $this->addForeignKey('event_owner_id', 'EventOwnerId', 'INTEGER', 'meeting_attendee', 'id', false, null, null);
        $this->addColumn('event_description', 'EventDescription', 'LONGVARCHAR', false, null, null);
        $this->addColumn('account_id', 'AccountId', 'INTEGER', false, null, null);
        $this->addColumn('additional_data', 'AdditionalData', 'VARCHAR', false, null, null);
        $this->addColumn('event_created_at', 'EventCreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('event_updated_at', 'EventUpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('raw_data', 'RawData', 'VARCHAR', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('EventOwner', '\\DataModels\\DataModels\\MeetingAttendee', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':event_owner_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('EventCreator', '\\DataModels\\DataModels\\MeetingAttendee', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':event_creator_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('MeetingHasAttendee', '\\DataModels\\DataModels\\MeetingHasAttendee', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':meeting_id',
    1 => ':id',
  ),
), null, null, 'MeetingHasAttendees', false);
        $this->addRelation('MeetingAttendee', '\\DataModels\\DataModels\\MeetingAttendee', RelationMap::MANY_TO_MANY, array(), null, null, 'MeetingAttendees');
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
        return $withPrefix ? MeetingTableMap::CLASS_DEFAULT : MeetingTableMap::OM_CLASS;
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
     * @return array           (Meeting object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MeetingTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MeetingTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MeetingTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MeetingTableMap::OM_CLASS;
            /** @var Meeting $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MeetingTableMap::addInstanceToPool($obj, $key);
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
            $key = MeetingTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MeetingTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Meeting $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MeetingTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(MeetingTableMap::COL_ID);
            $criteria->addSelectColumn(MeetingTableMap::COL_EVENT_ID);
            $criteria->addSelectColumn(MeetingTableMap::COL_EVENT_TYPE);
            $criteria->addSelectColumn(MeetingTableMap::COL_NAME);
            $criteria->addSelectColumn(MeetingTableMap::COL_EVENT_DATETIME);
            $criteria->addSelectColumn(MeetingTableMap::COL_EVENT_CREATOR_ID);
            $criteria->addSelectColumn(MeetingTableMap::COL_EVENT_OWNER_ID);
            $criteria->addSelectColumn(MeetingTableMap::COL_EVENT_DESCRIPTION);
            $criteria->addSelectColumn(MeetingTableMap::COL_ACCOUNT_ID);
            $criteria->addSelectColumn(MeetingTableMap::COL_ADDITIONAL_DATA);
            $criteria->addSelectColumn(MeetingTableMap::COL_EVENT_CREATED_AT);
            $criteria->addSelectColumn(MeetingTableMap::COL_EVENT_UPDATED_AT);
            $criteria->addSelectColumn(MeetingTableMap::COL_RAW_DATA);
            $criteria->addSelectColumn(MeetingTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(MeetingTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.event_id');
            $criteria->addSelectColumn($alias . '.event_type');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.event_datetime');
            $criteria->addSelectColumn($alias . '.event_creator_id');
            $criteria->addSelectColumn($alias . '.event_owner_id');
            $criteria->addSelectColumn($alias . '.event_description');
            $criteria->addSelectColumn($alias . '.account_id');
            $criteria->addSelectColumn($alias . '.additional_data');
            $criteria->addSelectColumn($alias . '.event_created_at');
            $criteria->addSelectColumn($alias . '.event_updated_at');
            $criteria->addSelectColumn($alias . '.raw_data');
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
        return Propel::getServiceContainer()->getDatabaseMap(MeetingTableMap::DATABASE_NAME)->getTable(MeetingTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MeetingTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MeetingTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MeetingTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Meeting or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Meeting object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \DataModels\DataModels\Meeting) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MeetingTableMap::DATABASE_NAME);
            $criteria->add(MeetingTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = MeetingQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MeetingTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MeetingTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the meeting table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MeetingQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Meeting or Criteria object.
     *
     * @param mixed               $criteria Criteria or Meeting object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Meeting object
        }

        if ($criteria->containsKey(MeetingTableMap::COL_ID) && $criteria->keyContainsValue(MeetingTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MeetingTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = MeetingQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MeetingTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MeetingTableMap::buildTableMap();
