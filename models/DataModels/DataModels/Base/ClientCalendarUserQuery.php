<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\ClientCalendarUser as ChildClientCalendarUser;
use DataModels\DataModels\ClientCalendarUserQuery as ChildClientCalendarUserQuery;
use DataModels\DataModels\MeetingAttendeeQuery as ChildMeetingAttendeeQuery;
use DataModels\DataModels\Map\ClientCalendarUserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'client_calendar_user' table.
 *
 *
 *
 * @method     ChildClientCalendarUserQuery orderByClientId($order = Criteria::ASC) Order by the client_id column
 * @method     ChildClientCalendarUserQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildClientCalendarUserQuery orderBySurname($order = Criteria::ASC) Order by the surname column
 * @method     ChildClientCalendarUserQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildClientCalendarUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildClientCalendarUserQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildClientCalendarUserQuery groupByClientId() Group by the client_id column
 * @method     ChildClientCalendarUserQuery groupByName() Group by the name column
 * @method     ChildClientCalendarUserQuery groupBySurname() Group by the surname column
 * @method     ChildClientCalendarUserQuery groupByTitle() Group by the title column
 * @method     ChildClientCalendarUserQuery groupByEmail() Group by the email column
 * @method     ChildClientCalendarUserQuery groupById() Group by the id column
 *
 * @method     ChildClientCalendarUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildClientCalendarUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildClientCalendarUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildClientCalendarUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildClientCalendarUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildClientCalendarUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildClientCalendarUserQuery leftJoinClient($relationAlias = null) Adds a LEFT JOIN clause to the query using the Client relation
 * @method     ChildClientCalendarUserQuery rightJoinClient($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Client relation
 * @method     ChildClientCalendarUserQuery innerJoinClient($relationAlias = null) Adds a INNER JOIN clause to the query using the Client relation
 *
 * @method     ChildClientCalendarUserQuery joinWithClient($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Client relation
 *
 * @method     ChildClientCalendarUserQuery leftJoinWithClient() Adds a LEFT JOIN clause and with to the query using the Client relation
 * @method     ChildClientCalendarUserQuery rightJoinWithClient() Adds a RIGHT JOIN clause and with to the query using the Client relation
 * @method     ChildClientCalendarUserQuery innerJoinWithClient() Adds a INNER JOIN clause and with to the query using the Client relation
 *
 * @method     ChildClientCalendarUserQuery leftJoinMeetingAttendee($relationAlias = null) Adds a LEFT JOIN clause to the query using the MeetingAttendee relation
 * @method     ChildClientCalendarUserQuery rightJoinMeetingAttendee($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MeetingAttendee relation
 * @method     ChildClientCalendarUserQuery innerJoinMeetingAttendee($relationAlias = null) Adds a INNER JOIN clause to the query using the MeetingAttendee relation
 *
 * @method     ChildClientCalendarUserQuery joinWithMeetingAttendee($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MeetingAttendee relation
 *
 * @method     ChildClientCalendarUserQuery leftJoinWithMeetingAttendee() Adds a LEFT JOIN clause and with to the query using the MeetingAttendee relation
 * @method     ChildClientCalendarUserQuery rightJoinWithMeetingAttendee() Adds a RIGHT JOIN clause and with to the query using the MeetingAttendee relation
 * @method     ChildClientCalendarUserQuery innerJoinWithMeetingAttendee() Adds a INNER JOIN clause and with to the query using the MeetingAttendee relation
 *
 * @method     ChildClientCalendarUserQuery leftJoinClientCalendarUserOAuth($relationAlias = null) Adds a LEFT JOIN clause to the query using the ClientCalendarUserOAuth relation
 * @method     ChildClientCalendarUserQuery rightJoinClientCalendarUserOAuth($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ClientCalendarUserOAuth relation
 * @method     ChildClientCalendarUserQuery innerJoinClientCalendarUserOAuth($relationAlias = null) Adds a INNER JOIN clause to the query using the ClientCalendarUserOAuth relation
 *
 * @method     ChildClientCalendarUserQuery joinWithClientCalendarUserOAuth($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the ClientCalendarUserOAuth relation
 *
 * @method     ChildClientCalendarUserQuery leftJoinWithClientCalendarUserOAuth() Adds a LEFT JOIN clause and with to the query using the ClientCalendarUserOAuth relation
 * @method     ChildClientCalendarUserQuery rightJoinWithClientCalendarUserOAuth() Adds a RIGHT JOIN clause and with to the query using the ClientCalendarUserOAuth relation
 * @method     ChildClientCalendarUserQuery innerJoinWithClientCalendarUserOAuth() Adds a INNER JOIN clause and with to the query using the ClientCalendarUserOAuth relation
 *
 * @method     ChildClientCalendarUserQuery leftJoinMeeting($relationAlias = null) Adds a LEFT JOIN clause to the query using the Meeting relation
 * @method     ChildClientCalendarUserQuery rightJoinMeeting($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Meeting relation
 * @method     ChildClientCalendarUserQuery innerJoinMeeting($relationAlias = null) Adds a INNER JOIN clause to the query using the Meeting relation
 *
 * @method     ChildClientCalendarUserQuery joinWithMeeting($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Meeting relation
 *
 * @method     ChildClientCalendarUserQuery leftJoinWithMeeting() Adds a LEFT JOIN clause and with to the query using the Meeting relation
 * @method     ChildClientCalendarUserQuery rightJoinWithMeeting() Adds a RIGHT JOIN clause and with to the query using the Meeting relation
 * @method     ChildClientCalendarUserQuery innerJoinWithMeeting() Adds a INNER JOIN clause and with to the query using the Meeting relation
 *
 * @method     \DataModels\DataModels\ClientQuery|\DataModels\DataModels\MeetingAttendeeQuery|\DataModels\DataModels\ClientCalendarUserOAuthQuery|\DataModels\DataModels\MeetingQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildClientCalendarUser findOne(ConnectionInterface $con = null) Return the first ChildClientCalendarUser matching the query
 * @method     ChildClientCalendarUser findOneOrCreate(ConnectionInterface $con = null) Return the first ChildClientCalendarUser matching the query, or a new ChildClientCalendarUser object populated from the query conditions when no match is found
 *
 * @method     ChildClientCalendarUser findOneByClientId(int $client_id) Return the first ChildClientCalendarUser filtered by the client_id column
 * @method     ChildClientCalendarUser findOneByName(string $name) Return the first ChildClientCalendarUser filtered by the name column
 * @method     ChildClientCalendarUser findOneBySurname(string $surname) Return the first ChildClientCalendarUser filtered by the surname column
 * @method     ChildClientCalendarUser findOneByTitle(string $title) Return the first ChildClientCalendarUser filtered by the title column
 * @method     ChildClientCalendarUser findOneByEmail(string $email) Return the first ChildClientCalendarUser filtered by the email column
 * @method     ChildClientCalendarUser findOneById(int $id) Return the first ChildClientCalendarUser filtered by the id column *

 * @method     ChildClientCalendarUser requirePk($key, ConnectionInterface $con = null) Return the ChildClientCalendarUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUser requireOne(ConnectionInterface $con = null) Return the first ChildClientCalendarUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildClientCalendarUser requireOneByClientId(int $client_id) Return the first ChildClientCalendarUser filtered by the client_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUser requireOneByName(string $name) Return the first ChildClientCalendarUser filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUser requireOneBySurname(string $surname) Return the first ChildClientCalendarUser filtered by the surname column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUser requireOneByTitle(string $title) Return the first ChildClientCalendarUser filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUser requireOneByEmail(string $email) Return the first ChildClientCalendarUser filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildClientCalendarUser requireOneById(int $id) Return the first ChildClientCalendarUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildClientCalendarUser[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildClientCalendarUser objects based on current ModelCriteria
 * @method     ChildClientCalendarUser[]|ObjectCollection findByClientId(int $client_id) Return ChildClientCalendarUser objects filtered by the client_id column
 * @method     ChildClientCalendarUser[]|ObjectCollection findByName(string $name) Return ChildClientCalendarUser objects filtered by the name column
 * @method     ChildClientCalendarUser[]|ObjectCollection findBySurname(string $surname) Return ChildClientCalendarUser objects filtered by the surname column
 * @method     ChildClientCalendarUser[]|ObjectCollection findByTitle(string $title) Return ChildClientCalendarUser objects filtered by the title column
 * @method     ChildClientCalendarUser[]|ObjectCollection findByEmail(string $email) Return ChildClientCalendarUser objects filtered by the email column
 * @method     ChildClientCalendarUser[]|ObjectCollection findById(int $id) Return ChildClientCalendarUser objects filtered by the id column
 * @method     ChildClientCalendarUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ClientCalendarUserQuery extends ChildMeetingAttendeeQuery
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\ClientCalendarUserQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\ClientCalendarUser', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildClientCalendarUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildClientCalendarUserQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildClientCalendarUserQuery) {
            return $criteria;
        }
        $query = new ChildClientCalendarUserQuery();
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
     * @return ChildClientCalendarUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ClientCalendarUserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ClientCalendarUserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildClientCalendarUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT client_id, name, surname, title, email, id FROM client_calendar_user WHERE id = :p0';
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
            /** @var ChildClientCalendarUser $obj */
            $obj = new ChildClientCalendarUser();
            $obj->hydrate($row);
            ClientCalendarUserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildClientCalendarUser|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ClientCalendarUserTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ClientCalendarUserTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function filterByClientId($clientId = null, $comparison = null)
    {
        if (is_array($clientId)) {
            $useMinMax = false;
            if (isset($clientId['min'])) {
                $this->addUsingAlias(ClientCalendarUserTableMap::COL_CLIENT_ID, $clientId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($clientId['max'])) {
                $this->addUsingAlias(ClientCalendarUserTableMap::COL_CLIENT_ID, $clientId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserTableMap::COL_CLIENT_ID, $clientId, $comparison);
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
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the surname column
     *
     * Example usage:
     * <code>
     * $query->filterBySurname('fooValue');   // WHERE surname = 'fooValue'
     * $query->filterBySurname('%fooValue%', Criteria::LIKE); // WHERE surname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $surname The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function filterBySurname($surname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($surname)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserTableMap::COL_SURNAME, $surname, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserTableMap::COL_TITLE, $title, $comparison);
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
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserTableMap::COL_EMAIL, $email, $comparison);
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
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ClientCalendarUserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ClientCalendarUserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ClientCalendarUserTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \DataModels\DataModels\Client object
     *
     * @param \DataModels\DataModels\Client|ObjectCollection $client The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function filterByClient($client, $comparison = null)
    {
        if ($client instanceof \DataModels\DataModels\Client) {
            return $this
                ->addUsingAlias(ClientCalendarUserTableMap::COL_CLIENT_ID, $client->getId(), $comparison);
        } elseif ($client instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ClientCalendarUserTableMap::COL_CLIENT_ID, $client->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
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
     * @return ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function filterByMeetingAttendee($meetingAttendee, $comparison = null)
    {
        if ($meetingAttendee instanceof \DataModels\DataModels\MeetingAttendee) {
            return $this
                ->addUsingAlias(ClientCalendarUserTableMap::COL_ID, $meetingAttendee->getId(), $comparison);
        } elseif ($meetingAttendee instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ClientCalendarUserTableMap::COL_ID, $meetingAttendee->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
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
     * Filter the query by a related \DataModels\DataModels\ClientCalendarUserOAuth object
     *
     * @param \DataModels\DataModels\ClientCalendarUserOAuth|ObjectCollection $clientCalendarUserOAuth the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function filterByClientCalendarUserOAuth($clientCalendarUserOAuth, $comparison = null)
    {
        if ($clientCalendarUserOAuth instanceof \DataModels\DataModels\ClientCalendarUserOAuth) {
            return $this
                ->addUsingAlias(ClientCalendarUserTableMap::COL_ID, $clientCalendarUserOAuth->getClientCalendarUserId(), $comparison);
        } elseif ($clientCalendarUserOAuth instanceof ObjectCollection) {
            return $this
                ->useClientCalendarUserOAuthQuery()
                ->filterByPrimaryKeys($clientCalendarUserOAuth->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByClientCalendarUserOAuth() only accepts arguments of type \DataModels\DataModels\ClientCalendarUserOAuth or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ClientCalendarUserOAuth relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function joinClientCalendarUserOAuth($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ClientCalendarUserOAuth');

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
            $this->addJoinObject($join, 'ClientCalendarUserOAuth');
        }

        return $this;
    }

    /**
     * Use the ClientCalendarUserOAuth relation ClientCalendarUserOAuth object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\ClientCalendarUserOAuthQuery A secondary query class using the current class as primary query
     */
    public function useClientCalendarUserOAuthQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinClientCalendarUserOAuth($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ClientCalendarUserOAuth', '\DataModels\DataModels\ClientCalendarUserOAuthQuery');
    }

    /**
     * Filter the query by a related \DataModels\DataModels\Meeting object
     *
     * @param \DataModels\DataModels\Meeting|ObjectCollection $meeting the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function filterByMeeting($meeting, $comparison = null)
    {
        if ($meeting instanceof \DataModels\DataModels\Meeting) {
            return $this
                ->addUsingAlias(ClientCalendarUserTableMap::COL_ID, $meeting->getClientCalendarUserId(), $comparison);
        } elseif ($meeting instanceof ObjectCollection) {
            return $this
                ->useMeetingQuery()
                ->filterByPrimaryKeys($meeting->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMeeting() only accepts arguments of type \DataModels\DataModels\Meeting or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Meeting relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function joinMeeting($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Meeting');

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
            $this->addJoinObject($join, 'Meeting');
        }

        return $this;
    }

    /**
     * Use the Meeting relation Meeting object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\MeetingQuery A secondary query class using the current class as primary query
     */
    public function useMeetingQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMeeting($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Meeting', '\DataModels\DataModels\MeetingQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildClientCalendarUser $clientCalendarUser Object to remove from the list of results
     *
     * @return $this|ChildClientCalendarUserQuery The current query, for fluid interface
     */
    public function prune($clientCalendarUser = null)
    {
        if ($clientCalendarUser) {
            $this->addUsingAlias(ClientCalendarUserTableMap::COL_ID, $clientCalendarUser->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the client_calendar_user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ClientCalendarUserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ClientCalendarUserTableMap::clearInstancePool();
            ClientCalendarUserTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ClientCalendarUserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ClientCalendarUserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ClientCalendarUserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ClientCalendarUserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ClientCalendarUserQuery
