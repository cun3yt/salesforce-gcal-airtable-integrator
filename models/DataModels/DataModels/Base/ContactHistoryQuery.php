<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\ContactHistory as ChildContactHistory;
use DataModels\DataModels\ContactHistoryQuery as ChildContactHistoryQuery;
use DataModels\DataModels\Map\ContactHistoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'contact_history' table.
 *
 *
 *
 * @method     ChildContactHistoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildContactHistoryQuery orderByContactId($order = Criteria::ASC) Order by the contact_id column
 * @method     ChildContactHistoryQuery orderByAccountId($order = Criteria::ASC) Order by the account_id column
 * @method     ChildContactHistoryQuery orderBySfdcTitle($order = Criteria::ASC) Order by the sfdc_title column
 * @method     ChildContactHistoryQuery orderBySfdcMailingCity($order = Criteria::ASC) Order by the sfdc_mailing_city column
 *
 * @method     ChildContactHistoryQuery groupById() Group by the id column
 * @method     ChildContactHistoryQuery groupByContactId() Group by the contact_id column
 * @method     ChildContactHistoryQuery groupByAccountId() Group by the account_id column
 * @method     ChildContactHistoryQuery groupBySfdcTitle() Group by the sfdc_title column
 * @method     ChildContactHistoryQuery groupBySfdcMailingCity() Group by the sfdc_mailing_city column
 *
 * @method     ChildContactHistoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildContactHistoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildContactHistoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildContactHistoryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildContactHistoryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildContactHistoryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildContactHistory findOne(ConnectionInterface $con = null) Return the first ChildContactHistory matching the query
 * @method     ChildContactHistory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildContactHistory matching the query, or a new ChildContactHistory object populated from the query conditions when no match is found
 *
 * @method     ChildContactHistory findOneById(int $id) Return the first ChildContactHistory filtered by the id column
 * @method     ChildContactHistory findOneByContactId(int $contact_id) Return the first ChildContactHistory filtered by the contact_id column
 * @method     ChildContactHistory findOneByAccountId(int $account_id) Return the first ChildContactHistory filtered by the account_id column
 * @method     ChildContactHistory findOneBySfdcTitle(string $sfdc_title) Return the first ChildContactHistory filtered by the sfdc_title column
 * @method     ChildContactHistory findOneBySfdcMailingCity(string $sfdc_mailing_city) Return the first ChildContactHistory filtered by the sfdc_mailing_city column *

 * @method     ChildContactHistory requirePk($key, ConnectionInterface $con = null) Return the ChildContactHistory by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContactHistory requireOne(ConnectionInterface $con = null) Return the first ChildContactHistory matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildContactHistory requireOneById(int $id) Return the first ChildContactHistory filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContactHistory requireOneByContactId(int $contact_id) Return the first ChildContactHistory filtered by the contact_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContactHistory requireOneByAccountId(int $account_id) Return the first ChildContactHistory filtered by the account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContactHistory requireOneBySfdcTitle(string $sfdc_title) Return the first ChildContactHistory filtered by the sfdc_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContactHistory requireOneBySfdcMailingCity(string $sfdc_mailing_city) Return the first ChildContactHistory filtered by the sfdc_mailing_city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildContactHistory[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildContactHistory objects based on current ModelCriteria
 * @method     ChildContactHistory[]|ObjectCollection findById(int $id) Return ChildContactHistory objects filtered by the id column
 * @method     ChildContactHistory[]|ObjectCollection findByContactId(int $contact_id) Return ChildContactHistory objects filtered by the contact_id column
 * @method     ChildContactHistory[]|ObjectCollection findByAccountId(int $account_id) Return ChildContactHistory objects filtered by the account_id column
 * @method     ChildContactHistory[]|ObjectCollection findBySfdcTitle(string $sfdc_title) Return ChildContactHistory objects filtered by the sfdc_title column
 * @method     ChildContactHistory[]|ObjectCollection findBySfdcMailingCity(string $sfdc_mailing_city) Return ChildContactHistory objects filtered by the sfdc_mailing_city column
 * @method     ChildContactHistory[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ContactHistoryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\ContactHistoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\ContactHistory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildContactHistoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildContactHistoryQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildContactHistoryQuery) {
            return $criteria;
        }
        $query = new ChildContactHistoryQuery();
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
     * @return ChildContactHistory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ContactHistoryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ContactHistoryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildContactHistory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, contact_id, account_id, sfdc_title, sfdc_mailing_city FROM contact_history WHERE id = :p0';
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
            /** @var ChildContactHistory $obj */
            $obj = new ChildContactHistory();
            $obj->hydrate($row);
            ContactHistoryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildContactHistory|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildContactHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ContactHistoryTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildContactHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ContactHistoryTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildContactHistoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ContactHistoryTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ContactHistoryTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactHistoryTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the contact_id column
     *
     * Example usage:
     * <code>
     * $query->filterByContactId(1234); // WHERE contact_id = 1234
     * $query->filterByContactId(array(12, 34)); // WHERE contact_id IN (12, 34)
     * $query->filterByContactId(array('min' => 12)); // WHERE contact_id > 12
     * </code>
     *
     * @param     mixed $contactId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContactHistoryQuery The current query, for fluid interface
     */
    public function filterByContactId($contactId = null, $comparison = null)
    {
        if (is_array($contactId)) {
            $useMinMax = false;
            if (isset($contactId['min'])) {
                $this->addUsingAlias(ContactHistoryTableMap::COL_CONTACT_ID, $contactId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($contactId['max'])) {
                $this->addUsingAlias(ContactHistoryTableMap::COL_CONTACT_ID, $contactId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactHistoryTableMap::COL_CONTACT_ID, $contactId, $comparison);
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
     * @return $this|ChildContactHistoryQuery The current query, for fluid interface
     */
    public function filterByAccountId($accountId = null, $comparison = null)
    {
        if (is_array($accountId)) {
            $useMinMax = false;
            if (isset($accountId['min'])) {
                $this->addUsingAlias(ContactHistoryTableMap::COL_ACCOUNT_ID, $accountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accountId['max'])) {
                $this->addUsingAlias(ContactHistoryTableMap::COL_ACCOUNT_ID, $accountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactHistoryTableMap::COL_ACCOUNT_ID, $accountId, $comparison);
    }

    /**
     * Filter the query on the sfdc_title column
     *
     * Example usage:
     * <code>
     * $query->filterBySfdcTitle('fooValue');   // WHERE sfdc_title = 'fooValue'
     * $query->filterBySfdcTitle('%fooValue%', Criteria::LIKE); // WHERE sfdc_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sfdcTitle The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContactHistoryQuery The current query, for fluid interface
     */
    public function filterBySfdcTitle($sfdcTitle = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sfdcTitle)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactHistoryTableMap::COL_SFDC_TITLE, $sfdcTitle, $comparison);
    }

    /**
     * Filter the query on the sfdc_mailing_city column
     *
     * Example usage:
     * <code>
     * $query->filterBySfdcMailingCity('fooValue');   // WHERE sfdc_mailing_city = 'fooValue'
     * $query->filterBySfdcMailingCity('%fooValue%', Criteria::LIKE); // WHERE sfdc_mailing_city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sfdcMailingCity The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContactHistoryQuery The current query, for fluid interface
     */
    public function filterBySfdcMailingCity($sfdcMailingCity = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sfdcMailingCity)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactHistoryTableMap::COL_SFDC_MAILING_CITY, $sfdcMailingCity, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildContactHistory $contactHistory Object to remove from the list of results
     *
     * @return $this|ChildContactHistoryQuery The current query, for fluid interface
     */
    public function prune($contactHistory = null)
    {
        if ($contactHistory) {
            $this->addUsingAlias(ContactHistoryTableMap::COL_ID, $contactHistory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the contact_history table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ContactHistoryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ContactHistoryTableMap::clearInstancePool();
            ContactHistoryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ContactHistoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ContactHistoryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ContactHistoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ContactHistoryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ContactHistoryQuery
