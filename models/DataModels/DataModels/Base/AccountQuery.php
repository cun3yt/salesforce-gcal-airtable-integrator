<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\Account as ChildAccount;
use DataModels\DataModels\AccountQuery as ChildAccountQuery;
use DataModels\DataModels\Map\AccountTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'account' table.
 *
 *
 *
 * @method     ChildAccountQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildAccountQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildAccountQuery orderByEmailDomain($order = Criteria::ASC) Order by the email_domain column
 * @method     ChildAccountQuery orderByWebsite($order = Criteria::ASC) Order by the website column
 * @method     ChildAccountQuery orderBySfdcAccountId($order = Criteria::ASC) Order by the sfdc_account_id column
 * @method     ChildAccountQuery orderByInternalClientId($order = Criteria::ASC) Order by the internal_client_id column
 *
 * @method     ChildAccountQuery groupById() Group by the id column
 * @method     ChildAccountQuery groupByName() Group by the name column
 * @method     ChildAccountQuery groupByEmailDomain() Group by the email_domain column
 * @method     ChildAccountQuery groupByWebsite() Group by the website column
 * @method     ChildAccountQuery groupBySfdcAccountId() Group by the sfdc_account_id column
 * @method     ChildAccountQuery groupByInternalClientId() Group by the internal_client_id column
 *
 * @method     ChildAccountQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildAccountQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildAccountQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildAccountQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildAccountQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildAccountQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildAccountQuery leftJoinInternalClient($relationAlias = null) Adds a LEFT JOIN clause to the query using the InternalClient relation
 * @method     ChildAccountQuery rightJoinInternalClient($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InternalClient relation
 * @method     ChildAccountQuery innerJoinInternalClient($relationAlias = null) Adds a INNER JOIN clause to the query using the InternalClient relation
 *
 * @method     ChildAccountQuery joinWithInternalClient($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the InternalClient relation
 *
 * @method     ChildAccountQuery leftJoinWithInternalClient() Adds a LEFT JOIN clause and with to the query using the InternalClient relation
 * @method     ChildAccountQuery rightJoinWithInternalClient() Adds a RIGHT JOIN clause and with to the query using the InternalClient relation
 * @method     ChildAccountQuery innerJoinWithInternalClient() Adds a INNER JOIN clause and with to the query using the InternalClient relation
 *
 * @method     ChildAccountQuery leftJoinContact($relationAlias = null) Adds a LEFT JOIN clause to the query using the Contact relation
 * @method     ChildAccountQuery rightJoinContact($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Contact relation
 * @method     ChildAccountQuery innerJoinContact($relationAlias = null) Adds a INNER JOIN clause to the query using the Contact relation
 *
 * @method     ChildAccountQuery joinWithContact($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Contact relation
 *
 * @method     ChildAccountQuery leftJoinWithContact() Adds a LEFT JOIN clause and with to the query using the Contact relation
 * @method     ChildAccountQuery rightJoinWithContact() Adds a RIGHT JOIN clause and with to the query using the Contact relation
 * @method     ChildAccountQuery innerJoinWithContact() Adds a INNER JOIN clause and with to the query using the Contact relation
 *
 * @method     \DataModels\DataModels\InternalClientQuery|\DataModels\DataModels\ContactQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildAccount findOne(ConnectionInterface $con = null) Return the first ChildAccount matching the query
 * @method     ChildAccount findOneOrCreate(ConnectionInterface $con = null) Return the first ChildAccount matching the query, or a new ChildAccount object populated from the query conditions when no match is found
 *
 * @method     ChildAccount findOneById(int $id) Return the first ChildAccount filtered by the id column
 * @method     ChildAccount findOneByName(string $name) Return the first ChildAccount filtered by the name column
 * @method     ChildAccount findOneByEmailDomain(string $email_domain) Return the first ChildAccount filtered by the email_domain column
 * @method     ChildAccount findOneByWebsite(string $website) Return the first ChildAccount filtered by the website column
 * @method     ChildAccount findOneBySfdcAccountId(int $sfdc_account_id) Return the first ChildAccount filtered by the sfdc_account_id column
 * @method     ChildAccount findOneByInternalClientId(int $internal_client_id) Return the first ChildAccount filtered by the internal_client_id column *

 * @method     ChildAccount requirePk($key, ConnectionInterface $con = null) Return the ChildAccount by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccount requireOne(ConnectionInterface $con = null) Return the first ChildAccount matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAccount requireOneById(int $id) Return the first ChildAccount filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccount requireOneByName(string $name) Return the first ChildAccount filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccount requireOneByEmailDomain(string $email_domain) Return the first ChildAccount filtered by the email_domain column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccount requireOneByWebsite(string $website) Return the first ChildAccount filtered by the website column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccount requireOneBySfdcAccountId(int $sfdc_account_id) Return the first ChildAccount filtered by the sfdc_account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildAccount requireOneByInternalClientId(int $internal_client_id) Return the first ChildAccount filtered by the internal_client_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildAccount[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildAccount objects based on current ModelCriteria
 * @method     ChildAccount[]|ObjectCollection findById(int $id) Return ChildAccount objects filtered by the id column
 * @method     ChildAccount[]|ObjectCollection findByName(string $name) Return ChildAccount objects filtered by the name column
 * @method     ChildAccount[]|ObjectCollection findByEmailDomain(string $email_domain) Return ChildAccount objects filtered by the email_domain column
 * @method     ChildAccount[]|ObjectCollection findByWebsite(string $website) Return ChildAccount objects filtered by the website column
 * @method     ChildAccount[]|ObjectCollection findBySfdcAccountId(int $sfdc_account_id) Return ChildAccount objects filtered by the sfdc_account_id column
 * @method     ChildAccount[]|ObjectCollection findByInternalClientId(int $internal_client_id) Return ChildAccount objects filtered by the internal_client_id column
 * @method     ChildAccount[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class AccountQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\AccountQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\Account', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildAccountQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildAccountQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildAccountQuery) {
            return $criteria;
        }
        $query = new ChildAccountQuery();
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
     * @return ChildAccount|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(AccountTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = AccountTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildAccount A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, email_domain, website, sfdc_account_id, internal_client_id FROM account WHERE id = :p0';
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
            /** @var ChildAccount $obj */
            $obj = new ChildAccount();
            $obj->hydrate($row);
            AccountTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildAccount|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildAccountQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AccountTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildAccountQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AccountTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildAccountQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AccountTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AccountTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildAccountQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the email_domain column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailDomain('fooValue');   // WHERE email_domain = 'fooValue'
     * $query->filterByEmailDomain('%fooValue%', Criteria::LIKE); // WHERE email_domain LIKE '%fooValue%'
     * </code>
     *
     * @param     string $emailDomain The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAccountQuery The current query, for fluid interface
     */
    public function filterByEmailDomain($emailDomain = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailDomain)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountTableMap::COL_EMAIL_DOMAIN, $emailDomain, $comparison);
    }

    /**
     * Filter the query on the website column
     *
     * Example usage:
     * <code>
     * $query->filterByWebsite('fooValue');   // WHERE website = 'fooValue'
     * $query->filterByWebsite('%fooValue%', Criteria::LIKE); // WHERE website LIKE '%fooValue%'
     * </code>
     *
     * @param     string $website The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAccountQuery The current query, for fluid interface
     */
    public function filterByWebsite($website = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($website)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountTableMap::COL_WEBSITE, $website, $comparison);
    }

    /**
     * Filter the query on the sfdc_account_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySfdcAccountId(1234); // WHERE sfdc_account_id = 1234
     * $query->filterBySfdcAccountId(array(12, 34)); // WHERE sfdc_account_id IN (12, 34)
     * $query->filterBySfdcAccountId(array('min' => 12)); // WHERE sfdc_account_id > 12
     * </code>
     *
     * @param     mixed $sfdcAccountId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAccountQuery The current query, for fluid interface
     */
    public function filterBySfdcAccountId($sfdcAccountId = null, $comparison = null)
    {
        if (is_array($sfdcAccountId)) {
            $useMinMax = false;
            if (isset($sfdcAccountId['min'])) {
                $this->addUsingAlias(AccountTableMap::COL_SFDC_ACCOUNT_ID, $sfdcAccountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sfdcAccountId['max'])) {
                $this->addUsingAlias(AccountTableMap::COL_SFDC_ACCOUNT_ID, $sfdcAccountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountTableMap::COL_SFDC_ACCOUNT_ID, $sfdcAccountId, $comparison);
    }

    /**
     * Filter the query on the internal_client_id column
     *
     * Example usage:
     * <code>
     * $query->filterByInternalClientId(1234); // WHERE internal_client_id = 1234
     * $query->filterByInternalClientId(array(12, 34)); // WHERE internal_client_id IN (12, 34)
     * $query->filterByInternalClientId(array('min' => 12)); // WHERE internal_client_id > 12
     * </code>
     *
     * @see       filterByInternalClient()
     *
     * @param     mixed $internalClientId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildAccountQuery The current query, for fluid interface
     */
    public function filterByInternalClientId($internalClientId = null, $comparison = null)
    {
        if (is_array($internalClientId)) {
            $useMinMax = false;
            if (isset($internalClientId['min'])) {
                $this->addUsingAlias(AccountTableMap::COL_INTERNAL_CLIENT_ID, $internalClientId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($internalClientId['max'])) {
                $this->addUsingAlias(AccountTableMap::COL_INTERNAL_CLIENT_ID, $internalClientId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountTableMap::COL_INTERNAL_CLIENT_ID, $internalClientId, $comparison);
    }

    /**
     * Filter the query by a related \DataModels\DataModels\InternalClient object
     *
     * @param \DataModels\DataModels\InternalClient|ObjectCollection $internalClient The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildAccountQuery The current query, for fluid interface
     */
    public function filterByInternalClient($internalClient, $comparison = null)
    {
        if ($internalClient instanceof \DataModels\DataModels\InternalClient) {
            return $this
                ->addUsingAlias(AccountTableMap::COL_INTERNAL_CLIENT_ID, $internalClient->getId(), $comparison);
        } elseif ($internalClient instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AccountTableMap::COL_INTERNAL_CLIENT_ID, $internalClient->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByInternalClient() only accepts arguments of type \DataModels\DataModels\InternalClient or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InternalClient relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildAccountQuery The current query, for fluid interface
     */
    public function joinInternalClient($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InternalClient');

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
            $this->addJoinObject($join, 'InternalClient');
        }

        return $this;
    }

    /**
     * Use the InternalClient relation InternalClient object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\InternalClientQuery A secondary query class using the current class as primary query
     */
    public function useInternalClientQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinInternalClient($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InternalClient', '\DataModels\DataModels\InternalClientQuery');
    }

    /**
     * Filter the query by a related \DataModels\DataModels\Contact object
     *
     * @param \DataModels\DataModels\Contact|ObjectCollection $contact the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildAccountQuery The current query, for fluid interface
     */
    public function filterByContact($contact, $comparison = null)
    {
        if ($contact instanceof \DataModels\DataModels\Contact) {
            return $this
                ->addUsingAlias(AccountTableMap::COL_ID, $contact->getAccountId(), $comparison);
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
     * @return $this|ChildAccountQuery The current query, for fluid interface
     */
    public function joinContact($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useContactQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinContact($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Contact', '\DataModels\DataModels\ContactQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildAccount $account Object to remove from the list of results
     *
     * @return $this|ChildAccountQuery The current query, for fluid interface
     */
    public function prune($account = null)
    {
        if ($account) {
            $this->addUsingAlias(AccountTableMap::COL_ID, $account->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the account table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AccountTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AccountTableMap::clearInstancePool();
            AccountTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(AccountTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(AccountTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            AccountTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            AccountTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // AccountQuery
