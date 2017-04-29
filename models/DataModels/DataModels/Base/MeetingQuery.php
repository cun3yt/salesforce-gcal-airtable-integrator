<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\Meeting as ChildMeeting;
use DataModels\DataModels\MeetingQuery as ChildMeetingQuery;
use DataModels\DataModels\Map\MeetingTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'meeting' table.
 *
 *
 *
 * @method     ChildMeetingQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMeetingQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildMeetingQuery orderByEventDatetime($order = Criteria::ASC) Order by the event_datetime column
 * @method     ChildMeetingQuery orderByEventCreatorId($order = Criteria::ASC) Order by the event_creator_id column
 * @method     ChildMeetingQuery orderByEventOwnerId($order = Criteria::ASC) Order by the event_owner_id column
 * @method     ChildMeetingQuery orderByEventDescription($order = Criteria::ASC) Order by the event_description column
 * @method     ChildMeetingQuery orderByAccountId($order = Criteria::ASC) Order by the account_id column
 * @method     ChildMeetingQuery orderByAdditionalData($order = Criteria::ASC) Order by the additional_data column
 * @method     ChildMeetingQuery orderByCreated($order = Criteria::ASC) Order by the created column
 * @method     ChildMeetingQuery orderByUpdated($order = Criteria::ASC) Order by the updated column
 *
 * @method     ChildMeetingQuery groupById() Group by the id column
 * @method     ChildMeetingQuery groupByName() Group by the name column
 * @method     ChildMeetingQuery groupByEventDatetime() Group by the event_datetime column
 * @method     ChildMeetingQuery groupByEventCreatorId() Group by the event_creator_id column
 * @method     ChildMeetingQuery groupByEventOwnerId() Group by the event_owner_id column
 * @method     ChildMeetingQuery groupByEventDescription() Group by the event_description column
 * @method     ChildMeetingQuery groupByAccountId() Group by the account_id column
 * @method     ChildMeetingQuery groupByAdditionalData() Group by the additional_data column
 * @method     ChildMeetingQuery groupByCreated() Group by the created column
 * @method     ChildMeetingQuery groupByUpdated() Group by the updated column
 *
 * @method     ChildMeetingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMeetingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMeetingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMeetingQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMeetingQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMeetingQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMeeting findOne(ConnectionInterface $con = null) Return the first ChildMeeting matching the query
 * @method     ChildMeeting findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMeeting matching the query, or a new ChildMeeting object populated from the query conditions when no match is found
 *
 * @method     ChildMeeting findOneById(int $id) Return the first ChildMeeting filtered by the id column
 * @method     ChildMeeting findOneByName(string $name) Return the first ChildMeeting filtered by the name column
 * @method     ChildMeeting findOneByEventDatetime(string $event_datetime) Return the first ChildMeeting filtered by the event_datetime column
 * @method     ChildMeeting findOneByEventCreatorId(int $event_creator_id) Return the first ChildMeeting filtered by the event_creator_id column
 * @method     ChildMeeting findOneByEventOwnerId(int $event_owner_id) Return the first ChildMeeting filtered by the event_owner_id column
 * @method     ChildMeeting findOneByEventDescription(string $event_description) Return the first ChildMeeting filtered by the event_description column
 * @method     ChildMeeting findOneByAccountId(int $account_id) Return the first ChildMeeting filtered by the account_id column
 * @method     ChildMeeting findOneByAdditionalData(string $additional_data) Return the first ChildMeeting filtered by the additional_data column
 * @method     ChildMeeting findOneByCreated(string $created) Return the first ChildMeeting filtered by the created column
 * @method     ChildMeeting findOneByUpdated(string $updated) Return the first ChildMeeting filtered by the updated column *

 * @method     ChildMeeting requirePk($key, ConnectionInterface $con = null) Return the ChildMeeting by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeeting requireOne(ConnectionInterface $con = null) Return the first ChildMeeting matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMeeting requireOneById(int $id) Return the first ChildMeeting filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeeting requireOneByName(string $name) Return the first ChildMeeting filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeeting requireOneByEventDatetime(string $event_datetime) Return the first ChildMeeting filtered by the event_datetime column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeeting requireOneByEventCreatorId(int $event_creator_id) Return the first ChildMeeting filtered by the event_creator_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeeting requireOneByEventOwnerId(int $event_owner_id) Return the first ChildMeeting filtered by the event_owner_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeeting requireOneByEventDescription(string $event_description) Return the first ChildMeeting filtered by the event_description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeeting requireOneByAccountId(int $account_id) Return the first ChildMeeting filtered by the account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeeting requireOneByAdditionalData(string $additional_data) Return the first ChildMeeting filtered by the additional_data column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeeting requireOneByCreated(string $created) Return the first ChildMeeting filtered by the created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeeting requireOneByUpdated(string $updated) Return the first ChildMeeting filtered by the updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMeeting[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMeeting objects based on current ModelCriteria
 * @method     ChildMeeting[]|ObjectCollection findById(int $id) Return ChildMeeting objects filtered by the id column
 * @method     ChildMeeting[]|ObjectCollection findByName(string $name) Return ChildMeeting objects filtered by the name column
 * @method     ChildMeeting[]|ObjectCollection findByEventDatetime(string $event_datetime) Return ChildMeeting objects filtered by the event_datetime column
 * @method     ChildMeeting[]|ObjectCollection findByEventCreatorId(int $event_creator_id) Return ChildMeeting objects filtered by the event_creator_id column
 * @method     ChildMeeting[]|ObjectCollection findByEventOwnerId(int $event_owner_id) Return ChildMeeting objects filtered by the event_owner_id column
 * @method     ChildMeeting[]|ObjectCollection findByEventDescription(string $event_description) Return ChildMeeting objects filtered by the event_description column
 * @method     ChildMeeting[]|ObjectCollection findByAccountId(int $account_id) Return ChildMeeting objects filtered by the account_id column
 * @method     ChildMeeting[]|ObjectCollection findByAdditionalData(string $additional_data) Return ChildMeeting objects filtered by the additional_data column
 * @method     ChildMeeting[]|ObjectCollection findByCreated(string $created) Return ChildMeeting objects filtered by the created column
 * @method     ChildMeeting[]|ObjectCollection findByUpdated(string $updated) Return ChildMeeting objects filtered by the updated column
 * @method     ChildMeeting[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MeetingQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\MeetingQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\Meeting', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMeetingQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMeetingQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMeetingQuery) {
            return $criteria;
        }
        $query = new ChildMeetingQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMeeting|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MeetingTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MeetingTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMeeting A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, event_datetime, event_creator_id, event_owner_id, event_description, account_id, additional_data, created, updated FROM meeting WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildMeeting $obj */
            $obj = new ChildMeeting();
            $obj->hydrate($row);
            MeetingTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildMeeting|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MeetingTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MeetingTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MeetingTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MeetingTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the event_datetime column
     *
     * Example usage:
     * <code>
     * $query->filterByEventDatetime('2011-03-14'); // WHERE event_datetime = '2011-03-14'
     * $query->filterByEventDatetime('now'); // WHERE event_datetime = '2011-03-14'
     * $query->filterByEventDatetime(array('max' => 'yesterday')); // WHERE event_datetime > '2011-03-13'
     * </code>
     *
     * @param     mixed $eventDatetime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function filterByEventDatetime($eventDatetime = null, $comparison = null)
    {
        if (is_array($eventDatetime)) {
            $useMinMax = false;
            if (isset($eventDatetime['min'])) {
                $this->addUsingAlias(MeetingTableMap::COL_EVENT_DATETIME, $eventDatetime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventDatetime['max'])) {
                $this->addUsingAlias(MeetingTableMap::COL_EVENT_DATETIME, $eventDatetime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingTableMap::COL_EVENT_DATETIME, $eventDatetime, $comparison);
    }

    /**
     * Filter the query on the event_creator_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEventCreatorId(1234); // WHERE event_creator_id = 1234
     * $query->filterByEventCreatorId(array(12, 34)); // WHERE event_creator_id IN (12, 34)
     * $query->filterByEventCreatorId(array('min' => 12)); // WHERE event_creator_id > 12
     * </code>
     *
     * @param     mixed $eventCreatorId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function filterByEventCreatorId($eventCreatorId = null, $comparison = null)
    {
        if (is_array($eventCreatorId)) {
            $useMinMax = false;
            if (isset($eventCreatorId['min'])) {
                $this->addUsingAlias(MeetingTableMap::COL_EVENT_CREATOR_ID, $eventCreatorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventCreatorId['max'])) {
                $this->addUsingAlias(MeetingTableMap::COL_EVENT_CREATOR_ID, $eventCreatorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingTableMap::COL_EVENT_CREATOR_ID, $eventCreatorId, $comparison);
    }

    /**
     * Filter the query on the event_owner_id column
     *
     * Example usage:
     * <code>
     * $query->filterByEventOwnerId(1234); // WHERE event_owner_id = 1234
     * $query->filterByEventOwnerId(array(12, 34)); // WHERE event_owner_id IN (12, 34)
     * $query->filterByEventOwnerId(array('min' => 12)); // WHERE event_owner_id > 12
     * </code>
     *
     * @param     mixed $eventOwnerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function filterByEventOwnerId($eventOwnerId = null, $comparison = null)
    {
        if (is_array($eventOwnerId)) {
            $useMinMax = false;
            if (isset($eventOwnerId['min'])) {
                $this->addUsingAlias(MeetingTableMap::COL_EVENT_OWNER_ID, $eventOwnerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventOwnerId['max'])) {
                $this->addUsingAlias(MeetingTableMap::COL_EVENT_OWNER_ID, $eventOwnerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingTableMap::COL_EVENT_OWNER_ID, $eventOwnerId, $comparison);
    }

    /**
     * Filter the query on the event_description column
     *
     * Example usage:
     * <code>
     * $query->filterByEventDescription('fooValue');   // WHERE event_description = 'fooValue'
     * $query->filterByEventDescription('%fooValue%', Criteria::LIKE); // WHERE event_description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $eventDescription The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function filterByEventDescription($eventDescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eventDescription)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingTableMap::COL_EVENT_DESCRIPTION, $eventDescription, $comparison);
    }

    /**
     * Filter the query on the account_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAccountId(1234); // WHERE account_id = 1234
     * $query->filterByAccountId(array(12, 34)); // WHERE account_id IN (12, 34)
     * $query->filterByAccountId(array('min' => 12)); // WHERE account_id > 12
     * </code>
     *
     * @param     mixed $accountId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function filterByAccountId($accountId = null, $comparison = null)
    {
        if (is_array($accountId)) {
            $useMinMax = false;
            if (isset($accountId['min'])) {
                $this->addUsingAlias(MeetingTableMap::COL_ACCOUNT_ID, $accountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accountId['max'])) {
                $this->addUsingAlias(MeetingTableMap::COL_ACCOUNT_ID, $accountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingTableMap::COL_ACCOUNT_ID, $accountId, $comparison);
    }

    /**
     * Filter the query on the additional_data column
     *
     * Example usage:
     * <code>
     * $query->filterByAdditionalData('fooValue');   // WHERE additional_data = 'fooValue'
     * $query->filterByAdditionalData('%fooValue%', Criteria::LIKE); // WHERE additional_data LIKE '%fooValue%'
     * </code>
     *
     * @param     string $additionalData The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function filterByAdditionalData($additionalData = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($additionalData)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingTableMap::COL_ADDITIONAL_DATA, $additionalData, $comparison);
    }

    /**
     * Filter the query on the created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreated('2011-03-14'); // WHERE created = '2011-03-14'
     * $query->filterByCreated('now'); // WHERE created = '2011-03-14'
     * $query->filterByCreated(array('max' => 'yesterday')); // WHERE created > '2011-03-13'
     * </code>
     *
     * @param     mixed $created The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function filterByCreated($created = null, $comparison = null)
    {
        if (is_array($created)) {
            $useMinMax = false;
            if (isset($created['min'])) {
                $this->addUsingAlias(MeetingTableMap::COL_CREATED, $created['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($created['max'])) {
                $this->addUsingAlias(MeetingTableMap::COL_CREATED, $created['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingTableMap::COL_CREATED, $created, $comparison);
    }

    /**
     * Filter the query on the updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdated('2011-03-14'); // WHERE updated = '2011-03-14'
     * $query->filterByUpdated('now'); // WHERE updated = '2011-03-14'
     * $query->filterByUpdated(array('max' => 'yesterday')); // WHERE updated > '2011-03-13'
     * </code>
     *
     * @param     mixed $updated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function filterByUpdated($updated = null, $comparison = null)
    {
        if (is_array($updated)) {
            $useMinMax = false;
            if (isset($updated['min'])) {
                $this->addUsingAlias(MeetingTableMap::COL_UPDATED, $updated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updated['max'])) {
                $this->addUsingAlias(MeetingTableMap::COL_UPDATED, $updated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingTableMap::COL_UPDATED, $updated, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMeeting $meeting Object to remove from the list of results
     *
     * @return $this|ChildMeetingQuery The current query, for fluid interface
     */
    public function prune($meeting = null)
    {
        if ($meeting) {
            $this->addUsingAlias(MeetingTableMap::COL_ID, $meeting->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the meeting table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MeetingTableMap::clearInstancePool();
            MeetingTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MeetingTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MeetingTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MeetingTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MeetingQuery
