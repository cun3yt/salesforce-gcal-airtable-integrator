<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\Opportunity as ChildOpportunity;
use DataModels\DataModels\OpportunityQuery as ChildOpportunityQuery;
use DataModels\DataModels\Map\OpportunityTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'opportunity' table.
 *
 *
 *
 * @method     ChildOpportunityQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildOpportunityQuery orderByAccountId($order = Criteria::ASC) Order by the account_id column
 * @method     ChildOpportunityQuery orderBySFDCId($order = Criteria::ASC) Order by the sfdc_id column
 * @method     ChildOpportunityQuery orderBySFDCLastCheckTime($order = Criteria::ASC) Order by the sfdc_last_check_time column
 * @method     ChildOpportunityQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildOpportunityQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildOpportunityQuery groupById() Group by the id column
 * @method     ChildOpportunityQuery groupByAccountId() Group by the account_id column
 * @method     ChildOpportunityQuery groupBySFDCId() Group by the sfdc_id column
 * @method     ChildOpportunityQuery groupBySFDCLastCheckTime() Group by the sfdc_last_check_time column
 * @method     ChildOpportunityQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildOpportunityQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildOpportunityQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOpportunityQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOpportunityQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOpportunityQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildOpportunityQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildOpportunityQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildOpportunityQuery leftJoinAccount($relationAlias = null) Adds a LEFT JOIN clause to the query using the Account relation
 * @method     ChildOpportunityQuery rightJoinAccount($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Account relation
 * @method     ChildOpportunityQuery innerJoinAccount($relationAlias = null) Adds a INNER JOIN clause to the query using the Account relation
 *
 * @method     ChildOpportunityQuery joinWithAccount($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Account relation
 *
 * @method     ChildOpportunityQuery leftJoinWithAccount() Adds a LEFT JOIN clause and with to the query using the Account relation
 * @method     ChildOpportunityQuery rightJoinWithAccount() Adds a RIGHT JOIN clause and with to the query using the Account relation
 * @method     ChildOpportunityQuery innerJoinWithAccount() Adds a INNER JOIN clause and with to the query using the Account relation
 *
 * @method     ChildOpportunityQuery leftJoinOpportunityHistory($relationAlias = null) Adds a LEFT JOIN clause to the query using the OpportunityHistory relation
 * @method     ChildOpportunityQuery rightJoinOpportunityHistory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the OpportunityHistory relation
 * @method     ChildOpportunityQuery innerJoinOpportunityHistory($relationAlias = null) Adds a INNER JOIN clause to the query using the OpportunityHistory relation
 *
 * @method     ChildOpportunityQuery joinWithOpportunityHistory($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the OpportunityHistory relation
 *
 * @method     ChildOpportunityQuery leftJoinWithOpportunityHistory() Adds a LEFT JOIN clause and with to the query using the OpportunityHistory relation
 * @method     ChildOpportunityQuery rightJoinWithOpportunityHistory() Adds a RIGHT JOIN clause and with to the query using the OpportunityHistory relation
 * @method     ChildOpportunityQuery innerJoinWithOpportunityHistory() Adds a INNER JOIN clause and with to the query using the OpportunityHistory relation
 *
 * @method     \DataModels\DataModels\AccountQuery|\DataModels\DataModels\OpportunityHistoryQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOpportunity findOne(ConnectionInterface $con = null) Return the first ChildOpportunity matching the query
 * @method     ChildOpportunity findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOpportunity matching the query, or a new ChildOpportunity object populated from the query conditions when no match is found
 *
 * @method     ChildOpportunity findOneById(int $id) Return the first ChildOpportunity filtered by the id column
 * @method     ChildOpportunity findOneByAccountId(int $account_id) Return the first ChildOpportunity filtered by the account_id column
 * @method     ChildOpportunity findOneBySFDCId(string $sfdc_id) Return the first ChildOpportunity filtered by the sfdc_id column
 * @method     ChildOpportunity findOneBySFDCLastCheckTime(string $sfdc_last_check_time) Return the first ChildOpportunity filtered by the sfdc_last_check_time column
 * @method     ChildOpportunity findOneByCreatedAt(string $created_at) Return the first ChildOpportunity filtered by the created_at column
 * @method     ChildOpportunity findOneByUpdatedAt(string $updated_at) Return the first ChildOpportunity filtered by the updated_at column *

 * @method     ChildOpportunity requirePk($key, ConnectionInterface $con = null) Return the ChildOpportunity by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunity requireOne(ConnectionInterface $con = null) Return the first ChildOpportunity matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOpportunity requireOneById(int $id) Return the first ChildOpportunity filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunity requireOneByAccountId(int $account_id) Return the first ChildOpportunity filtered by the account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunity requireOneBySFDCId(string $sfdc_id) Return the first ChildOpportunity filtered by the sfdc_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunity requireOneBySFDCLastCheckTime(string $sfdc_last_check_time) Return the first ChildOpportunity filtered by the sfdc_last_check_time column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunity requireOneByCreatedAt(string $created_at) Return the first ChildOpportunity filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunity requireOneByUpdatedAt(string $updated_at) Return the first ChildOpportunity filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOpportunity[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildOpportunity objects based on current ModelCriteria
 * @method     ChildOpportunity[]|ObjectCollection findById(int $id) Return ChildOpportunity objects filtered by the id column
 * @method     ChildOpportunity[]|ObjectCollection findByAccountId(int $account_id) Return ChildOpportunity objects filtered by the account_id column
 * @method     ChildOpportunity[]|ObjectCollection findBySFDCId(string $sfdc_id) Return ChildOpportunity objects filtered by the sfdc_id column
 * @method     ChildOpportunity[]|ObjectCollection findBySFDCLastCheckTime(string $sfdc_last_check_time) Return ChildOpportunity objects filtered by the sfdc_last_check_time column
 * @method     ChildOpportunity[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildOpportunity objects filtered by the created_at column
 * @method     ChildOpportunity[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildOpportunity objects filtered by the updated_at column
 * @method     ChildOpportunity[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class OpportunityQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\OpportunityQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\Opportunity', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOpportunityQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOpportunityQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildOpportunityQuery) {
            return $criteria;
        }
        $query = new ChildOpportunityQuery();
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
     * @return ChildOpportunity|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OpportunityTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = OpportunityTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildOpportunity A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, account_id, sfdc_id, sfdc_last_check_time, created_at, updated_at FROM opportunity WHERE id = :p0';
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
            /** @var ChildOpportunity $obj */
            $obj = new ChildOpportunity();
            $obj->hydrate($row);
            OpportunityTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildOpportunity|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(OpportunityTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(OpportunityTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(OpportunityTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(OpportunityTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityTableMap::COL_ID, $id, $comparison);
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
     * @see       filterByAccount()
     *
     * @param     mixed $accountId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function filterByAccountId($accountId = null, $comparison = null)
    {
        if (is_array($accountId)) {
            $useMinMax = false;
            if (isset($accountId['min'])) {
                $this->addUsingAlias(OpportunityTableMap::COL_ACCOUNT_ID, $accountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accountId['max'])) {
                $this->addUsingAlias(OpportunityTableMap::COL_ACCOUNT_ID, $accountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityTableMap::COL_ACCOUNT_ID, $accountId, $comparison);
    }

    /**
     * Filter the query on the sfdc_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySFDCId('fooValue');   // WHERE sfdc_id = 'fooValue'
     * $query->filterBySFDCId('%fooValue%', Criteria::LIKE); // WHERE sfdc_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sFDCId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function filterBySFDCId($sFDCId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sFDCId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityTableMap::COL_SFDC_ID, $sFDCId, $comparison);
    }

    /**
     * Filter the query on the sfdc_last_check_time column
     *
     * Example usage:
     * <code>
     * $query->filterBySFDCLastCheckTime('2011-03-14'); // WHERE sfdc_last_check_time = '2011-03-14'
     * $query->filterBySFDCLastCheckTime('now'); // WHERE sfdc_last_check_time = '2011-03-14'
     * $query->filterBySFDCLastCheckTime(array('max' => 'yesterday')); // WHERE sfdc_last_check_time > '2011-03-13'
     * </code>
     *
     * @param     mixed $sFDCLastCheckTime The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function filterBySFDCLastCheckTime($sFDCLastCheckTime = null, $comparison = null)
    {
        if (is_array($sFDCLastCheckTime)) {
            $useMinMax = false;
            if (isset($sFDCLastCheckTime['min'])) {
                $this->addUsingAlias(OpportunityTableMap::COL_SFDC_LAST_CHECK_TIME, $sFDCLastCheckTime['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sFDCLastCheckTime['max'])) {
                $this->addUsingAlias(OpportunityTableMap::COL_SFDC_LAST_CHECK_TIME, $sFDCLastCheckTime['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityTableMap::COL_SFDC_LAST_CHECK_TIME, $sFDCLastCheckTime, $comparison);
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
     * @return $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(OpportunityTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(OpportunityTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(OpportunityTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(OpportunityTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \DataModels\DataModels\Account object
     *
     * @param \DataModels\DataModels\Account|ObjectCollection $account The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOpportunityQuery The current query, for fluid interface
     */
    public function filterByAccount($account, $comparison = null)
    {
        if ($account instanceof \DataModels\DataModels\Account) {
            return $this
                ->addUsingAlias(OpportunityTableMap::COL_ACCOUNT_ID, $account->getId(), $comparison);
        } elseif ($account instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OpportunityTableMap::COL_ACCOUNT_ID, $account->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAccount() only accepts arguments of type \DataModels\DataModels\Account or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Account relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function joinAccount($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Account');

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
            $this->addJoinObject($join, 'Account');
        }

        return $this;
    }

    /**
     * Use the Account relation Account object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\AccountQuery A secondary query class using the current class as primary query
     */
    public function useAccountQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAccount($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Account', '\DataModels\DataModels\AccountQuery');
    }

    /**
     * Filter the query by a related \DataModels\DataModels\OpportunityHistory object
     *
     * @param \DataModels\DataModels\OpportunityHistory|ObjectCollection $opportunityHistory the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildOpportunityQuery The current query, for fluid interface
     */
    public function filterByOpportunityHistory($opportunityHistory, $comparison = null)
    {
        if ($opportunityHistory instanceof \DataModels\DataModels\OpportunityHistory) {
            return $this
                ->addUsingAlias(OpportunityTableMap::COL_ID, $opportunityHistory->getOpportunityId(), $comparison);
        } elseif ($opportunityHistory instanceof ObjectCollection) {
            return $this
                ->useOpportunityHistoryQuery()
                ->filterByPrimaryKeys($opportunityHistory->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByOpportunityHistory() only accepts arguments of type \DataModels\DataModels\OpportunityHistory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the OpportunityHistory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function joinOpportunityHistory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('OpportunityHistory');

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
            $this->addJoinObject($join, 'OpportunityHistory');
        }

        return $this;
    }

    /**
     * Use the OpportunityHistory relation OpportunityHistory object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\OpportunityHistoryQuery A secondary query class using the current class as primary query
     */
    public function useOpportunityHistoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOpportunityHistory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'OpportunityHistory', '\DataModels\DataModels\OpportunityHistoryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildOpportunity $opportunity Object to remove from the list of results
     *
     * @return $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function prune($opportunity = null)
    {
        if ($opportunity) {
            $this->addUsingAlias(OpportunityTableMap::COL_ID, $opportunity->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the opportunity table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OpportunityTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OpportunityTableMap::clearInstancePool();
            OpportunityTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(OpportunityTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OpportunityTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            OpportunityTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            OpportunityTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(OpportunityTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(OpportunityTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(OpportunityTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(OpportunityTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(OpportunityTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildOpportunityQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(OpportunityTableMap::COL_CREATED_AT);
    }

} // OpportunityQuery
