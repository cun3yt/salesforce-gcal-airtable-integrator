<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\InternalClient as ChildInternalClient;
use DataModels\DataModels\InternalClientQuery as ChildInternalClientQuery;
use DataModels\DataModels\Map\InternalClientTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'internal_client' table.
 *
 *
 *
 * @method     ChildInternalClientQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildInternalClientQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildInternalClientQuery orderByWebsite($order = Criteria::ASC) Order by the website column
 * @method     ChildInternalClientQuery orderByEmailDomain($order = Criteria::ASC) Order by the email_domain column
 *
 * @method     ChildInternalClientQuery groupById() Group by the id column
 * @method     ChildInternalClientQuery groupByName() Group by the name column
 * @method     ChildInternalClientQuery groupByWebsite() Group by the website column
 * @method     ChildInternalClientQuery groupByEmailDomain() Group by the email_domain column
 *
 * @method     ChildInternalClientQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildInternalClientQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildInternalClientQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildInternalClientQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildInternalClientQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildInternalClientQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildInternalClientQuery leftJoinAccount($relationAlias = null) Adds a LEFT JOIN clause to the query using the Account relation
 * @method     ChildInternalClientQuery rightJoinAccount($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Account relation
 * @method     ChildInternalClientQuery innerJoinAccount($relationAlias = null) Adds a INNER JOIN clause to the query using the Account relation
 *
 * @method     ChildInternalClientQuery joinWithAccount($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Account relation
 *
 * @method     ChildInternalClientQuery leftJoinWithAccount() Adds a LEFT JOIN clause and with to the query using the Account relation
 * @method     ChildInternalClientQuery rightJoinWithAccount() Adds a RIGHT JOIN clause and with to the query using the Account relation
 * @method     ChildInternalClientQuery innerJoinWithAccount() Adds a INNER JOIN clause and with to the query using the Account relation
 *
 * @method     ChildInternalClientQuery leftJoinClientCalendarUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the ClientCalendarUser relation
 * @method     ChildInternalClientQuery rightJoinClientCalendarUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ClientCalendarUser relation
 * @method     ChildInternalClientQuery innerJoinClientCalendarUser($relationAlias = null) Adds a INNER JOIN clause to the query using the ClientCalendarUser relation
 *
 * @method     ChildInternalClientQuery joinWithClientCalendarUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ClientCalendarUser relation
 *
 * @method     ChildInternalClientQuery leftJoinWithClientCalendarUser() Adds a LEFT JOIN clause and with to the query using the ClientCalendarUser relation
 * @method     ChildInternalClientQuery rightJoinWithClientCalendarUser() Adds a RIGHT JOIN clause and with to the query using the ClientCalendarUser relation
 * @method     ChildInternalClientQuery innerJoinWithClientCalendarUser() Adds a INNER JOIN clause and with to the query using the ClientCalendarUser relation
 *
 * @method     \DataModels\DataModels\AccountQuery|\DataModels\DataModels\ClientCalendarUserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildInternalClient findOne(ConnectionInterface $con = null) Return the first ChildInternalClient matching the query
 * @method     ChildInternalClient findOneOrCreate(ConnectionInterface $con = null) Return the first ChildInternalClient matching the query, or a new ChildInternalClient object populated from the query conditions when no match is found
 *
 * @method     ChildInternalClient findOneById(int $id) Return the first ChildInternalClient filtered by the id column
 * @method     ChildInternalClient findOneByName(string $name) Return the first ChildInternalClient filtered by the name column
 * @method     ChildInternalClient findOneByWebsite(string $website) Return the first ChildInternalClient filtered by the website column
 * @method     ChildInternalClient findOneByEmailDomain(string $email_domain) Return the first ChildInternalClient filtered by the email_domain column *

 * @method     ChildInternalClient requirePk($key, ConnectionInterface $con = null) Return the ChildInternalClient by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInternalClient requireOne(ConnectionInterface $con = null) Return the first ChildInternalClient matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInternalClient requireOneById(int $id) Return the first ChildInternalClient filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInternalClient requireOneByName(string $name) Return the first ChildInternalClient filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInternalClient requireOneByWebsite(string $website) Return the first ChildInternalClient filtered by the website column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildInternalClient requireOneByEmailDomain(string $email_domain) Return the first ChildInternalClient filtered by the email_domain column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildInternalClient[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildInternalClient objects based on current ModelCriteria
 * @method     ChildInternalClient[]|ObjectCollection findById(int $id) Return ChildInternalClient objects filtered by the id column
 * @method     ChildInternalClient[]|ObjectCollection findByName(string $name) Return ChildInternalClient objects filtered by the name column
 * @method     ChildInternalClient[]|ObjectCollection findByWebsite(string $website) Return ChildInternalClient objects filtered by the website column
 * @method     ChildInternalClient[]|ObjectCollection findByEmailDomain(string $email_domain) Return ChildInternalClient objects filtered by the email_domain column
 * @method     ChildInternalClient[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class InternalClientQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\InternalClientQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\InternalClient', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildInternalClientQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildInternalClientQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildInternalClientQuery) {
            return $criteria;
        }
        $query = new ChildInternalClientQuery();
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
     * @return ChildInternalClient|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(InternalClientTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = InternalClientTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildInternalClient A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, name, website, email_domain FROM internal_client WHERE id = :p0';
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
            /** @var ChildInternalClient $obj */
            $obj = new ChildInternalClient();
            $obj->hydrate($row);
            InternalClientTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildInternalClient|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildInternalClientQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(InternalClientTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildInternalClientQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(InternalClientTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildInternalClientQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(InternalClientTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(InternalClientTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InternalClientTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildInternalClientQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InternalClientTableMap::COL_NAME, $name, $comparison);
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
     * @return $this|ChildInternalClientQuery The current query, for fluid interface
     */
    public function filterByWebsite($website = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($website)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InternalClientTableMap::COL_WEBSITE, $website, $comparison);
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
     * @return $this|ChildInternalClientQuery The current query, for fluid interface
     */
    public function filterByEmailDomain($emailDomain = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailDomain)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InternalClientTableMap::COL_EMAIL_DOMAIN, $emailDomain, $comparison);
    }

    /**
     * Filter the query by a related \DataModels\DataModels\Account object
     *
     * @param \DataModels\DataModels\Account|ObjectCollection $account the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInternalClientQuery The current query, for fluid interface
     */
    public function filterByAccount($account, $comparison = null)
    {
        if ($account instanceof \DataModels\DataModels\Account) {
            return $this
                ->addUsingAlias(InternalClientTableMap::COL_ID, $account->getInternalClientId(), $comparison);
        } elseif ($account instanceof ObjectCollection) {
            return $this
                ->useAccountQuery()
                ->filterByPrimaryKeys($account->getPrimaryKeys())
                ->endUse();
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
     * @return $this|ChildInternalClientQuery The current query, for fluid interface
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
     * Filter the query by a related \DataModels\DataModels\ClientCalendarUser object
     *
     * @param \DataModels\DataModels\ClientCalendarUser|ObjectCollection $clientCalendarUser the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildInternalClientQuery The current query, for fluid interface
     */
    public function filterByClientCalendarUser($clientCalendarUser, $comparison = null)
    {
        if ($clientCalendarUser instanceof \DataModels\DataModels\ClientCalendarUser) {
            return $this
                ->addUsingAlias(InternalClientTableMap::COL_ID, $clientCalendarUser->getInternalClientId(), $comparison);
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
     * @return $this|ChildInternalClientQuery The current query, for fluid interface
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
     * @param   ChildInternalClient $internalClient Object to remove from the list of results
     *
     * @return $this|ChildInternalClientQuery The current query, for fluid interface
     */
    public function prune($internalClient = null)
    {
        if ($internalClient) {
            $this->addUsingAlias(InternalClientTableMap::COL_ID, $internalClient->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the internal_client table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(InternalClientTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            InternalClientTableMap::clearInstancePool();
            InternalClientTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(InternalClientTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(InternalClientTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            InternalClientTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            InternalClientTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // InternalClientQuery
