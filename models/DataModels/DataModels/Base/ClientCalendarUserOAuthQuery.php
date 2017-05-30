<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\ClientCalendarUserOAuth as ChildClientCalendarUserOAuth;
use DataModels\DataModels\ClientCalendarUserOAuthQuery as ChildClientCalendarUserOAuthQuery;
use DataModels\DataModels\Map\ClientCalendarUserOAuthTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'client_calendar_user_oauth' table.
 *
 *
 *
 * @method     ChildClientCalendarUserOAuthQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildClientCalendarUserOAuthQuery orderByClientCalendarUserId($order = Criteria::ASC) Order by the client_calendar_user_id column
 * @method     ChildClientCalendarUserOAuthQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildClientCalendarUserOAuthQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildClientCalendarUserOAuthQuery orderByData($order = Criteria::ASC) Order by the data column
 * @method     ChildClientCalendarUserOAuthQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildClientCalendarUserOAuthQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildClientCalendarUserOAuthQuery groupById() Group by the id column
 * @method     ChildClientCalendarUserOAuthQuery groupByClientCalendarUserId() Group by the client_calendar_user_id column
 * @method     ChildClientCalendarUserOAuthQuery groupByType() Group by the type column
 * @method     ChildClientCalendarUserOAuthQuery groupByStatus() Group by the status column
 * @method     ChildClientCalendarUserOAuthQuery groupByData() Group by the data column
 * @method     ChildClientCalendarUserOAuthQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildClientCalendarUserOAuthQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildClientCalendarUserOAuthQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildClientCalendarUserOAuthQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildClientCalendarUserOAuthQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildClientCalendarUserOAuthQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildClientCalendarUserOAuthQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildClientCalendarUserOAuthQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildClientCalendarUserOAuthQuery leftJoinClientCalendarUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the ClientCalendarUser relation
 * @method     ChildClientCalendarUserOAuthQuery rightJoinClientCalendarUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ClientCalendarUser relation
 * @method     ChildClientCalendarUserOAuthQuery innerJoinClientCalendarUser($relationAlias = null) Adds a INNER JOIN clause to the query using the ClientCalendarUser relation
 *
 * @method     ChildClientCalendarUserOAuthQuery joinWithClientCalendarUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ClientCalendarUser relation
 *
 * @method     ChildClientCalendarUserOAuthQuery leftJoinWithClientCalendarUser() Adds a LEFT JOIN clause and with to the query using the ClientCalendarUser relation
 * @method     ChildClientCalendarUserOAuthQuery rightJoinWithClientCalendarUser() Adds a RIGHT JOIN clause and with to the query using the ClientCalendarUser relation
 * @method     ChildClientCalendarUserOAuthQuery innerJoinWithClientCalendarUser() Adds a INNER JOIN clause and with to the query using the ClientCalendarUser relation
 *
 * @method     \DataModels\DataModels\ClientCalendarUserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildClientCalendarUserOAuth findOne(ConnectionInterface $con = null) Return the first ChildClientCalendarUserOAuth matching the query
 * @method     ChildClientCalendarUserOAuth findOneOrCreate(ConnectionInterface $con = null) Return the first ChildClientCalendarUserOAuth matching the query, or a new ChildClientCalendarUserOAuth object populated from the query conditions when no match is found
 *
 * @method     ChildClientCalendarUserOAuth findOneById(int $id) Return the first ChildClientCalendarUserOAuth filtered by the id column
 * @method     ChildClientCalendarUserOAuth findOneByClientCalendarUserId(int $client_calendar_user_id) Return the first ChildClientCalendarUserOAuth filtered by the client_calendar_user_id column
 * @method     ChildClientCalendarUserOAuth findOneByType(string $type) Return the first ChildClientCalendarUserOAuth filtered by the type column
 * @method     ChildClientCalendarUserOAuth findOneByStatus(string $status) Return the first ChildClientCalendarUserOAuth filtered by the status column
 * @method     ChildClientCalendarUserOAuth findOneByData(string $data) Return the first ChildClientCalendarUserOAuth filtered by the data column
 * @method     ChildClientCalendarUserOAuth findOneByCreatedAt(string $created_at) Return the first ChildClientCalendarUserOAuth filtered by the created_at column
 * @method     ChildClientCalendarUserOAuth findOneByUpdatedAt(string $updated_at) Return the first ChildClientCalendarUserOAuth filtered by the updated_at column *

 * @method     ChildClientCalendarUserOAuth requirePk($key, ConnectionInterface $con = null) Return the ChildClientCalendarUserOAuth by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUserOAuth requireOne(ConnectionInterface $con = null) Return the first ChildClientCalendarUserOAuth matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildClientCalendarUserOAuth requireOneById(int $id) Return the first ChildClientCalendarUserOAuth filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUserOAuth requireOneByClientCalendarUserId(int $client_calendar_user_id) Return the first ChildClientCalendarUserOAuth filtered by the client_calendar_user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUserOAuth requireOneByType(string $type) Return the first ChildClientCalendarUserOAuth filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUserOAuth requireOneByStatus(string $status) Return the first ChildClientCalendarUserOAuth filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUserOAuth requireOneByData(string $data) Return the first ChildClientCalendarUserOAuth filtered by the data column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUserOAuth requireOneByCreatedAt(string $created_at) Return the first ChildClientCalendarUserOAuth filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUserOAuth requireOneByUpdatedAt(string $updated_at) Return the first ChildClientCalendarUserOAuth filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildClientCalendarUserOAuth[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildClientCalendarUserOAuth objects based on current ModelCriteria
 * @method     ChildClientCalendarUserOAuth[]|ObjectCollection findById(int $id) Return ChildClientCalendarUserOAuth objects filtered by the id column
 * @method     ChildClientCalendarUserOAuth[]|ObjectCollection findByClientCalendarUserId(int $client_calendar_user_id) Return ChildClientCalendarUserOAuth objects filtered by the client_calendar_user_id column
 * @method     ChildClientCalendarUserOAuth[]|ObjectCollection findByType(string $type) Return ChildClientCalendarUserOAuth objects filtered by the type column
 * @method     ChildClientCalendarUserOAuth[]|ObjectCollection findByStatus(string $status) Return ChildClientCalendarUserOAuth objects filtered by the status column
 * @method     ChildClientCalendarUserOAuth[]|ObjectCollection findByData(string $data) Return ChildClientCalendarUserOAuth objects filtered by the data column
 * @method     ChildClientCalendarUserOAuth[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildClientCalendarUserOAuth objects filtered by the created_at column
 * @method     ChildClientCalendarUserOAuth[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildClientCalendarUserOAuth objects filtered by the updated_at column
 * @method     ChildClientCalendarUserOAuth[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ClientCalendarUserOAuthQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\ClientCalendarUserOAuthQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\ClientCalendarUserOAuth', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildClientCalendarUserOAuthQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildClientCalendarUserOAuthQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildClientCalendarUserOAuthQuery) {
            return $criteria;
        }
        $query = new ChildClientCalendarUserOAuthQuery();
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
     * @return ChildClientCalendarUserOAuth|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ClientCalendarUserOAuthTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ClientCalendarUserOAuthTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildClientCalendarUserOAuth A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, client_calendar_user_id, type, status, data, created_at, updated_at FROM client_calendar_user_oauth WHERE id = :p0';
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
            /** @var ChildClientCalendarUserOAuth $obj */
            $obj = new ChildClientCalendarUserOAuth();
            $obj->hydrate($row);
            ClientCalendarUserOAuthTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildClientCalendarUserOAuth|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the client_calendar_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByClientCalendarUserId(1234); // WHERE client_calendar_user_id = 1234
     * $query->filterByClientCalendarUserId(array(12, 34)); // WHERE client_calendar_user_id IN (12, 34)
     * $query->filterByClientCalendarUserId(array('min' => 12)); // WHERE client_calendar_user_id > 12
     * </code>
     *
     * @see       filterByClientCalendarUser()
     *
     * @param     mixed $clientCalendarUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function filterByClientCalendarUserId($clientCalendarUserId = null, $comparison = null)
    {
        if (is_array($clientCalendarUserId)) {
            $useMinMax = false;
            if (isset($clientCalendarUserId['min'])) {
                $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_CLIENT_CALENDAR_USER_ID, $clientCalendarUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($clientCalendarUserId['max'])) {
                $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_CLIENT_CALENDAR_USER_ID, $clientCalendarUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_CLIENT_CALENDAR_USER_ID, $clientCalendarUserId, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus('fooValue');   // WHERE status = 'fooValue'
     * $query->filterByStatus('%fooValue%', Criteria::LIKE); // WHERE status LIKE '%fooValue%'
     * </code>
     *
     * @param     string $status The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($status)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the data column
     *
     * Example usage:
     * <code>
     * $query->filterByData('fooValue');   // WHERE data = 'fooValue'
     * $query->filterByData('%fooValue%', Criteria::LIKE); // WHERE data LIKE '%fooValue%'
     * </code>
     *
     * @param     string $data The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function filterByData($data = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($data)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_DATA, $data, $comparison);
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
     * @return $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \DataModels\DataModels\ClientCalendarUser object
     *
     * @param \DataModels\DataModels\ClientCalendarUser|ObjectCollection $clientCalendarUser The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function filterByClientCalendarUser($clientCalendarUser, $comparison = null)
    {
        if ($clientCalendarUser instanceof \DataModels\DataModels\ClientCalendarUser) {
            return $this
                ->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_CLIENT_CALENDAR_USER_ID, $clientCalendarUser->getId(), $comparison);
        } elseif ($clientCalendarUser instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_CLIENT_CALENDAR_USER_ID, $clientCalendarUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function joinClientCalendarUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useClientCalendarUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinClientCalendarUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ClientCalendarUser', '\DataModels\DataModels\ClientCalendarUserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildClientCalendarUserOAuth $clientCalendarUserOAuth Object to remove from the list of results
     *
     * @return $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function prune($clientCalendarUserOAuth = null)
    {
        if ($clientCalendarUserOAuth) {
            $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_ID, $clientCalendarUserOAuth->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the client_calendar_user_oauth table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ClientCalendarUserOAuthTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ClientCalendarUserOAuthTableMap::clearInstancePool();
            ClientCalendarUserOAuthTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ClientCalendarUserOAuthTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ClientCalendarUserOAuthTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ClientCalendarUserOAuthTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ClientCalendarUserOAuthTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ClientCalendarUserOAuthTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ClientCalendarUserOAuthTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ClientCalendarUserOAuthTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ClientCalendarUserOAuthTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildClientCalendarUserOAuthQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ClientCalendarUserOAuthTableMap::COL_CREATED_AT);
    }

} // ClientCalendarUserOAuthQuery
