<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\Contact as ChildContact;
use DataModels\DataModels\ContactQuery as ChildContactQuery;
use DataModels\DataModels\MeetingAttendeeQuery as ChildMeetingAttendeeQuery;
use DataModels\DataModels\Map\ContactTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'contact' table.
 *
 *
 *
 * @method     ChildContactQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildContactQuery orderByFullName($order = Criteria::ASC) Order by the full_name column
 * @method     ChildContactQuery orderByClientId($order = Criteria::ASC) Order by the client_id column
 * @method     ChildContactQuery orderByAccountId($order = Criteria::ASC) Order by the account_id column
 * @method     ChildContactQuery orderBySfdcContactId($order = Criteria::ASC) Order by the sfdc_contact_id column
 * @method     ChildContactQuery orderBySfdcAccountId($order = Criteria::ASC) Order by the sfdc_account_id column
 * @method     ChildContactQuery orderBySfdcContactName($order = Criteria::ASC) Order by the sfdc_contact_name column
 * @method     ChildContactQuery orderBySfdcTitle($order = Criteria::ASC) Order by the sfdc_contact_title column
 * @method     ChildContactQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildContactQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildContactQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildContactQuery groupByEmail() Group by the email column
 * @method     ChildContactQuery groupByFullName() Group by the full_name column
 * @method     ChildContactQuery groupByClientId() Group by the client_id column
 * @method     ChildContactQuery groupByAccountId() Group by the account_id column
 * @method     ChildContactQuery groupBySfdcContactId() Group by the sfdc_contact_id column
 * @method     ChildContactQuery groupBySfdcAccountId() Group by the sfdc_account_id column
 * @method     ChildContactQuery groupBySfdcContactName() Group by the sfdc_contact_name column
 * @method     ChildContactQuery groupBySfdcTitle() Group by the sfdc_contact_title column
 * @method     ChildContactQuery groupById() Group by the id column
 * @method     ChildContactQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildContactQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildContactQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildContactQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildContactQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildContactQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildContactQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildContactQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildContactQuery leftJoinAccount($relationAlias = null) Adds a LEFT JOIN clause to the query using the Account relation
 * @method     ChildContactQuery rightJoinAccount($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Account relation
 * @method     ChildContactQuery innerJoinAccount($relationAlias = null) Adds a INNER JOIN clause to the query using the Account relation
 *
 * @method     ChildContactQuery joinWithAccount($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Account relation
 *
 * @method     ChildContactQuery leftJoinWithAccount() Adds a LEFT JOIN clause and with to the query using the Account relation
 * @method     ChildContactQuery rightJoinWithAccount() Adds a RIGHT JOIN clause and with to the query using the Account relation
 * @method     ChildContactQuery innerJoinWithAccount() Adds a INNER JOIN clause and with to the query using the Account relation
 *
 * @method     ChildContactQuery leftJoinClient($relationAlias = null) Adds a LEFT JOIN clause to the query using the Client relation
 * @method     ChildContactQuery rightJoinClient($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Client relation
 * @method     ChildContactQuery innerJoinClient($relationAlias = null) Adds a INNER JOIN clause to the query using the Client relation
 *
 * @method     ChildContactQuery joinWithClient($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Client relation
 *
 * @method     ChildContactQuery leftJoinWithClient() Adds a LEFT JOIN clause and with to the query using the Client relation
 * @method     ChildContactQuery rightJoinWithClient() Adds a RIGHT JOIN clause and with to the query using the Client relation
 * @method     ChildContactQuery innerJoinWithClient() Adds a INNER JOIN clause and with to the query using the Client relation
 *
 * @method     ChildContactQuery leftJoinMeetingAttendee($relationAlias = null) Adds a LEFT JOIN clause to the query using the MeetingAttendee relation
 * @method     ChildContactQuery rightJoinMeetingAttendee($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MeetingAttendee relation
 * @method     ChildContactQuery innerJoinMeetingAttendee($relationAlias = null) Adds a INNER JOIN clause to the query using the MeetingAttendee relation
 *
 * @method     ChildContactQuery joinWithMeetingAttendee($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MeetingAttendee relation
 *
 * @method     ChildContactQuery leftJoinWithMeetingAttendee() Adds a LEFT JOIN clause and with to the query using the MeetingAttendee relation
 * @method     ChildContactQuery rightJoinWithMeetingAttendee() Adds a RIGHT JOIN clause and with to the query using the MeetingAttendee relation
 * @method     ChildContactQuery innerJoinWithMeetingAttendee() Adds a INNER JOIN clause and with to the query using the MeetingAttendee relation
 *
 * @method     ChildContactQuery leftJoinContactHistory($relationAlias = null) Adds a LEFT JOIN clause to the query using the ContactHistory relation
 * @method     ChildContactQuery rightJoinContactHistory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ContactHistory relation
 * @method     ChildContactQuery innerJoinContactHistory($relationAlias = null) Adds a INNER JOIN clause to the query using the ContactHistory relation
 *
 * @method     ChildContactQuery joinWithContactHistory($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ContactHistory relation
 *
 * @method     ChildContactQuery leftJoinWithContactHistory() Adds a LEFT JOIN clause and with to the query using the ContactHistory relation
 * @method     ChildContactQuery rightJoinWithContactHistory() Adds a RIGHT JOIN clause and with to the query using the ContactHistory relation
 * @method     ChildContactQuery innerJoinWithContactHistory() Adds a INNER JOIN clause and with to the query using the ContactHistory relation
 *
 * @method     \DataModels\DataModels\AccountQuery|\DataModels\DataModels\ClientQuery|\DataModels\DataModels\MeetingAttendeeQuery|\DataModels\DataModels\ContactHistoryQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildContact findOne(ConnectionInterface $con = null) Return the first ChildContact matching the query
 * @method     ChildContact findOneOrCreate(ConnectionInterface $con = null) Return the first ChildContact matching the query, or a new ChildContact object populated from the query conditions when no match is found
 *
 * @method     ChildContact findOneByEmail(string $email) Return the first ChildContact filtered by the email column
 * @method     ChildContact findOneByFullName(string $full_name) Return the first ChildContact filtered by the full_name column
 * @method     ChildContact findOneByClientId(int $client_id) Return the first ChildContact filtered by the client_id column
 * @method     ChildContact findOneByAccountId(int $account_id) Return the first ChildContact filtered by the account_id column
 * @method     ChildContact findOneBySfdcContactId(string $sfdc_contact_id) Return the first ChildContact filtered by the sfdc_contact_id column
 * @method     ChildContact findOneBySfdcAccountId(string $sfdc_account_id) Return the first ChildContact filtered by the sfdc_account_id column
 * @method     ChildContact findOneBySfdcContactName(string $sfdc_contact_name) Return the first ChildContact filtered by the sfdc_contact_name column
 * @method     ChildContact findOneBySfdcTitle(string $sfdc_contact_title) Return the first ChildContact filtered by the sfdc_contact_title column
 * @method     ChildContact findOneById(int $id) Return the first ChildContact filtered by the id column
 * @method     ChildContact findOneByCreatedAt(string $created_at) Return the first ChildContact filtered by the created_at column
 * @method     ChildContact findOneByUpdatedAt(string $updated_at) Return the first ChildContact filtered by the updated_at column *

 * @method     ChildContact requirePk($key, ConnectionInterface $con = null) Return the ChildContact by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContact requireOne(ConnectionInterface $con = null) Return the first ChildContact matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildContact requireOneByEmail(string $email) Return the first ChildContact filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContact requireOneByFullName(string $full_name) Return the first ChildContact filtered by the full_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContact requireOneByClientId(int $client_id) Return the first ChildContact filtered by the client_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContact requireOneByAccountId(int $account_id) Return the first ChildContact filtered by the account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContact requireOneBySfdcContactId(string $sfdc_contact_id) Return the first ChildContact filtered by the sfdc_contact_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContact requireOneBySfdcAccountId(string $sfdc_account_id) Return the first ChildContact filtered by the sfdc_account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContact requireOneBySfdcContactName(string $sfdc_contact_name) Return the first ChildContact filtered by the sfdc_contact_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContact requireOneBySfdcTitle(string $sfdc_contact_title) Return the first ChildContact filtered by the sfdc_contact_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContact requireOneById(int $id) Return the first ChildContact filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContact requireOneByCreatedAt(string $created_at) Return the first ChildContact filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildContact requireOneByUpdatedAt(string $updated_at) Return the first ChildContact filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildContact[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildContact objects based on current ModelCriteria
 * @method     ChildContact[]|ObjectCollection findByEmail(string $email) Return ChildContact objects filtered by the email column
 * @method     ChildContact[]|ObjectCollection findByFullName(string $full_name) Return ChildContact objects filtered by the full_name column
 * @method     ChildContact[]|ObjectCollection findByClientId(int $client_id) Return ChildContact objects filtered by the client_id column
 * @method     ChildContact[]|ObjectCollection findByAccountId(int $account_id) Return ChildContact objects filtered by the account_id column
 * @method     ChildContact[]|ObjectCollection findBySfdcContactId(string $sfdc_contact_id) Return ChildContact objects filtered by the sfdc_contact_id column
 * @method     ChildContact[]|ObjectCollection findBySfdcAccountId(string $sfdc_account_id) Return ChildContact objects filtered by the sfdc_account_id column
 * @method     ChildContact[]|ObjectCollection findBySfdcContactName(string $sfdc_contact_name) Return ChildContact objects filtered by the sfdc_contact_name column
 * @method     ChildContact[]|ObjectCollection findBySfdcTitle(string $sfdc_contact_title) Return ChildContact objects filtered by the sfdc_contact_title column
 * @method     ChildContact[]|ObjectCollection findById(int $id) Return ChildContact objects filtered by the id column
 * @method     ChildContact[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildContact objects filtered by the created_at column
 * @method     ChildContact[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildContact objects filtered by the updated_at column
 * @method     ChildContact[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ContactQuery extends ChildMeetingAttendeeQuery
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\ContactQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\Contact', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildContactQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildContactQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildContactQuery) {
            return $criteria;
        }
        $query = new ChildContactQuery();
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
     * @return ChildContact|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ContactTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ContactTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildContact A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT email, full_name, client_id, account_id, sfdc_contact_id, sfdc_account_id, sfdc_contact_name, sfdc_contact_title, id, created_at, updated_at FROM contact WHERE id = :p0';
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
            /** @var ChildContact $obj */
            $obj = new ChildContact();
            $obj->hydrate($row);
            ContactTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildContact|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ContactTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ContactTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactTableMap::COL_EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the full_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFullName('fooValue');   // WHERE full_name = 'fooValue'
     * $query->filterByFullName('%fooValue%', Criteria::LIKE); // WHERE full_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fullName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterByFullName($fullName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fullName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactTableMap::COL_FULL_NAME, $fullName, $comparison);
    }

    /**
     * Filter the query on the client_id column
     *
     * Example usage:
     * <code>
     * $query->filterByClientId(1234); // WHERE client_id = 1234
     * $query->filterByClientId(array(12, 34)); // WHERE client_id IN (12, 34)
     * $query->filterByClientId(array('min' => 12)); // WHERE client_id > 12
     * </code>
     *
     * @see       filterByClient()
     *
     * @param     mixed $clientId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterByClientId($clientId = null, $comparison = null)
    {
        if (is_array($clientId)) {
            $useMinMax = false;
            if (isset($clientId['min'])) {
                $this->addUsingAlias(ContactTableMap::COL_CLIENT_ID, $clientId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($clientId['max'])) {
                $this->addUsingAlias(ContactTableMap::COL_CLIENT_ID, $clientId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactTableMap::COL_CLIENT_ID, $clientId, $comparison);
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
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterByAccountId($accountId = null, $comparison = null)
    {
        if (is_array($accountId)) {
            $useMinMax = false;
            if (isset($accountId['min'])) {
                $this->addUsingAlias(ContactTableMap::COL_ACCOUNT_ID, $accountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accountId['max'])) {
                $this->addUsingAlias(ContactTableMap::COL_ACCOUNT_ID, $accountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactTableMap::COL_ACCOUNT_ID, $accountId, $comparison);
    }

    /**
     * Filter the query on the sfdc_contact_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySfdcContactId('fooValue');   // WHERE sfdc_contact_id = 'fooValue'
     * $query->filterBySfdcContactId('%fooValue%', Criteria::LIKE); // WHERE sfdc_contact_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sfdcContactId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterBySfdcContactId($sfdcContactId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sfdcContactId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactTableMap::COL_SFDC_CONTACT_ID, $sfdcContactId, $comparison);
    }

    /**
     * Filter the query on the sfdc_account_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySfdcAccountId('fooValue');   // WHERE sfdc_account_id = 'fooValue'
     * $query->filterBySfdcAccountId('%fooValue%', Criteria::LIKE); // WHERE sfdc_account_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sfdcAccountId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterBySfdcAccountId($sfdcAccountId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sfdcAccountId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactTableMap::COL_SFDC_ACCOUNT_ID, $sfdcAccountId, $comparison);
    }

    /**
     * Filter the query on the sfdc_contact_name column
     *
     * Example usage:
     * <code>
     * $query->filterBySfdcContactName('fooValue');   // WHERE sfdc_contact_name = 'fooValue'
     * $query->filterBySfdcContactName('%fooValue%', Criteria::LIKE); // WHERE sfdc_contact_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sfdcContactName The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterBySfdcContactName($sfdcContactName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sfdcContactName)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactTableMap::COL_SFDC_CONTACT_NAME, $sfdcContactName, $comparison);
    }

    /**
     * Filter the query on the sfdc_contact_title column
     *
     * Example usage:
     * <code>
     * $query->filterBySfdcTitle('fooValue');   // WHERE sfdc_contact_title = 'fooValue'
     * $query->filterBySfdcTitle('%fooValue%', Criteria::LIKE); // WHERE sfdc_contact_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sfdcTitle The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterBySfdcTitle($sfdcTitle = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sfdcTitle)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactTableMap::COL_SFDC_CONTACT_TITLE, $sfdcTitle, $comparison);
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
     * @see       filterByMeetingAttendee()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ContactTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ContactTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ContactTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ContactTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ContactTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ContactTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContactTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \DataModels\DataModels\Account object
     *
     * @param \DataModels\DataModels\Account|ObjectCollection $account The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildContactQuery The current query, for fluid interface
     */
    public function filterByAccount($account, $comparison = null)
    {
        if ($account instanceof \DataModels\DataModels\Account) {
            return $this
                ->addUsingAlias(ContactTableMap::COL_ACCOUNT_ID, $account->getId(), $comparison);
        } elseif ($account instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ContactTableMap::COL_ACCOUNT_ID, $account->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildContactQuery The current query, for fluid interface
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
     * Filter the query by a related \DataModels\DataModels\Client object
     *
     * @param \DataModels\DataModels\Client|ObjectCollection $client The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildContactQuery The current query, for fluid interface
     */
    public function filterByClient($client, $comparison = null)
    {
        if ($client instanceof \DataModels\DataModels\Client) {
            return $this
                ->addUsingAlias(ContactTableMap::COL_CLIENT_ID, $client->getId(), $comparison);
        } elseif ($client instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ContactTableMap::COL_CLIENT_ID, $client->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByClient() only accepts arguments of type \DataModels\DataModels\Client or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Client relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function joinClient($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Client');

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
            $this->addJoinObject($join, 'Client');
        }

        return $this;
    }

    /**
     * Use the Client relation Client object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\ClientQuery A secondary query class using the current class as primary query
     */
    public function useClientQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinClient($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Client', '\DataModels\DataModels\ClientQuery');
    }

    /**
     * Filter the query by a related \DataModels\DataModels\MeetingAttendee object
     *
     * @param \DataModels\DataModels\MeetingAttendee|ObjectCollection $meetingAttendee The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildContactQuery The current query, for fluid interface
     */
    public function filterByMeetingAttendee($meetingAttendee, $comparison = null)
    {
        if ($meetingAttendee instanceof \DataModels\DataModels\MeetingAttendee) {
            return $this
                ->addUsingAlias(ContactTableMap::COL_ID, $meetingAttendee->getId(), $comparison);
        } elseif ($meetingAttendee instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ContactTableMap::COL_ID, $meetingAttendee->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildContactQuery The current query, for fluid interface
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
     * Filter the query by a related \DataModels\DataModels\ContactHistory object
     *
     * @param \DataModels\DataModels\ContactHistory|ObjectCollection $contactHistory the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildContactQuery The current query, for fluid interface
     */
    public function filterByContactHistory($contactHistory, $comparison = null)
    {
        if ($contactHistory instanceof \DataModels\DataModels\ContactHistory) {
            return $this
                ->addUsingAlias(ContactTableMap::COL_ID, $contactHistory->getContactId(), $comparison);
        } elseif ($contactHistory instanceof ObjectCollection) {
            return $this
                ->useContactHistoryQuery()
                ->filterByPrimaryKeys($contactHistory->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByContactHistory() only accepts arguments of type \DataModels\DataModels\ContactHistory or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ContactHistory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function joinContactHistory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ContactHistory');

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
            $this->addJoinObject($join, 'ContactHistory');
        }

        return $this;
    }

    /**
     * Use the ContactHistory relation ContactHistory object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\ContactHistoryQuery A secondary query class using the current class as primary query
     */
    public function useContactHistoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinContactHistory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ContactHistory', '\DataModels\DataModels\ContactHistoryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildContact $contact Object to remove from the list of results
     *
     * @return $this|ChildContactQuery The current query, for fluid interface
     */
    public function prune($contact = null)
    {
        if ($contact) {
            $this->addUsingAlias(ContactTableMap::COL_ID, $contact->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the contact table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ContactTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ContactTableMap::clearInstancePool();
            ContactTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ContactTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ContactTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ContactTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ContactTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildContactQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ContactTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildContactQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ContactTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildContactQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ContactTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildContactQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ContactTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildContactQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ContactTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildContactQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ContactTableMap::COL_CREATED_AT);
    }

} // ContactQuery
