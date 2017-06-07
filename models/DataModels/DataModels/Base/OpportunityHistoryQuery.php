<?php

namespace DataModels\DataModels\Base;

use \Exception;
use \PDO;
use DataModels\DataModels\OpportunityHistory as ChildOpportunityHistory;
use DataModels\DataModels\OpportunityHistoryQuery as ChildOpportunityHistoryQuery;
use DataModels\DataModels\Map\OpportunityHistoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'opportunity_history' table.
 *
 *
 *
 * @method     ChildOpportunityHistoryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildOpportunityHistoryQuery orderByOpportunityId($order = Criteria::ASC) Order by the opportunity_id column
 * @method     ChildOpportunityHistoryQuery orderBySFDCOpportunityId($order = Criteria::ASC) Order by the sfdc_opportunity_id column
 * @method     ChildOpportunityHistoryQuery orderByAccountSFDCId($order = Criteria::ASC) Order by the account_sfdc_id column
 * @method     ChildOpportunityHistoryQuery orderByAmount($order = Criteria::ASC) Order by the amount column
 * @method     ChildOpportunityHistoryQuery orderByCloseDate($order = Criteria::ASC) Order by the close_date column
 * @method     ChildOpportunityHistoryQuery orderByLastModifiedBy($order = Criteria::ASC) Order by the last_modified_by column
 * @method     ChildOpportunityHistoryQuery orderByNextStep($order = Criteria::ASC) Order by the next_step column
 * @method     ChildOpportunityHistoryQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildOpportunityHistoryQuery orderByOwnerId($order = Criteria::ASC) Order by the owner_id column
 * @method     ChildOpportunityHistoryQuery orderByStage($order = Criteria::ASC) Order by the stage column
 * @method     ChildOpportunityHistoryQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method     ChildOpportunityHistoryQuery orderByContact($order = Criteria::ASC) Order by the contact column
 * @method     ChildOpportunityHistoryQuery orderByCreatedBy($order = Criteria::ASC) Order by the created_by column
 * @method     ChildOpportunityHistoryQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildOpportunityHistoryQuery orderByExpectedRevenue($order = Criteria::ASC) Order by the expected_revenue column
 * @method     ChildOpportunityHistoryQuery orderByForecastCategory($order = Criteria::ASC) Order by the forecast_category column
 * @method     ChildOpportunityHistoryQuery orderByLeadSource($order = Criteria::ASC) Order by the lead_source column
 * @method     ChildOpportunityHistoryQuery orderByPriceBook($order = Criteria::ASC) Order by the price_book column
 * @method     ChildOpportunityHistoryQuery orderByPrimaryCampaignSource($order = Criteria::ASC) Order by the primary_campaign_source column
 * @method     ChildOpportunityHistoryQuery orderByIsPrivate($order = Criteria::ASC) Order by the is_private column
 * @method     ChildOpportunityHistoryQuery orderByProbability($order = Criteria::ASC) Order by the probability column
 * @method     ChildOpportunityHistoryQuery orderByQuantity($order = Criteria::ASC) Order by the quantity column
 * @method     ChildOpportunityHistoryQuery orderBySyncedQuote($order = Criteria::ASC) Order by the synced_quote column
 * @method     ChildOpportunityHistoryQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildOpportunityHistoryQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildOpportunityHistoryQuery groupById() Group by the id column
 * @method     ChildOpportunityHistoryQuery groupByOpportunityId() Group by the opportunity_id column
 * @method     ChildOpportunityHistoryQuery groupBySFDCOpportunityId() Group by the sfdc_opportunity_id column
 * @method     ChildOpportunityHistoryQuery groupByAccountSFDCId() Group by the account_sfdc_id column
 * @method     ChildOpportunityHistoryQuery groupByAmount() Group by the amount column
 * @method     ChildOpportunityHistoryQuery groupByCloseDate() Group by the close_date column
 * @method     ChildOpportunityHistoryQuery groupByLastModifiedBy() Group by the last_modified_by column
 * @method     ChildOpportunityHistoryQuery groupByNextStep() Group by the next_step column
 * @method     ChildOpportunityHistoryQuery groupByName() Group by the name column
 * @method     ChildOpportunityHistoryQuery groupByOwnerId() Group by the owner_id column
 * @method     ChildOpportunityHistoryQuery groupByStage() Group by the stage column
 * @method     ChildOpportunityHistoryQuery groupByType() Group by the type column
 * @method     ChildOpportunityHistoryQuery groupByContact() Group by the contact column
 * @method     ChildOpportunityHistoryQuery groupByCreatedBy() Group by the created_by column
 * @method     ChildOpportunityHistoryQuery groupByDescription() Group by the description column
 * @method     ChildOpportunityHistoryQuery groupByExpectedRevenue() Group by the expected_revenue column
 * @method     ChildOpportunityHistoryQuery groupByForecastCategory() Group by the forecast_category column
 * @method     ChildOpportunityHistoryQuery groupByLeadSource() Group by the lead_source column
 * @method     ChildOpportunityHistoryQuery groupByPriceBook() Group by the price_book column
 * @method     ChildOpportunityHistoryQuery groupByPrimaryCampaignSource() Group by the primary_campaign_source column
 * @method     ChildOpportunityHistoryQuery groupByIsPrivate() Group by the is_private column
 * @method     ChildOpportunityHistoryQuery groupByProbability() Group by the probability column
 * @method     ChildOpportunityHistoryQuery groupByQuantity() Group by the quantity column
 * @method     ChildOpportunityHistoryQuery groupBySyncedQuote() Group by the synced_quote column
 * @method     ChildOpportunityHistoryQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildOpportunityHistoryQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildOpportunityHistoryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildOpportunityHistoryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildOpportunityHistoryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildOpportunityHistoryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildOpportunityHistoryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildOpportunityHistoryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildOpportunityHistoryQuery leftJoinOpportunity($relationAlias = null) Adds a LEFT JOIN clause to the query using the Opportunity relation
 * @method     ChildOpportunityHistoryQuery rightJoinOpportunity($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Opportunity relation
 * @method     ChildOpportunityHistoryQuery innerJoinOpportunity($relationAlias = null) Adds a INNER JOIN clause to the query using the Opportunity relation
 *
 * @method     ChildOpportunityHistoryQuery joinWithOpportunity($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Opportunity relation
 *
 * @method     ChildOpportunityHistoryQuery leftJoinWithOpportunity() Adds a LEFT JOIN clause and with to the query using the Opportunity relation
 * @method     ChildOpportunityHistoryQuery rightJoinWithOpportunity() Adds a RIGHT JOIN clause and with to the query using the Opportunity relation
 * @method     ChildOpportunityHistoryQuery innerJoinWithOpportunity() Adds a INNER JOIN clause and with to the query using the Opportunity relation
 *
 * @method     \DataModels\DataModels\OpportunityQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildOpportunityHistory findOne(ConnectionInterface $con = null) Return the first ChildOpportunityHistory matching the query
 * @method     ChildOpportunityHistory findOneOrCreate(ConnectionInterface $con = null) Return the first ChildOpportunityHistory matching the query, or a new ChildOpportunityHistory object populated from the query conditions when no match is found
 *
 * @method     ChildOpportunityHistory findOneById(int $id) Return the first ChildOpportunityHistory filtered by the id column
 * @method     ChildOpportunityHistory findOneByOpportunityId(int $opportunity_id) Return the first ChildOpportunityHistory filtered by the opportunity_id column
 * @method     ChildOpportunityHistory findOneBySFDCOpportunityId(string $sfdc_opportunity_id) Return the first ChildOpportunityHistory filtered by the sfdc_opportunity_id column
 * @method     ChildOpportunityHistory findOneByAccountSFDCId(string $account_sfdc_id) Return the first ChildOpportunityHistory filtered by the account_sfdc_id column
 * @method     ChildOpportunityHistory findOneByAmount(string $amount) Return the first ChildOpportunityHistory filtered by the amount column
 * @method     ChildOpportunityHistory findOneByCloseDate(string $close_date) Return the first ChildOpportunityHistory filtered by the close_date column
 * @method     ChildOpportunityHistory findOneByLastModifiedBy(string $last_modified_by) Return the first ChildOpportunityHistory filtered by the last_modified_by column
 * @method     ChildOpportunityHistory findOneByNextStep(string $next_step) Return the first ChildOpportunityHistory filtered by the next_step column
 * @method     ChildOpportunityHistory findOneByName(string $name) Return the first ChildOpportunityHistory filtered by the name column
 * @method     ChildOpportunityHistory findOneByOwnerId(string $owner_id) Return the first ChildOpportunityHistory filtered by the owner_id column
 * @method     ChildOpportunityHistory findOneByStage(string $stage) Return the first ChildOpportunityHistory filtered by the stage column
 * @method     ChildOpportunityHistory findOneByType(string $type) Return the first ChildOpportunityHistory filtered by the type column
 * @method     ChildOpportunityHistory findOneByContact(string $contact) Return the first ChildOpportunityHistory filtered by the contact column
 * @method     ChildOpportunityHistory findOneByCreatedBy(string $created_by) Return the first ChildOpportunityHistory filtered by the created_by column
 * @method     ChildOpportunityHistory findOneByDescription(string $description) Return the first ChildOpportunityHistory filtered by the description column
 * @method     ChildOpportunityHistory findOneByExpectedRevenue(string $expected_revenue) Return the first ChildOpportunityHistory filtered by the expected_revenue column
 * @method     ChildOpportunityHistory findOneByForecastCategory(string $forecast_category) Return the first ChildOpportunityHistory filtered by the forecast_category column
 * @method     ChildOpportunityHistory findOneByLeadSource(string $lead_source) Return the first ChildOpportunityHistory filtered by the lead_source column
 * @method     ChildOpportunityHistory findOneByPriceBook(string $price_book) Return the first ChildOpportunityHistory filtered by the price_book column
 * @method     ChildOpportunityHistory findOneByPrimaryCampaignSource(string $primary_campaign_source) Return the first ChildOpportunityHistory filtered by the primary_campaign_source column
 * @method     ChildOpportunityHistory findOneByIsPrivate(boolean $is_private) Return the first ChildOpportunityHistory filtered by the is_private column
 * @method     ChildOpportunityHistory findOneByProbability(string $probability) Return the first ChildOpportunityHistory filtered by the probability column
 * @method     ChildOpportunityHistory findOneByQuantity(string $quantity) Return the first ChildOpportunityHistory filtered by the quantity column
 * @method     ChildOpportunityHistory findOneBySyncedQuote(string $synced_quote) Return the first ChildOpportunityHistory filtered by the synced_quote column
 * @method     ChildOpportunityHistory findOneByCreatedAt(string $created_at) Return the first ChildOpportunityHistory filtered by the created_at column
 * @method     ChildOpportunityHistory findOneByUpdatedAt(string $updated_at) Return the first ChildOpportunityHistory filtered by the updated_at column *

 * @method     ChildOpportunityHistory requirePk($key, ConnectionInterface $con = null) Return the ChildOpportunityHistory by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOne(ConnectionInterface $con = null) Return the first ChildOpportunityHistory matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOpportunityHistory requireOneById(int $id) Return the first ChildOpportunityHistory filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByOpportunityId(int $opportunity_id) Return the first ChildOpportunityHistory filtered by the opportunity_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneBySFDCOpportunityId(string $sfdc_opportunity_id) Return the first ChildOpportunityHistory filtered by the sfdc_opportunity_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByAccountSFDCId(string $account_sfdc_id) Return the first ChildOpportunityHistory filtered by the account_sfdc_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByAmount(string $amount) Return the first ChildOpportunityHistory filtered by the amount column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByCloseDate(string $close_date) Return the first ChildOpportunityHistory filtered by the close_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByLastModifiedBy(string $last_modified_by) Return the first ChildOpportunityHistory filtered by the last_modified_by column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByNextStep(string $next_step) Return the first ChildOpportunityHistory filtered by the next_step column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByName(string $name) Return the first ChildOpportunityHistory filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByOwnerId(string $owner_id) Return the first ChildOpportunityHistory filtered by the owner_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByStage(string $stage) Return the first ChildOpportunityHistory filtered by the stage column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByType(string $type) Return the first ChildOpportunityHistory filtered by the type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByContact(string $contact) Return the first ChildOpportunityHistory filtered by the contact column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByCreatedBy(string $created_by) Return the first ChildOpportunityHistory filtered by the created_by column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByDescription(string $description) Return the first ChildOpportunityHistory filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByExpectedRevenue(string $expected_revenue) Return the first ChildOpportunityHistory filtered by the expected_revenue column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByForecastCategory(string $forecast_category) Return the first ChildOpportunityHistory filtered by the forecast_category column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByLeadSource(string $lead_source) Return the first ChildOpportunityHistory filtered by the lead_source column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByPriceBook(string $price_book) Return the first ChildOpportunityHistory filtered by the price_book column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByPrimaryCampaignSource(string $primary_campaign_source) Return the first ChildOpportunityHistory filtered by the primary_campaign_source column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByIsPrivate(boolean $is_private) Return the first ChildOpportunityHistory filtered by the is_private column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByProbability(string $probability) Return the first ChildOpportunityHistory filtered by the probability column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByQuantity(string $quantity) Return the first ChildOpportunityHistory filtered by the quantity column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneBySyncedQuote(string $synced_quote) Return the first ChildOpportunityHistory filtered by the synced_quote column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByCreatedAt(string $created_at) Return the first ChildOpportunityHistory filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildOpportunityHistory requireOneByUpdatedAt(string $updated_at) Return the first ChildOpportunityHistory filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildOpportunityHistory[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildOpportunityHistory objects based on current ModelCriteria
 * @method     ChildOpportunityHistory[]|ObjectCollection findById(int $id) Return ChildOpportunityHistory objects filtered by the id column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByOpportunityId(int $opportunity_id) Return ChildOpportunityHistory objects filtered by the opportunity_id column
 * @method     ChildOpportunityHistory[]|ObjectCollection findBySFDCOpportunityId(string $sfdc_opportunity_id) Return ChildOpportunityHistory objects filtered by the sfdc_opportunity_id column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByAccountSFDCId(string $account_sfdc_id) Return ChildOpportunityHistory objects filtered by the account_sfdc_id column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByAmount(string $amount) Return ChildOpportunityHistory objects filtered by the amount column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByCloseDate(string $close_date) Return ChildOpportunityHistory objects filtered by the close_date column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByLastModifiedBy(string $last_modified_by) Return ChildOpportunityHistory objects filtered by the last_modified_by column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByNextStep(string $next_step) Return ChildOpportunityHistory objects filtered by the next_step column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByName(string $name) Return ChildOpportunityHistory objects filtered by the name column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByOwnerId(string $owner_id) Return ChildOpportunityHistory objects filtered by the owner_id column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByStage(string $stage) Return ChildOpportunityHistory objects filtered by the stage column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByType(string $type) Return ChildOpportunityHistory objects filtered by the type column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByContact(string $contact) Return ChildOpportunityHistory objects filtered by the contact column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByCreatedBy(string $created_by) Return ChildOpportunityHistory objects filtered by the created_by column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByDescription(string $description) Return ChildOpportunityHistory objects filtered by the description column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByExpectedRevenue(string $expected_revenue) Return ChildOpportunityHistory objects filtered by the expected_revenue column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByForecastCategory(string $forecast_category) Return ChildOpportunityHistory objects filtered by the forecast_category column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByLeadSource(string $lead_source) Return ChildOpportunityHistory objects filtered by the lead_source column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByPriceBook(string $price_book) Return ChildOpportunityHistory objects filtered by the price_book column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByPrimaryCampaignSource(string $primary_campaign_source) Return ChildOpportunityHistory objects filtered by the primary_campaign_source column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByIsPrivate(boolean $is_private) Return ChildOpportunityHistory objects filtered by the is_private column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByProbability(string $probability) Return ChildOpportunityHistory objects filtered by the probability column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByQuantity(string $quantity) Return ChildOpportunityHistory objects filtered by the quantity column
 * @method     ChildOpportunityHistory[]|ObjectCollection findBySyncedQuote(string $synced_quote) Return ChildOpportunityHistory objects filtered by the synced_quote column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildOpportunityHistory objects filtered by the created_at column
 * @method     ChildOpportunityHistory[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildOpportunityHistory objects filtered by the updated_at column
 * @method     ChildOpportunityHistory[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class OpportunityHistoryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \DataModels\DataModels\Base\OpportunityHistoryQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\DataModels\\DataModels\\OpportunityHistory', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildOpportunityHistoryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildOpportunityHistoryQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildOpportunityHistoryQuery) {
            return $criteria;
        }
        $query = new ChildOpportunityHistoryQuery();
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
     * @return ChildOpportunityHistory|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OpportunityHistoryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = OpportunityHistoryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildOpportunityHistory A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, opportunity_id, sfdc_opportunity_id, account_sfdc_id, amount, close_date, last_modified_by, next_step, name, owner_id, stage, type, contact, created_by, description, expected_revenue, forecast_category, lead_source, price_book, primary_campaign_source, is_private, probability, quantity, synced_quote, created_at, updated_at FROM opportunity_history WHERE id = :p0';
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
            /** @var ChildOpportunityHistory $obj */
            $obj = new ChildOpportunityHistory();
            $obj->hydrate($row);
            OpportunityHistoryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildOpportunityHistory|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the opportunity_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOpportunityId(1234); // WHERE opportunity_id = 1234
     * $query->filterByOpportunityId(array(12, 34)); // WHERE opportunity_id IN (12, 34)
     * $query->filterByOpportunityId(array('min' => 12)); // WHERE opportunity_id > 12
     * </code>
     *
     * @see       filterByOpportunity()
     *
     * @param     mixed $opportunityId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByOpportunityId($opportunityId = null, $comparison = null)
    {
        if (is_array($opportunityId)) {
            $useMinMax = false;
            if (isset($opportunityId['min'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_OPPORTUNITY_ID, $opportunityId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($opportunityId['max'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_OPPORTUNITY_ID, $opportunityId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_OPPORTUNITY_ID, $opportunityId, $comparison);
    }

    /**
     * Filter the query on the sfdc_opportunity_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySFDCOpportunityId('fooValue');   // WHERE sfdc_opportunity_id = 'fooValue'
     * $query->filterBySFDCOpportunityId('%fooValue%', Criteria::LIKE); // WHERE sfdc_opportunity_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $sFDCOpportunityId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterBySFDCOpportunityId($sFDCOpportunityId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($sFDCOpportunityId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_SFDC_OPPORTUNITY_ID, $sFDCOpportunityId, $comparison);
    }

    /**
     * Filter the query on the account_sfdc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAccountSFDCId('fooValue');   // WHERE account_sfdc_id = 'fooValue'
     * $query->filterByAccountSFDCId('%fooValue%', Criteria::LIKE); // WHERE account_sfdc_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $accountSFDCId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByAccountSFDCId($accountSFDCId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($accountSFDCId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_ACCOUNT_SFDC_ID, $accountSFDCId, $comparison);
    }

    /**
     * Filter the query on the amount column
     *
     * Example usage:
     * <code>
     * $query->filterByAmount(1234); // WHERE amount = 1234
     * $query->filterByAmount(array(12, 34)); // WHERE amount IN (12, 34)
     * $query->filterByAmount(array('min' => 12)); // WHERE amount > 12
     * </code>
     *
     * @param     mixed $amount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByAmount($amount = null, $comparison = null)
    {
        if (is_array($amount)) {
            $useMinMax = false;
            if (isset($amount['min'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_AMOUNT, $amount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amount['max'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_AMOUNT, $amount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_AMOUNT, $amount, $comparison);
    }

    /**
     * Filter the query on the close_date column
     *
     * Example usage:
     * <code>
     * $query->filterByCloseDate('2011-03-14'); // WHERE close_date = '2011-03-14'
     * $query->filterByCloseDate('now'); // WHERE close_date = '2011-03-14'
     * $query->filterByCloseDate(array('max' => 'yesterday')); // WHERE close_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $closeDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByCloseDate($closeDate = null, $comparison = null)
    {
        if (is_array($closeDate)) {
            $useMinMax = false;
            if (isset($closeDate['min'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_CLOSE_DATE, $closeDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($closeDate['max'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_CLOSE_DATE, $closeDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_CLOSE_DATE, $closeDate, $comparison);
    }

    /**
     * Filter the query on the last_modified_by column
     *
     * Example usage:
     * <code>
     * $query->filterByLastModifiedBy('fooValue');   // WHERE last_modified_by = 'fooValue'
     * $query->filterByLastModifiedBy('%fooValue%', Criteria::LIKE); // WHERE last_modified_by LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastModifiedBy The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByLastModifiedBy($lastModifiedBy = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastModifiedBy)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_LAST_MODIFIED_BY, $lastModifiedBy, $comparison);
    }

    /**
     * Filter the query on the next_step column
     *
     * Example usage:
     * <code>
     * $query->filterByNextStep('fooValue');   // WHERE next_step = 'fooValue'
     * $query->filterByNextStep('%fooValue%', Criteria::LIKE); // WHERE next_step LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nextStep The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByNextStep($nextStep = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nextStep)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_NEXT_STEP, $nextStep, $comparison);
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
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_NAME, $name, $comparison);
    }

    /**
     * Filter the query on the owner_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOwnerId('fooValue');   // WHERE owner_id = 'fooValue'
     * $query->filterByOwnerId('%fooValue%', Criteria::LIKE); // WHERE owner_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ownerId The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByOwnerId($ownerId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ownerId)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_OWNER_ID, $ownerId, $comparison);
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
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByStage($stage = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($stage)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_STAGE, $stage, $comparison);
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
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the contact column
     *
     * Example usage:
     * <code>
     * $query->filterByContact('fooValue');   // WHERE contact = 'fooValue'
     * $query->filterByContact('%fooValue%', Criteria::LIKE); // WHERE contact LIKE '%fooValue%'
     * </code>
     *
     * @param     string $contact The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByContact($contact = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($contact)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_CONTACT, $contact, $comparison);
    }

    /**
     * Filter the query on the created_by column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedBy('fooValue');   // WHERE created_by = 'fooValue'
     * $query->filterByCreatedBy('%fooValue%', Criteria::LIKE); // WHERE created_by LIKE '%fooValue%'
     * </code>
     *
     * @param     string $createdBy The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByCreatedBy($createdBy = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($createdBy)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_CREATED_BY, $createdBy, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%', Criteria::LIKE); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the expected_revenue column
     *
     * Example usage:
     * <code>
     * $query->filterByExpectedRevenue(1234); // WHERE expected_revenue = 1234
     * $query->filterByExpectedRevenue(array(12, 34)); // WHERE expected_revenue IN (12, 34)
     * $query->filterByExpectedRevenue(array('min' => 12)); // WHERE expected_revenue > 12
     * </code>
     *
     * @param     mixed $expectedRevenue The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByExpectedRevenue($expectedRevenue = null, $comparison = null)
    {
        if (is_array($expectedRevenue)) {
            $useMinMax = false;
            if (isset($expectedRevenue['min'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_EXPECTED_REVENUE, $expectedRevenue['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($expectedRevenue['max'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_EXPECTED_REVENUE, $expectedRevenue['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_EXPECTED_REVENUE, $expectedRevenue, $comparison);
    }

    /**
     * Filter the query on the forecast_category column
     *
     * Example usage:
     * <code>
     * $query->filterByForecastCategory('fooValue');   // WHERE forecast_category = 'fooValue'
     * $query->filterByForecastCategory('%fooValue%', Criteria::LIKE); // WHERE forecast_category LIKE '%fooValue%'
     * </code>
     *
     * @param     string $forecastCategory The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByForecastCategory($forecastCategory = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($forecastCategory)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_FORECAST_CATEGORY, $forecastCategory, $comparison);
    }

    /**
     * Filter the query on the lead_source column
     *
     * Example usage:
     * <code>
     * $query->filterByLeadSource('fooValue');   // WHERE lead_source = 'fooValue'
     * $query->filterByLeadSource('%fooValue%', Criteria::LIKE); // WHERE lead_source LIKE '%fooValue%'
     * </code>
     *
     * @param     string $leadSource The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByLeadSource($leadSource = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($leadSource)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_LEAD_SOURCE, $leadSource, $comparison);
    }

    /**
     * Filter the query on the price_book column
     *
     * Example usage:
     * <code>
     * $query->filterByPriceBook('fooValue');   // WHERE price_book = 'fooValue'
     * $query->filterByPriceBook('%fooValue%', Criteria::LIKE); // WHERE price_book LIKE '%fooValue%'
     * </code>
     *
     * @param     string $priceBook The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByPriceBook($priceBook = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($priceBook)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_PRICE_BOOK, $priceBook, $comparison);
    }

    /**
     * Filter the query on the primary_campaign_source column
     *
     * Example usage:
     * <code>
     * $query->filterByPrimaryCampaignSource('fooValue');   // WHERE primary_campaign_source = 'fooValue'
     * $query->filterByPrimaryCampaignSource('%fooValue%', Criteria::LIKE); // WHERE primary_campaign_source LIKE '%fooValue%'
     * </code>
     *
     * @param     string $primaryCampaignSource The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByPrimaryCampaignSource($primaryCampaignSource = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($primaryCampaignSource)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_PRIMARY_CAMPAIGN_SOURCE, $primaryCampaignSource, $comparison);
    }

    /**
     * Filter the query on the is_private column
     *
     * Example usage:
     * <code>
     * $query->filterByIsPrivate(true); // WHERE is_private = true
     * $query->filterByIsPrivate('yes'); // WHERE is_private = true
     * </code>
     *
     * @param     boolean|string $isPrivate The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByIsPrivate($isPrivate = null, $comparison = null)
    {
        if (is_string($isPrivate)) {
            $isPrivate = in_array(strtolower($isPrivate), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_IS_PRIVATE, $isPrivate, $comparison);
    }

    /**
     * Filter the query on the probability column
     *
     * Example usage:
     * <code>
     * $query->filterByProbability(1234); // WHERE probability = 1234
     * $query->filterByProbability(array(12, 34)); // WHERE probability IN (12, 34)
     * $query->filterByProbability(array('min' => 12)); // WHERE probability > 12
     * </code>
     *
     * @param     mixed $probability The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByProbability($probability = null, $comparison = null)
    {
        if (is_array($probability)) {
            $useMinMax = false;
            if (isset($probability['min'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_PROBABILITY, $probability['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($probability['max'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_PROBABILITY, $probability['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_PROBABILITY, $probability, $comparison);
    }

    /**
     * Filter the query on the quantity column
     *
     * Example usage:
     * <code>
     * $query->filterByQuantity(1234); // WHERE quantity = 1234
     * $query->filterByQuantity(array(12, 34)); // WHERE quantity IN (12, 34)
     * $query->filterByQuantity(array('min' => 12)); // WHERE quantity > 12
     * </code>
     *
     * @param     mixed $quantity The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByQuantity($quantity = null, $comparison = null)
    {
        if (is_array($quantity)) {
            $useMinMax = false;
            if (isset($quantity['min'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_QUANTITY, $quantity['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($quantity['max'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_QUANTITY, $quantity['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_QUANTITY, $quantity, $comparison);
    }

    /**
     * Filter the query on the synced_quote column
     *
     * Example usage:
     * <code>
     * $query->filterBySyncedQuote('fooValue');   // WHERE synced_quote = 'fooValue'
     * $query->filterBySyncedQuote('%fooValue%', Criteria::LIKE); // WHERE synced_quote LIKE '%fooValue%'
     * </code>
     *
     * @param     string $syncedQuote The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterBySyncedQuote($syncedQuote = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($syncedQuote)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_SYNCED_QUOTE, $syncedQuote, $comparison);
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
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(OpportunityHistoryTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \DataModels\DataModels\Opportunity object
     *
     * @param \DataModels\DataModels\Opportunity|ObjectCollection $opportunity The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function filterByOpportunity($opportunity, $comparison = null)
    {
        if ($opportunity instanceof \DataModels\DataModels\Opportunity) {
            return $this
                ->addUsingAlias(OpportunityHistoryTableMap::COL_OPPORTUNITY_ID, $opportunity->getId(), $comparison);
        } elseif ($opportunity instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(OpportunityHistoryTableMap::COL_OPPORTUNITY_ID, $opportunity->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByOpportunity() only accepts arguments of type \DataModels\DataModels\Opportunity or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Opportunity relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function joinOpportunity($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Opportunity');

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
            $this->addJoinObject($join, 'Opportunity');
        }

        return $this;
    }

    /**
     * Use the Opportunity relation Opportunity object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \DataModels\DataModels\OpportunityQuery A secondary query class using the current class as primary query
     */
    public function useOpportunityQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOpportunity($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Opportunity', '\DataModels\DataModels\OpportunityQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildOpportunityHistory $opportunityHistory Object to remove from the list of results
     *
     * @return $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function prune($opportunityHistory = null)
    {
        if ($opportunityHistory) {
            $this->addUsingAlias(OpportunityHistoryTableMap::COL_ID, $opportunityHistory->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the opportunity_history table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OpportunityHistoryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            OpportunityHistoryTableMap::clearInstancePool();
            OpportunityHistoryTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(OpportunityHistoryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(OpportunityHistoryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            OpportunityHistoryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            OpportunityHistoryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(OpportunityHistoryTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(OpportunityHistoryTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(OpportunityHistoryTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(OpportunityHistoryTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildOpportunityHistoryQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(OpportunityHistoryTableMap::COL_CREATED_AT);
    }

} // OpportunityHistoryQuery
