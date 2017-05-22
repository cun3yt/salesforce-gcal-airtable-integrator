<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\BuyerStage as ChildBuyerStage;
use DataModels\DataModels\BuyerStageQuery as ChildBuyerStageQuery;
use DataModels\DataModels\Map\BuyerStageTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'buyer_stage' table.
 *
 *
 *
 * @method     ChildBuyerStageQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildBuyerStageQuery orderByInternalClientId($order = Criteria::ASC) Order by the internal_client_id column
 * @method     ChildBuyerStageQuery orderByStage($order = Criteria::ASC) Order by the stage column
 *
 * @method     ChildBuyerStageQuery groupById() Group by the id column
 * @method     ChildBuyerStageQuery groupByInternalClientId() Group by the internal_client_id column
 * @method     ChildBuyerStageQuery groupByStage() Group by the stage column
 *
 * @method     ChildBuyerStageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildBuyerStageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildBuyerStageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildBuyerStageQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildBuyerStageQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildBuyerStageQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildBuyerStage findOne(ConnectionInterface $con = null) Return the first ChildBuyerStage matching the query
 * @method     ChildBuyerStage findOneOrCreate(ConnectionInterface $con = null) Return the first ChildBuyerStage matching the query, or a new ChildBuyerStage object populated from the query conditions when no match is found
 *
 * @method     ChildBuyerStage findOneById(int $id) Return the first ChildBuyerStage filtered by the id column
 * @method     ChildBuyerStage findOneByInternalClientId(int $internal_client_id) Return the first ChildBuyerStage filtered by the internal_client_id column
 * @method     ChildBuyerStage findOneByStage(string $stage) Return the first ChildBuyerStage filtered by the stage column *

 * @method     ChildBuyerStage requirePk($key, ConnectionInterface $con = null) Return the ChildBuyerStage by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBuyerStage requireOne(ConnectionInterface $con = null) Return the first ChildBuyerStage matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildBuyerStage requireOneById(int $id) Return the first ChildBuyerStage filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBuyerStage requireOneByInternalClientId(int $internal_client_id) Return the first ChildBuyerStage filtered by the internal_client_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildBuyerStage requireOneByStage(string $stage) Return the first ChildBuyerStage filtered by the stage column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildBuyerStage[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildBuyerStage objects based on current ModelCriteria
 * @method     ChildBuyerStage[]|ObjectCollection findById(int $id) Return ChildBuyerStage objects filtered by the id column
 * @method     ChildBuyerStage[]|ObjectCollection findByInternalClientId(int $internal_client_id) Return ChildBuyerStage objects filtered by the internal_client_id column
 * @method     ChildBuyerStage[]|ObjectCollection findByStage(string $stage) Return ChildBuyerStage objects filtered by the stage column
 * @method     ChildBuyerStage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class BuyerStageQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\BuyerStageQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\BuyerStage', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildBuyerStageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildBuyerStageQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildBuyerStageQuery) {
            return $criteria;
        }
        $query = new ChildBuyerStageQuery();
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
     * @return ChildBuyerStage|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(BuyerStageTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = BuyerStageTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildBuyerStage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, internal_client_id, stage FROM buyer_stage WHERE id = :p0';
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
            /** @var ChildBuyerStage $obj */
            $obj = new ChildBuyerStage();
            $obj->hydrate($row);
            BuyerStageTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildBuyerStage|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildBuyerStageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BuyerStageTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildBuyerStageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BuyerStageTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildBuyerStageQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(BuyerStageTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BuyerStageTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BuyerStageTableMap::COL_ID, $id, $comparison);
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
     * @param     mixed $internalClientId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBuyerStageQuery The current query, for fluid interface
     */
    public function filterByInternalClientId($internalClientId = null, $comparison = null)
    {
        if (is_array($internalClientId)) {
            $useMinMax = false;
            if (isset($internalClientId['min'])) {
                $this->addUsingAlias(BuyerStageTableMap::COL_INTERNAL_CLIENT_ID, $internalClientId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($internalClientId['max'])) {
                $this->addUsingAlias(BuyerStageTableMap::COL_INTERNAL_CLIENT_ID, $internalClientId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BuyerStageTableMap::COL_INTERNAL_CLIENT_ID, $internalClientId, $comparison);
    }

    /**
     * Filter the query on the stage column
     *
     * Example usage:
     * <code>
     * $query->filterByStage('fooValue');   // WHERE stage = 'fooValue'
     * $query->filterByStage('%fooValue%', Criteria::LIKE); // WHERE stage LIKE '%fooValue%'
     * </code>
     *
     * @param     string $stage The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildBuyerStageQuery The current query, for fluid interface
     */
    public function filterByStage($stage = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($stage)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BuyerStageTableMap::COL_STAGE, $stage, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildBuyerStage $buyerStage Object to remove from the list of results
     *
     * @return $this|ChildBuyerStageQuery The current query, for fluid interface
     */
    public function prune($buyerStage = null)
    {
        if ($buyerStage) {
            $this->addUsingAlias(BuyerStageTableMap::COL_ID, $buyerStage->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the buyer_stage table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BuyerStageTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            BuyerStageTableMap::clearInstancePool();
            BuyerStageTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(BuyerStageTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(BuyerStageTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            BuyerStageTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            BuyerStageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // BuyerStageQuery
