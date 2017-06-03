<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\AccountHistory as ChildAccountHistory;
use DataModels\DataModels\AccountHistoryQuery as ChildAccountHistoryQuery;
use DataModels\DataModels\Map\AccountHistoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'account_history' table.
 *
 *
 *
 * @method     ChildAccountHistoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAccountHistoryQuery orderByAccountId($order = Criteria::ASC) Order by the account_id column
 * @method     ChildAccountHistoryQuery orderBySFDCName($order = Criteria::ASC) Order by the sfdc_name column
 * @method     ChildAccountHistoryQuery orderByAccountStatusId($order = Criteria::ASC) Order by the account_status_id column
 * @method     ChildAccountHistoryQuery orderByBillingCycleId($order = Criteria::ASC) Order by the billing_cycle_id column
 * @method     ChildAccountHistoryQuery orderByBillingCity($order = Criteria::ASC) Order by the billing_city column
 * @method     ChildAccountHistoryQuery orderByRenewalDate($order = Criteria::ASC) Order by the renewal_date column
 * @method     ChildAccountHistoryQuery orderByNumEmployees($order = Criteria::ASC) Order by the num_employees column
 * @method     ChildAccountHistoryQuery orderByArr($order = Criteria::ASC) Order by the ARR column
 * @method     ChildAccountHistoryQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildAccountHistoryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildAccountHistoryQuery groupById() Group by the id column
 * @method     ChildAccountHistoryQuery groupByAccountId() Group by the account_id column
 * @method     ChildAccountHistoryQuery groupBySFDCName() Group by the sfdc_name column
 * @method     ChildAccountHistoryQuery groupByAccountStatusId() Group by the account_status_id column
 * @method     ChildAccountHistoryQuery groupByBillingCycleId() Group by the billing_cycle_id column
 * @method     ChildAccountHistoryQuery groupByBillingCity() Group by the billing_city column
 * @method     ChildAccountHistoryQuery groupByRenewalDate() Group by the renewal_date column
 * @method     ChildAccountHistoryQuery groupByNumEmployees() Group by the num_employees column
 * @method     ChildAccountHistoryQuery groupByArr() Group by the ARR column
 * @method     ChildAccountHistoryQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildAccountHistoryQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildAccountHistoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAccountHistoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAccountHistoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAccountHistoryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAccountHistoryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAccountHistoryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAccountHistoryQuery leftJoinAccount($relationAlias = null) Adds a LEFT JOIN clause to the query using the Account relation
 * @method     ChildAccountHistoryQuery rightJoinAccount($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Account relation
 * @method     ChildAccountHistoryQuery innerJoinAccount($relationAlias = null) Adds a INNER JOIN clause to the query using the Account relation
 *
 * @method     ChildAccountHistoryQuery joinWithAccount($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Account relation
 *
 * @method     ChildAccountHistoryQuery leftJoinWithAccount() Adds a LEFT JOIN clause and with to the query using the Account relation
 * @method     ChildAccountHistoryQuery rightJoinWithAccount() Adds a RIGHT JOIN clause and with to the query using the Account relation
 * @method     ChildAccountHistoryQuery innerJoinWithAccount() Adds a INNER JOIN clause and with to the query using the Account relation
 *
 * @method     \DataModels\DataModels\AccountQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAccountHistory findOne(ConnectionInterface $con = null) Return the first ChildAccountHistory matching the query
 * @method     ChildAccountHistory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAccountHistory matching the query, or a new ChildAccountHistory object populated from the query conditions when no match is found
 *
 * @method     ChildAccountHistory findOneById(int $id) Return the first ChildAccountHistory filtered by the id column
 * @method     ChildAccountHistory findOneByAccountId(int $account_id) Return the first ChildAccountHistory filtered by the account_id column
 * @method     ChildAccountHistory findOneBySFDCName(string $sfdc_name) Return the first ChildAccountHistory filtered by the sfdc_name column
 * @method     ChildAccountHistory findOneByAccountStatusId(int $account_status_id) Return the first ChildAccountHistory filtered by the account_status_id column
 * @method     ChildAccountHistory findOneByBillingCycleId(int $billing_cycle_id) Return the first ChildAccountHistory filtered by the billing_cycle_id column
 * @method     ChildAccountHistory findOneByBillingCity(string $billing_city) Return the first ChildAccountHistory filtered by the billing_city column
 * @method     ChildAccountHistory findOneByRenewalDate(string $renewal_date) Return the first ChildAccountHistory filtered by the renewal_date column
 * @method     ChildAccountHistory findOneByNumEmployees(int $num_employees) Return the first ChildAccountHistory filtered by the num_employees column
 * @method     ChildAccountHistory findOneByArr(string $ARR) Return the first ChildAccountHistory filtered by the ARR column
 * @method     ChildAccountHistory findOneByCreatedAt(string $created_at) Return the first ChildAccountHistory filtered by the created_at column
 * @method     ChildAccountHistory findOneByUpdatedAt(string $updated_at) Return the first ChildAccountHistory filtered by the updated_at column *

 * @method     ChildAccountHistory requirePk($key, ConnectionInterface $con = null) Return the ChildAccountHistory by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccountHistory requireOne(ConnectionInterface $con = null) Return the first ChildAccountHistory matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAccountHistory requireOneById(int $id) Return the first ChildAccountHistory filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccountHistory requireOneByAccountId(int $account_id) Return the first ChildAccountHistory filtered by the account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccountHistory requireOneBySFDCName(string $sfdc_name) Return the first ChildAccountHistory filtered by the sfdc_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccountHistory requireOneByAccountStatusId(int $account_status_id) Return the first ChildAccountHistory filtered by the account_status_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccountHistory requireOneByBillingCycleId(int $billing_cycle_id) Return the first ChildAccountHistory filtered by the billing_cycle_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccountHistory requireOneByBillingCity(string $billing_city) Return the first ChildAccountHistory filtered by the billing_city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccountHistory requireOneByRenewalDate(string $renewal_date) Return the first ChildAccountHistory filtered by the renewal_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccountHistory requireOneByNumEmployees(int $num_employees) Return the first ChildAccountHistory filtered by the num_employees column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccountHistory requireOneByArr(string $ARR) Return the first ChildAccountHistory filtered by the ARR column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccountHistory requireOneByCreatedAt(string $created_at) Return the first ChildAccountHistory filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccountHistory requireOneByUpdatedAt(string $updated_at) Return the first ChildAccountHistory filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAccountHistory[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAccountHistory objects based on current ModelCriteria
 * @method     ChildAccountHistory[]|ObjectCollection findById(int $id) Return ChildAccountHistory objects filtered by the id column
 * @method     ChildAccountHistory[]|ObjectCollection findByAccountId(int $account_id) Return ChildAccountHistory objects filtered by the account_id column
 * @method     ChildAccountHistory[]|ObjectCollection findBySFDCName(string $sfdc_name) Return ChildAccountHistory objects filtered by the sfdc_name column
 * @method     ChildAccountHistory[]|ObjectCollection findByAccountStatusId(int $account_status_id) Return ChildAccountHistory objects filtered by the account_status_id column
 * @method     ChildAccountHistory[]|ObjectCollection findByBillingCycleId(int $billing_cycle_id) Return ChildAccountHistory objects filtered by the billing_cycle_id column
 * @method     ChildAccountHistory[]|ObjectCollection findByBillingCity(string $billing_city) Return ChildAccountHistory objects filtered by the billing_city column
 * @method     ChildAccountHistory[]|ObjectCollection findByRenewalDate(string $renewal_date) Return ChildAccountHistory objects filtered by the renewal_date column
 * @method     ChildAccountHistory[]|ObjectCollection findByNumEmployees(int $num_employees) Return ChildAccountHistory objects filtered by the num_employees column
 * @method     ChildAccountHistory[]|ObjectCollection findByArr(string $ARR) Return ChildAccountHistory objects filtered by the ARR column
 * @method     ChildAccountHistory[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildAccountHistory objects filtered by the created_at column
 * @method     ChildAccountHistory[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildAccountHistory objects filtered by the updated_at column
 * @method     ChildAccountHistory[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AccountHistoryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\AccountHistoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\AccountHistory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAccountHistoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAccountHistoryQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAccountHistoryQuery) {
            return $criteria;
        }
        $query = new ChildAccountHistoryQuery();
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
     * @return ChildAccountHistory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AccountHistoryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = AccountHistoryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildAccountHistory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, account_id, sfdc_name, account_status_id, billing_cycle_id, billing_city, renewal_date, num_employees, ARR, created_at, updated_at FROM account_history WHERE id = :p0';
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
            /** @var ChildAccountHistory $obj */
            $obj = new ChildAccountHistory();
            $obj->hydrate($row);
            AccountHistoryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildAccountHistory|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AccountHistoryTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AccountHistoryTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountHistoryTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByAccountId($accountId = null, $comparison = null)
    {
        if (is_array($accountId)) {
            $useMinMax = false;
            if (isset($accountId['min'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_ACCOUNT_ID, $accountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accountId['max'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_ACCOUNT_ID, $accountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountHistoryTableMap::COL_ACCOUNT_ID, $accountId, $comparison);
    }

    /**
     * Filter the query on the sfdc_name column
     *
     * Example usage:
     * <code>
     * $query->filterBySFDCName('fooValue');   // WHERE sfdc_name = 'fooValue'
     * $query->filterBySFDCName('%fooValue%', Criteria::LIKE); // WHERE sfdc_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sFDCName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterBySFDCName($sFDCName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sFDCName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountHistoryTableMap::COL_SFDC_NAME, $sFDCName, $comparison);
    }

    /**
     * Filter the query on the account_status_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAccountStatusId(1234); // WHERE account_status_id = 1234
     * $query->filterByAccountStatusId(array(12, 34)); // WHERE account_status_id IN (12, 34)
     * $query->filterByAccountStatusId(array('min' => 12)); // WHERE account_status_id > 12
     * </code>
     *
     * @param     mixed $accountStatusId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByAccountStatusId($accountStatusId = null, $comparison = null)
    {
        if (is_array($accountStatusId)) {
            $useMinMax = false;
            if (isset($accountStatusId['min'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_ACCOUNT_STATUS_ID, $accountStatusId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accountStatusId['max'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_ACCOUNT_STATUS_ID, $accountStatusId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountHistoryTableMap::COL_ACCOUNT_STATUS_ID, $accountStatusId, $comparison);
    }

    /**
     * Filter the query on the billing_cycle_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBillingCycleId(1234); // WHERE billing_cycle_id = 1234
     * $query->filterByBillingCycleId(array(12, 34)); // WHERE billing_cycle_id IN (12, 34)
     * $query->filterByBillingCycleId(array('min' => 12)); // WHERE billing_cycle_id > 12
     * </code>
     *
     * @param     mixed $billingCycleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByBillingCycleId($billingCycleId = null, $comparison = null)
    {
        if (is_array($billingCycleId)) {
            $useMinMax = false;
            if (isset($billingCycleId['min'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_BILLING_CYCLE_ID, $billingCycleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($billingCycleId['max'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_BILLING_CYCLE_ID, $billingCycleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountHistoryTableMap::COL_BILLING_CYCLE_ID, $billingCycleId, $comparison);
    }

    /**
     * Filter the query on the billing_city column
     *
     * Example usage:
     * <code>
     * $query->filterByBillingCity('fooValue');   // WHERE billing_city = 'fooValue'
     * $query->filterByBillingCity('%fooValue%', Criteria::LIKE); // WHERE billing_city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $billingCity The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByBillingCity($billingCity = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($billingCity)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountHistoryTableMap::COL_BILLING_CITY, $billingCity, $comparison);
    }

    /**
     * Filter the query on the renewal_date column
     *
     * Example usage:
     * <code>
     * $query->filterByRenewalDate('2011-03-14'); // WHERE renewal_date = '2011-03-14'
     * $query->filterByRenewalDate('now'); // WHERE renewal_date = '2011-03-14'
     * $query->filterByRenewalDate(array('max' => 'yesterday')); // WHERE renewal_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $renewalDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByRenewalDate($renewalDate = null, $comparison = null)
    {
        if (is_array($renewalDate)) {
            $useMinMax = false;
            if (isset($renewalDate['min'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_RENEWAL_DATE, $renewalDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($renewalDate['max'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_RENEWAL_DATE, $renewalDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountHistoryTableMap::COL_RENEWAL_DATE, $renewalDate, $comparison);
    }

    /**
     * Filter the query on the num_employees column
     *
     * Example usage:
     * <code>
     * $query->filterByNumEmployees(1234); // WHERE num_employees = 1234
     * $query->filterByNumEmployees(array(12, 34)); // WHERE num_employees IN (12, 34)
     * $query->filterByNumEmployees(array('min' => 12)); // WHERE num_employees > 12
     * </code>
     *
     * @param     mixed $numEmployees The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByNumEmployees($numEmployees = null, $comparison = null)
    {
        if (is_array($numEmployees)) {
            $useMinMax = false;
            if (isset($numEmployees['min'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_NUM_EMPLOYEES, $numEmployees['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($numEmployees['max'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_NUM_EMPLOYEES, $numEmployees['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountHistoryTableMap::COL_NUM_EMPLOYEES, $numEmployees, $comparison);
    }

    /**
     * Filter the query on the ARR column
     *
     * Example usage:
     * <code>
     * $query->filterByArr('fooValue');   // WHERE ARR = 'fooValue'
     * $query->filterByArr('%fooValue%', Criteria::LIKE); // WHERE ARR LIKE '%fooValue%'
     * </code>
     *
     * @param     string $arr The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByArr($arr = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($arr)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountHistoryTableMap::COL_ARR, $arr, $comparison);
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
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountHistoryTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(AccountHistoryTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountHistoryTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \DataModels\DataModels\Account object
     *
     * @param \DataModels\DataModels\Account|ObjectCollection $account The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function filterByAccount($account, $comparison = null)
    {
        if ($account instanceof \DataModels\DataModels\Account) {
            return $this
                ->addUsingAlias(AccountHistoryTableMap::COL_ACCOUNT_ID, $account->getId(), $comparison);
        } elseif ($account instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AccountHistoryTableMap::COL_ACCOUNT_ID, $account->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildAccountHistory $accountHistory Object to remove from the list of results
     *
     * @return $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function prune($accountHistory = null)
    {
        if ($accountHistory) {
            $this->addUsingAlias(AccountHistoryTableMap::COL_ID, $accountHistory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the account_history table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AccountHistoryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AccountHistoryTableMap::clearInstancePool();
            AccountHistoryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AccountHistoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AccountHistoryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AccountHistoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AccountHistoryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(AccountHistoryTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(AccountHistoryTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(AccountHistoryTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(AccountHistoryTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(AccountHistoryTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildAccountHistoryQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(AccountHistoryTableMap::COL_CREATED_AT);
    }

} // AccountHistoryQuery
