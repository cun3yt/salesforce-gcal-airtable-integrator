<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\MeetingHasAttendee as ChildMeetingHasAttendee;
use DataModels\DataModels\MeetingHasAttendeeQuery as ChildMeetingHasAttendeeQuery;
use DataModels\DataModels\Map\MeetingHasAttendeeTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'meeting_has_attendee' table.
 *
 *
 *
 * @method     ChildMeetingHasAttendeeQuery orderByMeetingId($order = Criteria::ASC) Order by the meeting_id column
 * @method     ChildMeetingHasAttendeeQuery orderByMeetingAttendeeId($order = Criteria::ASC) Order by the meeting_attendee_id column
 *
 * @method     ChildMeetingHasAttendeeQuery groupByMeetingId() Group by the meeting_id column
 * @method     ChildMeetingHasAttendeeQuery groupByMeetingAttendeeId() Group by the meeting_attendee_id column
 *
 * @method     ChildMeetingHasAttendeeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMeetingHasAttendeeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMeetingHasAttendeeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMeetingHasAttendeeQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMeetingHasAttendeeQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMeetingHasAttendeeQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMeetingHasAttendeeQuery leftJoinMeeting($relationAlias = null) Adds a LEFT JOIN clause to the query using the Meeting relation
 * @method     ChildMeetingHasAttendeeQuery rightJoinMeeting($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Meeting relation
 * @method     ChildMeetingHasAttendeeQuery innerJoinMeeting($relationAlias = null) Adds a INNER JOIN clause to the query using the Meeting relation
 *
 * @method     ChildMeetingHasAttendeeQuery joinWithMeeting($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Meeting relation
 *
 * @method     ChildMeetingHasAttendeeQuery leftJoinWithMeeting() Adds a LEFT JOIN clause and with to the query using the Meeting relation
 * @method     ChildMeetingHasAttendeeQuery rightJoinWithMeeting() Adds a RIGHT JOIN clause and with to the query using the Meeting relation
 * @method     ChildMeetingHasAttendeeQuery innerJoinWithMeeting() Adds a INNER JOIN clause and with to the query using the Meeting relation
 *
 * @method     ChildMeetingHasAttendeeQuery leftJoinMeetingAttendee($relationAlias = null) Adds a LEFT JOIN clause to the query using the MeetingAttendee relation
 * @method     ChildMeetingHasAttendeeQuery rightJoinMeetingAttendee($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MeetingAttendee relation
 * @method     ChildMeetingHasAttendeeQuery innerJoinMeetingAttendee($relationAlias = null) Adds a INNER JOIN clause to the query using the MeetingAttendee relation
 *
 * @method     ChildMeetingHasAttendeeQuery joinWithMeetingAttendee($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MeetingAttendee relation
 *
 * @method     ChildMeetingHasAttendeeQuery leftJoinWithMeetingAttendee() Adds a LEFT JOIN clause and with to the query using the MeetingAttendee relation
 * @method     ChildMeetingHasAttendeeQuery rightJoinWithMeetingAttendee() Adds a RIGHT JOIN clause and with to the query using the MeetingAttendee relation
 * @method     ChildMeetingHasAttendeeQuery innerJoinWithMeetingAttendee() Adds a INNER JOIN clause and with to the query using the MeetingAttendee relation
 *
 * @method     \DataModels\DataModels\MeetingQuery|\DataModels\DataModels\MeetingAttendeeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMeetingHasAttendee findOne(ConnectionInterface $con = null) Return the first ChildMeetingHasAttendee matching the query
 * @method     ChildMeetingHasAttendee findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMeetingHasAttendee matching the query, or a new ChildMeetingHasAttendee object populated from the query conditions when no match is found
 *
 * @method     ChildMeetingHasAttendee findOneByMeetingId(int $meeting_id) Return the first ChildMeetingHasAttendee filtered by the meeting_id column
 * @method     ChildMeetingHasAttendee findOneByMeetingAttendeeId(int $meeting_attendee_id) Return the first ChildMeetingHasAttendee filtered by the meeting_attendee_id column *

 * @method     ChildMeetingHasAttendee requirePk($key, ConnectionInterface $con = null) Return the ChildMeetingHasAttendee by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeetingHasAttendee requireOne(ConnectionInterface $con = null) Return the first ChildMeetingHasAttendee matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMeetingHasAttendee requireOneByMeetingId(int $meeting_id) Return the first ChildMeetingHasAttendee filtered by the meeting_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeetingHasAttendee requireOneByMeetingAttendeeId(int $meeting_attendee_id) Return the first ChildMeetingHasAttendee filtered by the meeting_attendee_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMeetingHasAttendee[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMeetingHasAttendee objects based on current ModelCriteria
 * @method     ChildMeetingHasAttendee[]|ObjectCollection findByMeetingId(int $meeting_id) Return ChildMeetingHasAttendee objects filtered by the meeting_id column
 * @method     ChildMeetingHasAttendee[]|ObjectCollection findByMeetingAttendeeId(int $meeting_attendee_id) Return ChildMeetingHasAttendee objects filtered by the meeting_attendee_id column
 * @method     ChildMeetingHasAttendee[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MeetingHasAttendeeQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\MeetingHasAttendeeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\MeetingHasAttendee', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMeetingHasAttendeeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMeetingHasAttendeeQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMeetingHasAttendeeQuery) {
            return $criteria;
        }
        $query = new ChildMeetingHasAttendeeQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$meeting_id, $meeting_attendee_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMeetingHasAttendee|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MeetingHasAttendeeTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MeetingHasAttendeeTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildMeetingHasAttendee A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT meeting_id, meeting_attendee_id FROM meeting_has_attendee WHERE meeting_id = :p0 AND meeting_attendee_id = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildMeetingHasAttendee $obj */
            $obj = new ChildMeetingHasAttendee();
            $obj->hydrate($row);
            MeetingHasAttendeeTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildMeetingHasAttendee|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildMeetingHasAttendeeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(MeetingHasAttendeeTableMap::COL_MEETING_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(MeetingHasAttendeeTableMap::COL_MEETING_ATTENDEE_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMeetingHasAttendeeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(MeetingHasAttendeeTableMap::COL_MEETING_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(MeetingHasAttendeeTableMap::COL_MEETING_ATTENDEE_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the meeting_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMeetingId(1234); // WHERE meeting_id = 1234
     * $query->filterByMeetingId(array(12, 34)); // WHERE meeting_id IN (12, 34)
     * $query->filterByMeetingId(array('min' => 12)); // WHERE meeting_id > 12
     * </code>
     *
     * @see       filterByMeeting()
     *
     * @param     mixed $meetingId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingHasAttendeeQuery The current query, for fluid interface
     */
    public function filterByMeetingId($meetingId = null, $comparison = null)
    {
        if (is_array($meetingId)) {
            $useMinMax = false;
            if (isset($meetingId['min'])) {
                $this->addUsingAlias(MeetingHasAttendeeTableMap::COL_MEETING_ID, $meetingId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($meetingId['max'])) {
                $this->addUsingAlias(MeetingHasAttendeeTableMap::COL_MEETING_ID, $meetingId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingHasAttendeeTableMap::COL_MEETING_ID, $meetingId, $comparison);
    }

    /**
     * Filter the query on the meeting_attendee_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMeetingAttendeeId(1234); // WHERE meeting_attendee_id = 1234
     * $query->filterByMeetingAttendeeId(array(12, 34)); // WHERE meeting_attendee_id IN (12, 34)
     * $query->filterByMeetingAttendeeId(array('min' => 12)); // WHERE meeting_attendee_id > 12
     * </code>
     *
     * @see       filterByMeetingAttendee()
     *
     * @param     mixed $meetingAttendeeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingHasAttendeeQuery The current query, for fluid interface
     */
    public function filterByMeetingAttendeeId($meetingAttendeeId = null, $comparison = null)
    {
        if (is_array($meetingAttendeeId)) {
            $useMinMax = false;
            if (isset($meetingAttendeeId['min'])) {
                $this->addUsingAlias(MeetingHasAttendeeTableMap::COL_MEETING_ATTENDEE_ID, $meetingAttendeeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($meetingAttendeeId['max'])) {
                $this->addUsingAlias(MeetingHasAttendeeTableMap::COL_MEETING_ATTENDEE_ID, $meetingAttendeeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingHasAttendeeTableMap::COL_MEETING_ATTENDEE_ID, $meetingAttendeeId, $comparison);
    }

    /**
     * Filter the query by a related \DataModels\DataModels\Meeting object
     *
     * @param \DataModels\DataModels\Meeting|ObjectCollection $meeting The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMeetingHasAttendeeQuery The current query, for fluid interface
     */
    public function filterByMeeting($meeting, $comparison = null)
    {
        if ($meeting instanceof \DataModels\DataModels\Meeting) {
            return $this
                ->addUsingAlias(MeetingHasAttendeeTableMap::COL_MEETING_ID, $meeting->getId(), $comparison);
        } elseif ($meeting instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MeetingHasAttendeeTableMap::COL_MEETING_ID, $meeting->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMeeting() only accepts arguments of type \DataModels\DataModels\Meeting or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Meeting relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMeetingHasAttendeeQuery The current query, for fluid interface
     */
    public function joinMeeting($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Meeting');

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
            $this->addJoinObject($join, 'Meeting');
        }

        return $this;
    }

    /**
     * Use the Meeting relation Meeting object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\MeetingQuery A secondary query class using the current class as primary query
     */
    public function useMeetingQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMeeting($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Meeting', '\DataModels\DataModels\MeetingQuery');
    }

    /**
     * Filter the query by a related \DataModels\DataModels\MeetingAttendee object
     *
     * @param \DataModels\DataModels\MeetingAttendee|ObjectCollection $meetingAttendee The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMeetingHasAttendeeQuery The current query, for fluid interface
     */
    public function filterByMeetingAttendee($meetingAttendee, $comparison = null)
    {
        if ($meetingAttendee instanceof \DataModels\DataModels\MeetingAttendee) {
            return $this
                ->addUsingAlias(MeetingHasAttendeeTableMap::COL_MEETING_ATTENDEE_ID, $meetingAttendee->getId(), $comparison);
        } elseif ($meetingAttendee instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MeetingHasAttendeeTableMap::COL_MEETING_ATTENDEE_ID, $meetingAttendee->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMeetingAttendee() only accepts arguments of type \DataModels\DataModels\MeetingAttendee or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MeetingAttendee relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMeetingHasAttendeeQuery The current query, for fluid interface
     */
    public function joinMeetingAttendee($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MeetingAttendee');

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
            $this->addJoinObject($join, 'MeetingAttendee');
        }

        return $this;
    }

    /**
     * Use the MeetingAttendee relation MeetingAttendee object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\MeetingAttendeeQuery A secondary query class using the current class as primary query
     */
    public function useMeetingAttendeeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMeetingAttendee($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MeetingAttendee', '\DataModels\DataModels\MeetingAttendeeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMeetingHasAttendee $meetingHasAttendee Object to remove from the list of results
     *
     * @return $this|ChildMeetingHasAttendeeQuery The current query, for fluid interface
     */
    public function prune($meetingHasAttendee = null)
    {
        if ($meetingHasAttendee) {
            $this->addCond('pruneCond0', $this->getAliasedColName(MeetingHasAttendeeTableMap::COL_MEETING_ID), $meetingHasAttendee->getMeetingId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(MeetingHasAttendeeTableMap::COL_MEETING_ATTENDEE_ID), $meetingHasAttendee->getMeetingAttendeeId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the meeting_has_attendee table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingHasAttendeeTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MeetingHasAttendeeTableMap::clearInstancePool();
            MeetingHasAttendeeTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingHasAttendeeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MeetingHasAttendeeTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MeetingHasAttendeeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MeetingHasAttendeeTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MeetingHasAttendeeQuery
