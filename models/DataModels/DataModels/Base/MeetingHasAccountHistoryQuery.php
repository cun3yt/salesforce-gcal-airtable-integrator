<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\MeetingHasAccountHistory as ChildMeetingHasAccountHistory;
use DataModels\DataModels\MeetingHasAccountHistoryQuery as ChildMeetingHasAccountHistoryQuery;
use DataModels\DataModels\Map\MeetingHasAccountHistoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'meeting_has_account_history' table.
 *
 *
 *
 * @method     ChildMeetingHasAccountHistoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMeetingHasAccountHistoryQuery orderByMeetingId($order = Criteria::ASC) Order by the meeting_id column
 * @method     ChildMeetingHasAccountHistoryQuery orderByAccountHistoryId($order = Criteria::ASC) Order by the account_history_id column
 * @method     ChildMeetingHasAccountHistoryQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildMeetingHasAccountHistoryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildMeetingHasAccountHistoryQuery groupById() Group by the id column
 * @method     ChildMeetingHasAccountHistoryQuery groupByMeetingId() Group by the meeting_id column
 * @method     ChildMeetingHasAccountHistoryQuery groupByAccountHistoryId() Group by the account_history_id column
 * @method     ChildMeetingHasAccountHistoryQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildMeetingHasAccountHistoryQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildMeetingHasAccountHistoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMeetingHasAccountHistoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMeetingHasAccountHistoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMeetingHasAccountHistoryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMeetingHasAccountHistoryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMeetingHasAccountHistoryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMeetingHasAccountHistory findOne(ConnectionInterface $con = null) Return the first ChildMeetingHasAccountHistory matching the query
 * @method     ChildMeetingHasAccountHistory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMeetingHasAccountHistory matching the query, or a new ChildMeetingHasAccountHistory object populated from the query conditions when no match is found
 *
 * @method     ChildMeetingHasAccountHistory findOneById(int $id) Return the first ChildMeetingHasAccountHistory filtered by the id column
 * @method     ChildMeetingHasAccountHistory findOneByMeetingId(int $meeting_id) Return the first ChildMeetingHasAccountHistory filtered by the meeting_id column
 * @method     ChildMeetingHasAccountHistory findOneByAccountHistoryId(int $account_history_id) Return the first ChildMeetingHasAccountHistory filtered by the account_history_id column
 * @method     ChildMeetingHasAccountHistory findOneByCreatedAt(string $created_at) Return the first ChildMeetingHasAccountHistory filtered by the created_at column
 * @method     ChildMeetingHasAccountHistory findOneByUpdatedAt(string $updated_at) Return the first ChildMeetingHasAccountHistory filtered by the updated_at column *

 * @method     ChildMeetingHasAccountHistory requirePk($key, ConnectionInterface $con = null) Return the ChildMeetingHasAccountHistory by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeetingHasAccountHistory requireOne(ConnectionInterface $con = null) Return the first ChildMeetingHasAccountHistory matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMeetingHasAccountHistory requireOneById(int $id) Return the first ChildMeetingHasAccountHistory filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeetingHasAccountHistory requireOneByMeetingId(int $meeting_id) Return the first ChildMeetingHasAccountHistory filtered by the meeting_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeetingHasAccountHistory requireOneByAccountHistoryId(int $account_history_id) Return the first ChildMeetingHasAccountHistory filtered by the account_history_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeetingHasAccountHistory requireOneByCreatedAt(string $created_at) Return the first ChildMeetingHasAccountHistory filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMeetingHasAccountHistory requireOneByUpdatedAt(string $updated_at) Return the first ChildMeetingHasAccountHistory filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMeetingHasAccountHistory[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMeetingHasAccountHistory objects based on current ModelCriteria
 * @method     ChildMeetingHasAccountHistory[]|ObjectCollection findById(int $id) Return ChildMeetingHasAccountHistory objects filtered by the id column
 * @method     ChildMeetingHasAccountHistory[]|ObjectCollection findByMeetingId(int $meeting_id) Return ChildMeetingHasAccountHistory objects filtered by the meeting_id column
 * @method     ChildMeetingHasAccountHistory[]|ObjectCollection findByAccountHistoryId(int $account_history_id) Return ChildMeetingHasAccountHistory objects filtered by the account_history_id column
 * @method     ChildMeetingHasAccountHistory[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildMeetingHasAccountHistory objects filtered by the created_at column
 * @method     ChildMeetingHasAccountHistory[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildMeetingHasAccountHistory objects filtered by the updated_at column
 * @method     ChildMeetingHasAccountHistory[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MeetingHasAccountHistoryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\MeetingHasAccountHistoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\MeetingHasAccountHistory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMeetingHasAccountHistoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMeetingHasAccountHistoryQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMeetingHasAccountHistoryQuery) {
            return $criteria;
        }
        $query = new ChildMeetingHasAccountHistoryQuery();
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
     * @return ChildMeetingHasAccountHistory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MeetingHasAccountHistoryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MeetingHasAccountHistoryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildMeetingHasAccountHistory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, meeting_id, account_history_id, created_at, updated_at FROM meeting_has_account_history WHERE id = :p0';
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
            /** @var ChildMeetingHasAccountHistory $obj */
            $obj = new ChildMeetingHasAccountHistory();
            $obj->hydrate($row);
            MeetingHasAccountHistoryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildMeetingHasAccountHistory|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_ID, $id, $comparison);
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
     * @param     mixed $meetingId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByMeetingId($meetingId = null, $comparison = null)
    {
        if (is_array($meetingId)) {
            $useMinMax = false;
            if (isset($meetingId['min'])) {
                $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_MEETING_ID, $meetingId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($meetingId['max'])) {
                $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_MEETING_ID, $meetingId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_MEETING_ID, $meetingId, $comparison);
    }

    /**
     * Filter the query on the account_history_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAccountHistoryId(1234); // WHERE account_history_id = 1234
     * $query->filterByAccountHistoryId(array(12, 34)); // WHERE account_history_id IN (12, 34)
     * $query->filterByAccountHistoryId(array('min' => 12)); // WHERE account_history_id > 12
     * </code>
     *
     * @param     mixed $accountHistoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByAccountHistoryId($accountHistoryId = null, $comparison = null)
    {
        if (is_array($accountHistoryId)) {
            $useMinMax = false;
            if (isset($accountHistoryId['min'])) {
                $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_ACCOUNT_HISTORY_ID, $accountHistoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accountHistoryId['max'])) {
                $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_ACCOUNT_HISTORY_ID, $accountHistoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_ACCOUNT_HISTORY_ID, $accountHistoryId, $comparison);
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
     * @return $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMeetingHasAccountHistory $meetingHasAccountHistory Object to remove from the list of results
     *
     * @return $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function prune($meetingHasAccountHistory = null)
    {
        if ($meetingHasAccountHistory) {
            $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_ID, $meetingHasAccountHistory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the meeting_has_account_history table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingHasAccountHistoryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MeetingHasAccountHistoryTableMap::clearInstancePool();
            MeetingHasAccountHistoryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingHasAccountHistoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MeetingHasAccountHistoryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MeetingHasAccountHistoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MeetingHasAccountHistoryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(MeetingHasAccountHistoryTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(MeetingHasAccountHistoryTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(MeetingHasAccountHistoryTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(MeetingHasAccountHistoryTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildMeetingHasAccountHistoryQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(MeetingHasAccountHistoryTableMap::COL_CREATED_AT);
    }

} // MeetingHasAccountHistoryQuery
