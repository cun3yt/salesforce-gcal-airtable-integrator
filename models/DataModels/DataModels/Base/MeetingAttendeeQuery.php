<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\MeetingAttendee as ChildMeetingAttendee;
use DataModels\DataModels\MeetingAttendeeQuery as ChildMeetingAttendeeQuery;
use DataModels\DataModels\Map\MeetingAttendeeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'meeting_attendee' table.
 *
 *
 *
 * @method     ChildMeetingAttendeeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMeetingAttendeeQuery orderByDescendantClass($order = Criteria::ASC) Order by the descendant_class column
 * @method     ChildMeetingAttendeeQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildMeetingAttendeeQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildMeetingAttendeeQuery groupById() Group by the id column
 * @method     ChildMeetingAttendeeQuery groupByDescendantClass() Group by the descendant_class column
 * @method     ChildMeetingAttendeeQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildMeetingAttendeeQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildMeetingAttendeeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMeetingAttendeeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMeetingAttendeeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMeetingAttendeeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMeetingAttendeeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMeetingAttendeeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMeetingAttendeeQuery leftJoinMeetingRelatedByEventOwnerId($relationAlias = null) Adds a LEFT JOIN clause to the query using the MeetingRelatedByEventOwnerId relation
 * @method     ChildMeetingAttendeeQuery rightJoinMeetingRelatedByEventOwnerId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MeetingRelatedByEventOwnerId relation
 * @method     ChildMeetingAttendeeQuery innerJoinMeetingRelatedByEventOwnerId($relationAlias = null) Adds a INNER JOIN clause to the query using the MeetingRelatedByEventOwnerId relation
 *
 * @method     ChildMeetingAttendeeQuery joinWithMeetingRelatedByEventOwnerId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MeetingRelatedByEventOwnerId relation
 *
 * @method     ChildMeetingAttendeeQuery leftJoinWithMeetingRelatedByEventOwnerId() Adds a LEFT JOIN clause and with to the query using the MeetingRelatedByEventOwnerId relation
 * @method     ChildMeetingAttendeeQuery rightJoinWithMeetingRelatedByEventOwnerId() Adds a RIGHT JOIN clause and with to the query using the MeetingRelatedByEventOwnerId relation
 * @method     ChildMeetingAttendeeQuery innerJoinWithMeetingRelatedByEventOwnerId() Adds a INNER JOIN clause and with to the query using the MeetingRelatedByEventOwnerId relation
 *
 * @method     ChildMeetingAttendeeQuery leftJoinMeetingRelatedByEventCreatorId($relationAlias = null) Adds a LEFT JOIN clause to the query using the MeetingRelatedByEventCreatorId relation
 * @method     ChildMeetingAttendeeQuery rightJoinMeetingRelatedByEventCreatorId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MeetingRelatedByEventCreatorId relation
 * @method     ChildMeetingAttendeeQuery innerJoinMeetingRelatedByEventCreatorId($relationAlias = null) Adds a INNER JOIN clause to the query using the MeetingRelatedByEventCreatorId relation
 *
 * @method     ChildMeetingAttendeeQuery joinWithMeetingRelatedByEventCreatorId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MeetingRelatedByEventCreatorId relation
 *
 * @method     ChildMeetingAttendeeQuery leftJoinWithMeetingRelatedByEventCreatorId() Adds a LEFT JOIN clause and with to the query using the MeetingRelatedByEventCreatorId relation
 * @method     ChildMeetingAttendeeQuery rightJoinWithMeetingRelatedByEventCreatorId() Adds a RIGHT JOIN clause and with to the query using the MeetingRelatedByEventCreatorId relation
 * @method     ChildMeetingAttendeeQuery innerJoinWithMeetingRelatedByEventCreatorId() Adds a INNER JOIN clause and with to the query using the MeetingRelatedByEventCreatorId relation
 *
 * @method     ChildMeetingAttendeeQuery leftJoinMeetingHasAttendee($relationAlias = null) Adds a LEFT JOIN clause to the query using the MeetingHasAttendee relation
 * @method     ChildMeetingAttendeeQuery rightJoinMeetingHasAttendee($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MeetingHasAttendee relation
 * @method     ChildMeetingAttendeeQuery innerJoinMeetingHasAttendee($relationAlias = null) Adds a INNER JOIN clause to the query using the MeetingHasAttendee relation
 *
 * @method     ChildMeetingAttendeeQuery joinWithMeetingHasAttendee($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MeetingHasAttendee relation
 *
 * @method     ChildMeetingAttendeeQuery leftJoinWithMeetingHasAttendee() Adds a LEFT JOIN clause and with to the query using the MeetingHasAttendee relation
 * @method     ChildMeetingAttendeeQuery rightJoinWithMeetingHasAttendee() Adds a RIGHT JOIN clause and with to the query using the MeetingHasAttendee relation
 * @method     ChildMeetingAttendeeQuery innerJoinWithMeetingHasAttendee() Adds a INNER JOIN clause and with to the query using the MeetingHasAttendee relation
 *
 * @method     ChildMeetingAttendeeQuery leftJoinContact($relationAlias = null) Adds a LEFT JOIN clause to the query using the Contact relation
 * @method     ChildMeetingAttendeeQuery rightJoinContact($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Contact relation
 * @method     ChildMeetingAttendeeQuery innerJoinContact($relationAlias = null) Adds a INNER JOIN clause to the query using the Contact relation
 *
 * @method     ChildMeetingAttendeeQuery joinWithContact($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Contact relation
 *
 * @method     ChildMeetingAttendeeQuery leftJoinWithContact() Adds a LEFT JOIN clause and with to the query using the Contact relation
 * @method     ChildMeetingAttendeeQuery rightJoinWithContact() Adds a RIGHT JOIN clause and with to the query using the Contact relation
 * @method     ChildMeetingAttendeeQuery innerJoinWithContact() Adds a INNER JOIN clause and with to the query using the Contact relation
 *
 * @method     ChildMeetingAttendeeQuery leftJoinClientCalendarUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the ClientCalendarUser relation
 * @method     ChildMeetingAttendeeQuery rightJoinClientCalendarUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ClientCalendarUser relation
 * @method     ChildMeetingAttendeeQuery innerJoinClientCalendarUser($relationAlias = null) Adds a INNER JOIN clause to the query using the ClientCalendarUser relation
 *
 * @method     ChildMeetingAttendeeQuery joinWithClientCalendarUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ClientCalendarUser relation
 *
 * @method     ChildMeetingAttendeeQuery leftJoinWithClientCalendarUser() Adds a LEFT JOIN clause and with to the query using the ClientCalendarUser relation
 * @method     ChildMeetingAttendeeQuery rightJoinWithClientCalendarUser() Adds a RIGHT JOIN clause and with to the query using the ClientCalendarUser relation
 * @method     ChildMeetingAttendeeQuery innerJoinWithClientCalendarUser() Adds a INNER JOIN clause and with to the query using the ClientCalendarUser relation
 *
 * @method     \DataModels\DataModels\MeetingQuery|\DataModels\DataModels\MeetingHasAttendeeQuery|\DataModels\DataModels\ContactQuery|\DataModels\DataModels\ClientCalendarUserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMeetingAttendee findOne(ConnectionInterface $con = null) Return the first ChildMeetingAttendee matching the query
 * @method     ChildMeetingAttendee findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMeetingAttendee matching the query, or a new ChildMeetingAttendee object populated from the query conditions when no match is found
 *
 * @method     ChildMeetingAttendee findOneById(int $id) Return the first ChildMeetingAttendee filtered by the id column
 * @method     ChildMeetingAttendee findOneByDescendantClass(string $descendant_class) Return the first ChildMeetingAttendee filtered by the descendant_class column
 * @method     ChildMeetingAttendee findOneByCreatedAt(string $created_at) Return the first ChildMeetingAttendee filtered by the created_at column
 * @method     ChildMeetingAttendee findOneByUpdatedAt(string $updated_at) Return the first ChildMeetingAttendee filtered by the updated_at column *

 * @method     ChildMeetingAttendee requirePk($key, ConnectionInterface $con = null) Return the ChildMeetingAttendee by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeetingAttendee requireOne(ConnectionInterface $con = null) Return the first ChildMeetingAttendee matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMeetingAttendee requireOneById(int $id) Return the first ChildMeetingAttendee filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeetingAttendee requireOneByDescendantClass(string $descendant_class) Return the first ChildMeetingAttendee filtered by the descendant_class column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeetingAttendee requireOneByCreatedAt(string $created_at) Return the first ChildMeetingAttendee filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeetingAttendee requireOneByUpdatedAt(string $updated_at) Return the first ChildMeetingAttendee filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMeetingAttendee[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMeetingAttendee objects based on current ModelCriteria
 * @method     ChildMeetingAttendee[]|ObjectCollection findById(int $id) Return ChildMeetingAttendee objects filtered by the id column
 * @method     ChildMeetingAttendee[]|ObjectCollection findByDescendantClass(string $descendant_class) Return ChildMeetingAttendee objects filtered by the descendant_class column
 * @method     ChildMeetingAttendee[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildMeetingAttendee objects filtered by the created_at column
 * @method     ChildMeetingAttendee[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildMeetingAttendee objects filtered by the updated_at column
 * @method     ChildMeetingAttendee[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MeetingAttendeeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\MeetingAttendeeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\MeetingAttendee', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMeetingAttendeeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMeetingAttendeeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMeetingAttendeeQuery) {
            return $criteria;
        }
        $query = new ChildMeetingAttendeeQuery();
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
     * @return ChildMeetingAttendee|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MeetingAttendeeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MeetingAttendeeTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildMeetingAttendee A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, descendant_class, created_at, updated_at FROM meeting_attendee WHERE id = :p0';
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
            /** @var ChildMeetingAttendee $obj */
            $obj = new ChildMeetingAttendee();
            $obj->hydrate($row);
            MeetingAttendeeTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildMeetingAttendee|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MeetingAttendeeTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MeetingAttendeeTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MeetingAttendeeTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MeetingAttendeeTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingAttendeeTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the descendant_class column
     *
     * Example usage:
     * <code>
     * $query->filterByDescendantClass('fooValue');   // WHERE descendant_class = 'fooValue'
     * $query->filterByDescendantClass('%fooValue%', Criteria::LIKE); // WHERE descendant_class LIKE '%fooValue%'
     * </code>
     *
     * @param     string $descendantClass The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function filterByDescendantClass($descendantClass = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($descendantClass)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingAttendeeTableMap::COL_DESCENDANT_CLASS, $descendantClass, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MeetingAttendeeTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MeetingAttendeeTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingAttendeeTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(MeetingAttendeeTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MeetingAttendeeTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingAttendeeTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \DataModels\DataModels\Meeting object
     *
     * @param \DataModels\DataModels\Meeting|ObjectCollection $meeting the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function filterByMeetingRelatedByEventOwnerId($meeting, $comparison = null)
    {
        if ($meeting instanceof \DataModels\DataModels\Meeting) {
            return $this
                ->addUsingAlias(MeetingAttendeeTableMap::COL_ID, $meeting->getEventOwnerId(), $comparison);
        } elseif ($meeting instanceof ObjectCollection) {
            return $this
                ->useMeetingRelatedByEventOwnerIdQuery()
                ->filterByPrimaryKeys($meeting->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMeetingRelatedByEventOwnerId() only accepts arguments of type \DataModels\DataModels\Meeting or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MeetingRelatedByEventOwnerId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function joinMeetingRelatedByEventOwnerId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MeetingRelatedByEventOwnerId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'MeetingRelatedByEventOwnerId');
        }

        return $this;
    }

    /**
     * Use the MeetingRelatedByEventOwnerId relation Meeting object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\MeetingQuery A secondary query class using the current class as primary query
     */
    public function useMeetingRelatedByEventOwnerIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMeetingRelatedByEventOwnerId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MeetingRelatedByEventOwnerId', '\DataModels\DataModels\MeetingQuery');
    }

    /**
     * Filter the query by a related \DataModels\DataModels\Meeting object
     *
     * @param \DataModels\DataModels\Meeting|ObjectCollection $meeting the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function filterByMeetingRelatedByEventCreatorId($meeting, $comparison = null)
    {
        if ($meeting instanceof \DataModels\DataModels\Meeting) {
            return $this
                ->addUsingAlias(MeetingAttendeeTableMap::COL_ID, $meeting->getEventCreatorId(), $comparison);
        } elseif ($meeting instanceof ObjectCollection) {
            return $this
                ->useMeetingRelatedByEventCreatorIdQuery()
                ->filterByPrimaryKeys($meeting->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMeetingRelatedByEventCreatorId() only accepts arguments of type \DataModels\DataModels\Meeting or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MeetingRelatedByEventCreatorId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function joinMeetingRelatedByEventCreatorId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MeetingRelatedByEventCreatorId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'MeetingRelatedByEventCreatorId');
        }

        return $this;
    }

    /**
     * Use the MeetingRelatedByEventCreatorId relation Meeting object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\MeetingQuery A secondary query class using the current class as primary query
     */
    public function useMeetingRelatedByEventCreatorIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMeetingRelatedByEventCreatorId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MeetingRelatedByEventCreatorId', '\DataModels\DataModels\MeetingQuery');
    }

    /**
     * Filter the query by a related \DataModels\DataModels\MeetingHasAttendee object
     *
     * @param \DataModels\DataModels\MeetingHasAttendee|ObjectCollection $meetingHasAttendee the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function filterByMeetingHasAttendee($meetingHasAttendee, $comparison = null)
    {
        if ($meetingHasAttendee instanceof \DataModels\DataModels\MeetingHasAttendee) {
            return $this
                ->addUsingAlias(MeetingAttendeeTableMap::COL_ID, $meetingHasAttendee->getMeetingAttendeeId(), $comparison);
        } elseif ($meetingHasAttendee instanceof ObjectCollection) {
            return $this
                ->useMeetingHasAttendeeQuery()
                ->filterByPrimaryKeys($meetingHasAttendee->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMeetingHasAttendee() only accepts arguments of type \DataModels\DataModels\MeetingHasAttendee or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MeetingHasAttendee relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function joinMeetingHasAttendee($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MeetingHasAttendee');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'MeetingHasAttendee');
        }

        return $this;
    }

    /**
     * Use the MeetingHasAttendee relation MeetingHasAttendee object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\MeetingHasAttendeeQuery A secondary query class using the current class as primary query
     */
    public function useMeetingHasAttendeeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMeetingHasAttendee($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MeetingHasAttendee', '\DataModels\DataModels\MeetingHasAttendeeQuery');
    }

    /**
     * Filter the query by a related \DataModels\DataModels\Contact object
     *
     * @param \DataModels\DataModels\Contact|ObjectCollection $contact the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function filterByContact($contact, $comparison = null)
    {
        if ($contact instanceof \DataModels\DataModels\Contact) {
            return $this
                ->addUsingAlias(MeetingAttendeeTableMap::COL_ID, $contact->getId(), $comparison);
        } elseif ($contact instanceof ObjectCollection) {
            return $this
                ->useContactQuery()
                ->filterByPrimaryKeys($contact->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByContact() only accepts arguments of type \DataModels\DataModels\Contact or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Contact relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function joinContact($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Contact');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Contact');
        }

        return $this;
    }

    /**
     * Use the Contact relation Contact object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\ContactQuery A secondary query class using the current class as primary query
     */
    public function useContactQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinContact($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Contact', '\DataModels\DataModels\ContactQuery');
    }

    /**
     * Filter the query by a related \DataModels\DataModels\ClientCalendarUser object
     *
     * @param \DataModels\DataModels\ClientCalendarUser|ObjectCollection $clientCalendarUser the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function filterByClientCalendarUser($clientCalendarUser, $comparison = null)
    {
        if ($clientCalendarUser instanceof \DataModels\DataModels\ClientCalendarUser) {
            return $this
                ->addUsingAlias(MeetingAttendeeTableMap::COL_ID, $clientCalendarUser->getId(), $comparison);
        } elseif ($clientCalendarUser instanceof ObjectCollection) {
            return $this
                ->useClientCalendarUserQuery()
                ->filterByPrimaryKeys($clientCalendarUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByClientCalendarUser() only accepts arguments of type \DataModels\DataModels\ClientCalendarUser or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ClientCalendarUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function joinClientCalendarUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ClientCalendarUser');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ClientCalendarUser');
        }

        return $this;
    }

    /**
     * Use the ClientCalendarUser relation ClientCalendarUser object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\ClientCalendarUserQuery A secondary query class using the current class as primary query
     */
    public function useClientCalendarUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinClientCalendarUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ClientCalendarUser', '\DataModels\DataModels\ClientCalendarUserQuery');
    }

    /**
     * Filter the query by a related Meeting object
     * using the meeting_has_attendee table as cross reference
     *
     * @param Meeting $meeting the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function filterByMeeting($meeting, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useMeetingHasAttendeeQuery()
            ->filterByMeeting($meeting, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMeetingAttendee $meetingAttendee Object to remove from the list of results
     *
     * @return $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function prune($meetingAttendee = null)
    {
        if ($meetingAttendee) {
            $this->addUsingAlias(MeetingAttendeeTableMap::COL_ID, $meetingAttendee->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the meeting_attendee table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingAttendeeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MeetingAttendeeTableMap::clearInstancePool();
            MeetingAttendeeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingAttendeeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MeetingAttendeeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MeetingAttendeeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MeetingAttendeeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(MeetingAttendeeTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(MeetingAttendeeTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(MeetingAttendeeTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(MeetingAttendeeTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(MeetingAttendeeTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildMeetingAttendeeQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(MeetingAttendeeTableMap::COL_CREATED_AT);
    }

} // MeetingAttendeeQuery
