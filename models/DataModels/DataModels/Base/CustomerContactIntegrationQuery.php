<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\CustomerContactIntegration as ChildCustomerContactIntegration;
use DataModels\DataModels\CustomerContactIntegrationQuery as ChildCustomerContactIntegrationQuery;
use DataModels\DataModels\Map\CustomerContactIntegrationTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'customer_contact_integration' table.
 *
 *
 *
 * @method     ChildCustomerContactIntegrationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildCustomerContactIntegrationQuery orderByCustomerContactId($order = Criteria::ASC) Order by the customer_contact_id column
 * @method     ChildCustomerContactIntegrationQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildCustomerContactIntegrationQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildCustomerContactIntegrationQuery orderByData($order = Criteria::ASC) Order by the data column
 *
 * @method     ChildCustomerContactIntegrationQuery groupById() Group by the id column
 * @method     ChildCustomerContactIntegrationQuery groupByCustomerContactId() Group by the customer_contact_id column
 * @method     ChildCustomerContactIntegrationQuery groupByType() Group by the type column
 * @method     ChildCustomerContactIntegrationQuery groupByStatus() Group by the status column
 * @method     ChildCustomerContactIntegrationQuery groupByData() Group by the data column
 *
 * @method     ChildCustomerContactIntegrationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCustomerContactIntegrationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCustomerContactIntegrationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCustomerContactIntegrationQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCustomerContactIntegrationQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCustomerContactIntegrationQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCustomerContactIntegration findOne(ConnectionInterface $con = null) Return the first ChildCustomerContactIntegration matching the query
 * @method     ChildCustomerContactIntegration findOneOrCreate(ConnectionInterface $con = null) Return the first ChildCustomerContactIntegration matching the query, or a new ChildCustomerContactIntegration object populated from the query conditions when no match is found
 *
 * @method     ChildCustomerContactIntegration findOneById(int $id) Return the first ChildCustomerContactIntegration filtered by the id column
 * @method     ChildCustomerContactIntegration findOneByCustomerContactId(int $customer_contact_id) Return the first ChildCustomerContactIntegration filtered by the customer_contact_id column
 * @method     ChildCustomerContactIntegration findOneByType(string $type) Return the first ChildCustomerContactIntegration filtered by the type column
 * @method     ChildCustomerContactIntegration findOneByStatus(string $status) Return the first ChildCustomerContactIntegration filtered by the status column
 * @method     ChildCustomerContactIntegration findOneByData(string $data) Return the first ChildCustomerContactIntegration filtered by the data column *

 * @method     ChildCustomerContactIntegration requirePk($key, ConnectionInterface $con = null) Return the ChildCustomerContactIntegration by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomerContactIntegration requireOne(ConnectionInterface $con = null) Return the first ChildCustomerContactIntegration matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCustomerContactIntegration requireOneById(int $id) Return the first ChildCustomerContactIntegration filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomerContactIntegration requireOneByCustomerContactId(int $customer_contact_id) Return the first ChildCustomerContactIntegration filtered by the customer_contact_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomerContactIntegration requireOneByType(string $type) Return the first ChildCustomerContactIntegration filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomerContactIntegration requireOneByStatus(string $status) Return the first ChildCustomerContactIntegration filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCustomerContactIntegration requireOneByData(string $data) Return the first ChildCustomerContactIntegration filtered by the data column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCustomerContactIntegration[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildCustomerContactIntegration objects based on current ModelCriteria
 * @method     ChildCustomerContactIntegration[]|ObjectCollection findById(int $id) Return ChildCustomerContactIntegration objects filtered by the id column
 * @method     ChildCustomerContactIntegration[]|ObjectCollection findByCustomerContactId(int $customer_contact_id) Return ChildCustomerContactIntegration objects filtered by the customer_contact_id column
 * @method     ChildCustomerContactIntegration[]|ObjectCollection findByType(string $type) Return ChildCustomerContactIntegration objects filtered by the type column
 * @method     ChildCustomerContactIntegration[]|ObjectCollection findByStatus(string $status) Return ChildCustomerContactIntegration objects filtered by the status column
 * @method     ChildCustomerContactIntegration[]|ObjectCollection findByData(string $data) Return ChildCustomerContactIntegration objects filtered by the data column
 * @method     ChildCustomerContactIntegration[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class CustomerContactIntegrationQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\CustomerContactIntegrationQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\CustomerContactIntegration', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCustomerContactIntegrationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCustomerContactIntegrationQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildCustomerContactIntegrationQuery) {
            return $criteria;
        }
        $query = new ChildCustomerContactIntegrationQuery();
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
     * @return ChildCustomerContactIntegration|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CustomerContactIntegrationTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CustomerContactIntegrationTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildCustomerContactIntegration A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, customer_contact_id, type, status, data FROM customer_contact_integration WHERE id = :p0';
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
            /** @var ChildCustomerContactIntegration $obj */
            $obj = new ChildCustomerContactIntegration();
            $obj->hydrate($row);
            CustomerContactIntegrationTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildCustomerContactIntegration|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildCustomerContactIntegrationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CustomerContactIntegrationTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildCustomerContactIntegrationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CustomerContactIntegrationTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildCustomerContactIntegrationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CustomerContactIntegrationTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CustomerContactIntegrationTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerContactIntegrationTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the customer_contact_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerContactId(1234); // WHERE customer_contact_id = 1234
     * $query->filterByCustomerContactId(array(12, 34)); // WHERE customer_contact_id IN (12, 34)
     * $query->filterByCustomerContactId(array('min' => 12)); // WHERE customer_contact_id > 12
     * </code>
     *
     * @param     mixed $customerContactId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildCustomerContactIntegrationQuery The current query, for fluid interface
     */
    public function filterByCustomerContactId($customerContactId = null, $comparison = null)
    {
        if (is_array($customerContactId)) {
            $useMinMax = false;
            if (isset($customerContactId['min'])) {
                $this->addUsingAlias(CustomerContactIntegrationTableMap::COL_CUSTOMER_CONTACT_ID, $customerContactId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($customerContactId['max'])) {
                $this->addUsingAlias(CustomerContactIntegrationTableMap::COL_CUSTOMER_CONTACT_ID, $customerContactId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerContactIntegrationTableMap::COL_CUSTOMER_CONTACT_ID, $customerContactId, $comparison);
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
     * @return $this|ChildCustomerContactIntegrationQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerContactIntegrationTableMap::COL_TYPE, $type, $comparison);
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
     * @return $this|ChildCustomerContactIntegrationQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($status)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerContactIntegrationTableMap::COL_STATUS, $status, $comparison);
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
     * @return $this|ChildCustomerContactIntegrationQuery The current query, for fluid interface
     */
    public function filterByData($data = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($data)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CustomerContactIntegrationTableMap::COL_DATA, $data, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildCustomerContactIntegration $customerContactIntegration Object to remove from the list of results
     *
     * @return $this|ChildCustomerContactIntegrationQuery The current query, for fluid interface
     */
    public function prune($customerContactIntegration = null)
    {
        if ($customerContactIntegration) {
            $this->addUsingAlias(CustomerContactIntegrationTableMap::COL_ID, $customerContactIntegration->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the customer_contact_integration table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerContactIntegrationTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CustomerContactIntegrationTableMap::clearInstancePool();
            CustomerContactIntegrationTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(CustomerContactIntegrationTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CustomerContactIntegrationTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CustomerContactIntegrationTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CustomerContactIntegrationTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // CustomerContactIntegrationQuery
