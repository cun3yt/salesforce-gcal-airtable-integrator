<?php

namespace DataModels\DataModels\Map;

use DataModels\DataModels\OpportunityHistory;
use DataModels\DataModels\OpportunityHistoryQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'opportunity_history' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class OpportunityHistoryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'DataModels.DataModels.Map.OpportunityHistoryTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'opportunity_history';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\DataModels\\DataModels\\OpportunityHistory';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'DataModels.DataModels.OpportunityHistory';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 26;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 26;

    /**
     * the column name for the id field
     */
    const COL_ID = 'opportunity_history.id';

    /**
     * the column name for the opportunity_id field
     */
    const COL_OPPORTUNITY_ID = 'opportunity_history.opportunity_id';

    /**
     * the column name for the sfdc_opportunity_id field
     */
    const COL_SFDC_OPPORTUNITY_ID = 'opportunity_history.sfdc_opportunity_id';

    /**
     * the column name for the account_sfdc_id field
     */
    const COL_ACCOUNT_SFDC_ID = 'opportunity_history.account_sfdc_id';

    /**
     * the column name for the amount field
     */
    const COL_AMOUNT = 'opportunity_history.amount';

    /**
     * the column name for the close_date field
     */
    const COL_CLOSE_DATE = 'opportunity_history.close_date';

    /**
     * the column name for the last_modified_by field
     */
    const COL_LAST_MODIFIED_BY = 'opportunity_history.last_modified_by';

    /**
     * the column name for the next_step field
     */
    const COL_NEXT_STEP = 'opportunity_history.next_step';

    /**
     * the column name for the name field
     */
    const COL_NAME = 'opportunity_history.name';

    /**
     * the column name for the owner_id field
     */
    const COL_OWNER_ID = 'opportunity_history.owner_id';

    /**
     * the column name for the stage field
     */
    const COL_STAGE = 'opportunity_history.stage';

    /**
     * the column name for the type field
     */
    const COL_TYPE = 'opportunity_history.type';

    /**
     * the column name for the contact field
     */
    const COL_CONTACT = 'opportunity_history.contact';

    /**
     * the column name for the created_by field
     */
    const COL_CREATED_BY = 'opportunity_history.created_by';

    /**
     * the column name for the description field
     */
    const COL_DESCRIPTION = 'opportunity_history.description';

    /**
     * the column name for the expected_revenue field
     */
    const COL_EXPECTED_REVENUE = 'opportunity_history.expected_revenue';

    /**
     * the column name for the forecast_category field
     */
    const COL_FORECAST_CATEGORY = 'opportunity_history.forecast_category';

    /**
     * the column name for the lead_source field
     */
    const COL_LEAD_SOURCE = 'opportunity_history.lead_source';

    /**
     * the column name for the price_book field
     */
    const COL_PRICE_BOOK = 'opportunity_history.price_book';

    /**
     * the column name for the primary_campaign_source field
     */
    const COL_PRIMARY_CAMPAIGN_SOURCE = 'opportunity_history.primary_campaign_source';

    /**
     * the column name for the is_private field
     */
    const COL_IS_PRIVATE = 'opportunity_history.is_private';

    /**
     * the column name for the probability field
     */
    const COL_PROBABILITY = 'opportunity_history.probability';

    /**
     * the column name for the quantity field
     */
    const COL_QUANTITY = 'opportunity_history.quantity';

    /**
     * the column name for the synced_quote field
     */
    const COL_SYNCED_QUOTE = 'opportunity_history.synced_quote';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'opportunity_history.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'opportunity_history.updated_at';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'OpportunityId', 'SFDCOpportunityId', 'AccountSFDCId', 'Amount', 'CloseDate', 'LastModifiedBy', 'NextStep', 'Name', 'OwnerId', 'Stage', 'Type', 'Contact', 'CreatedBy', 'Description', 'ExpectedRevenue', 'ForecastCategory', 'LeadSource', 'PriceBook', 'PrimaryCampaignSource', 'IsPrivate', 'Probability', 'Quantity', 'SyncedQuote', 'CreatedAt', 'UpdatedAt', ),
        self::TYPE_CAMELNAME     => array('id', 'opportunityId', 'sFDCOpportunityId', 'accountSFDCId', 'amount', 'closeDate', 'lastModifiedBy', 'nextStep', 'name', 'ownerId', 'stage', 'type', 'contact', 'createdBy', 'description', 'expectedRevenue', 'forecastCategory', 'leadSource', 'priceBook', 'primaryCampaignSource', 'isPrivate', 'probability', 'quantity', 'syncedQuote', 'createdAt', 'updatedAt', ),
        self::TYPE_COLNAME       => array(OpportunityHistoryTableMap::COL_ID, OpportunityHistoryTableMap::COL_OPPORTUNITY_ID, OpportunityHistoryTableMap::COL_SFDC_OPPORTUNITY_ID, OpportunityHistoryTableMap::COL_ACCOUNT_SFDC_ID, OpportunityHistoryTableMap::COL_AMOUNT, OpportunityHistoryTableMap::COL_CLOSE_DATE, OpportunityHistoryTableMap::COL_LAST_MODIFIED_BY, OpportunityHistoryTableMap::COL_NEXT_STEP, OpportunityHistoryTableMap::COL_NAME, OpportunityHistoryTableMap::COL_OWNER_ID, OpportunityHistoryTableMap::COL_STAGE, OpportunityHistoryTableMap::COL_TYPE, OpportunityHistoryTableMap::COL_CONTACT, OpportunityHistoryTableMap::COL_CREATED_BY, OpportunityHistoryTableMap::COL_DESCRIPTION, OpportunityHistoryTableMap::COL_EXPECTED_REVENUE, OpportunityHistoryTableMap::COL_FORECAST_CATEGORY, OpportunityHistoryTableMap::COL_LEAD_SOURCE, OpportunityHistoryTableMap::COL_PRICE_BOOK, OpportunityHistoryTableMap::COL_PRIMARY_CAMPAIGN_SOURCE, OpportunityHistoryTableMap::COL_IS_PRIVATE, OpportunityHistoryTableMap::COL_PROBABILITY, OpportunityHistoryTableMap::COL_QUANTITY, OpportunityHistoryTableMap::COL_SYNCED_QUOTE, OpportunityHistoryTableMap::COL_CREATED_AT, OpportunityHistoryTableMap::COL_UPDATED_AT, ),
        self::TYPE_FIELDNAME     => array('id', 'opportunity_id', 'sfdc_opportunity_id', 'account_sfdc_id', 'amount', 'close_date', 'last_modified_by', 'next_step', 'name', 'owner_id', 'stage', 'type', 'contact', 'created_by', 'description', 'expected_revenue', 'forecast_category', 'lead_source', 'price_book', 'primary_campaign_source', 'is_private', 'probability', 'quantity', 'synced_quote', 'created_at', 'updated_at', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'OpportunityId' => 1, 'SFDCOpportunityId' => 2, 'AccountSFDCId' => 3, 'Amount' => 4, 'CloseDate' => 5, 'LastModifiedBy' => 6, 'NextStep' => 7, 'Name' => 8, 'OwnerId' => 9, 'Stage' => 10, 'Type' => 11, 'Contact' => 12, 'CreatedBy' => 13, 'Description' => 14, 'ExpectedRevenue' => 15, 'ForecastCategory' => 16, 'LeadSource' => 17, 'PriceBook' => 18, 'PrimaryCampaignSource' => 19, 'IsPrivate' => 20, 'Probability' => 21, 'Quantity' => 22, 'SyncedQuote' => 23, 'CreatedAt' => 24, 'UpdatedAt' => 25, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'opportunityId' => 1, 'sFDCOpportunityId' => 2, 'accountSFDCId' => 3, 'amount' => 4, 'closeDate' => 5, 'lastModifiedBy' => 6, 'nextStep' => 7, 'name' => 8, 'ownerId' => 9, 'stage' => 10, 'type' => 11, 'contact' => 12, 'createdBy' => 13, 'description' => 14, 'expectedRevenue' => 15, 'forecastCategory' => 16, 'leadSource' => 17, 'priceBook' => 18, 'primaryCampaignSource' => 19, 'isPrivate' => 20, 'probability' => 21, 'quantity' => 22, 'syncedQuote' => 23, 'createdAt' => 24, 'updatedAt' => 25, ),
        self::TYPE_COLNAME       => array(OpportunityHistoryTableMap::COL_ID => 0, OpportunityHistoryTableMap::COL_OPPORTUNITY_ID => 1, OpportunityHistoryTableMap::COL_SFDC_OPPORTUNITY_ID => 2, OpportunityHistoryTableMap::COL_ACCOUNT_SFDC_ID => 3, OpportunityHistoryTableMap::COL_AMOUNT => 4, OpportunityHistoryTableMap::COL_CLOSE_DATE => 5, OpportunityHistoryTableMap::COL_LAST_MODIFIED_BY => 6, OpportunityHistoryTableMap::COL_NEXT_STEP => 7, OpportunityHistoryTableMap::COL_NAME => 8, OpportunityHistoryTableMap::COL_OWNER_ID => 9, OpportunityHistoryTableMap::COL_STAGE => 10, OpportunityHistoryTableMap::COL_TYPE => 11, OpportunityHistoryTableMap::COL_CONTACT => 12, OpportunityHistoryTableMap::COL_CREATED_BY => 13, OpportunityHistoryTableMap::COL_DESCRIPTION => 14, OpportunityHistoryTableMap::COL_EXPECTED_REVENUE => 15, OpportunityHistoryTableMap::COL_FORECAST_CATEGORY => 16, OpportunityHistoryTableMap::COL_LEAD_SOURCE => 17, OpportunityHistoryTableMap::COL_PRICE_BOOK => 18, OpportunityHistoryTableMap::COL_PRIMARY_CAMPAIGN_SOURCE => 19, OpportunityHistoryTableMap::COL_IS_PRIVATE => 20, OpportunityHistoryTableMap::COL_PROBABILITY => 21, OpportunityHistoryTableMap::COL_QUANTITY => 22, OpportunityHistoryTableMap::COL_SYNCED_QUOTE => 23, OpportunityHistoryTableMap::COL_CREATED_AT => 24, OpportunityHistoryTableMap::COL_UPDATED_AT => 25, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'opportunity_id' => 1, 'sfdc_opportunity_id' => 2, 'account_sfdc_id' => 3, 'amount' => 4, 'close_date' => 5, 'last_modified_by' => 6, 'next_step' => 7, 'name' => 8, 'owner_id' => 9, 'stage' => 10, 'type' => 11, 'contact' => 12, 'created_by' => 13, 'description' => 14, 'expected_revenue' => 15, 'forecast_category' => 16, 'lead_source' => 17, 'price_book' => 18, 'primary_campaign_source' => 19, 'is_private' => 20, 'probability' => 21, 'quantity' => 22, 'synced_quote' => 23, 'created_at' => 24, 'updated_at' => 25, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('opportunity_history');
        $this->setPhpName('OpportunityHistory');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\DataModels\\DataModels\\OpportunityHistory');
        $this->setPackage('DataModels.DataModels');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('opportunity_history_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('opportunity_id', 'OpportunityId', 'INTEGER', 'opportunity', 'id', false, null, null);
        $this->addColumn('sfdc_opportunity_id', 'SFDCOpportunityId', 'VARCHAR', false, 127, null);
        $this->addColumn('account_sfdc_id', 'AccountSFDCId', 'VARCHAR', false, 127, null);
        $this->addColumn('amount', 'Amount', 'NUMERIC', false, 16, null);
        $this->addColumn('close_date', 'CloseDate', 'DATE', false, null, null);
        $this->addColumn('last_modified_by', 'LastModifiedBy', 'VARCHAR', false, 127, null);
        $this->addColumn('next_step', 'NextStep', 'VARCHAR', false, 255, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 120, null);
        $this->addColumn('owner_id', 'OwnerId', 'VARCHAR', false, 127, null);
        $this->addColumn('stage', 'Stage', 'VARCHAR', false, 63, null);
        $this->addColumn('type', 'Type', 'VARCHAR', false, 63, null);
        $this->addColumn('contact', 'Contact', 'VARCHAR', false, 127, null);
        $this->addColumn('created_by', 'CreatedBy', 'VARCHAR', false, 127, null);
        $this->addColumn('description', 'Description', 'VARCHAR', false, null, null);
        $this->addColumn('expected_revenue', 'ExpectedRevenue', 'NUMERIC', false, 16, null);
        $this->addColumn('forecast_category', 'ForecastCategory', 'VARCHAR', false, 127, null);
        $this->addColumn('lead_source', 'LeadSource', 'VARCHAR', false, 127, null);
        $this->addColumn('price_book', 'PriceBook', 'VARCHAR', false, 127, null);
        $this->addColumn('primary_campaign_source', 'PrimaryCampaignSource', 'VARCHAR', false, 127, null);
        $this->addColumn('is_private', 'IsPrivate', 'BOOLEAN', false, null, null);
        $this->addColumn('probability', 'Probability', 'NUMERIC', false, 5, null);
        $this->addColumn('quantity', 'Quantity', 'NUMERIC', false, 16, null);
        $this->addColumn('synced_quote', 'SyncedQuote', 'VARCHAR', false, 63, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Opportunity', '\\DataModels\\DataModels\\Opportunity', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':opportunity_id',
    1 => ':id',
  ),
), null, null, null, false);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? OpportunityHistoryTableMap::CLASS_DEFAULT : OpportunityHistoryTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (OpportunityHistory object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = OpportunityHistoryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = OpportunityHistoryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + OpportunityHistoryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = OpportunityHistoryTableMap::OM_CLASS;
            /** @var OpportunityHistory $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            OpportunityHistoryTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = OpportunityHistoryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = OpportunityHistoryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var OpportunityHistory $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                OpportunityHistoryTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_ID);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_OPPORTUNITY_ID);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_SFDC_OPPORTUNITY_ID);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_ACCOUNT_SFDC_ID);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_AMOUNT);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_CLOSE_DATE);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_LAST_MODIFIED_BY);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_NEXT_STEP);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_NAME);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_OWNER_ID);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_STAGE);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_TYPE);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_CONTACT);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_CREATED_BY);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_DESCRIPTION);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_EXPECTED_REVENUE);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_FORECAST_CATEGORY);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_LEAD_SOURCE);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_PRICE_BOOK);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_PRIMARY_CAMPAIGN_SOURCE);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_IS_PRIVATE);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_PROBABILITY);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_QUANTITY);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_SYNCED_QUOTE);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(OpportunityHistoryTableMap::COL_UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.opportunity_id');
            $criteria->addSelectColumn($alias . '.sfdc_opportunity_id');
            $criteria->addSelectColumn($alias . '.account_sfdc_id');
            $criteria->addSelectColumn($alias . '.amount');
            $criteria->addSelectColumn($alias . '.close_date');
            $criteria->addSelectColumn($alias . '.last_modified_by');
            $criteria->addSelectColumn($alias . '.next_step');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.owner_id');
            $criteria->addSelectColumn($alias . '.stage');
            $criteria->addSelectColumn($alias . '.type');
            $criteria->addSelectColumn($alias . '.contact');
            $criteria->addSelectColumn($alias . '.created_by');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.expected_revenue');
            $criteria->addSelectColumn($alias . '.forecast_category');
            $criteria->addSelectColumn($alias . '.lead_source');
            $criteria->addSelectColumn($alias . '.price_book');
            $criteria->addSelectColumn($alias . '.primary_campaign_source');
            $criteria->addSelectColumn($alias . '.is_private');
            $criteria->addSelectColumn($alias . '.probability');
            $criteria->addSelectColumn($alias . '.quantity');
            $criteria->addSelectColumn($alias . '.synced_quote');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(OpportunityHistoryTableMap::DATABASE_NAME)->getTable(OpportunityHistoryTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(OpportunityHistoryTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(OpportunityHistoryTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new OpportunityHistoryTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a OpportunityHistory or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or OpportunityHistory object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OpportunityHistoryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \DataModels\DataModels\OpportunityHistory) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(OpportunityHistoryTableMap::DATABASE_NAME);
            $criteria->add(OpportunityHistoryTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = OpportunityHistoryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            OpportunityHistoryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                OpportunityHistoryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the opportunity_history table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return OpportunityHistoryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a OpportunityHistory or Criteria object.
     *
     * @param mixed               $criteria Criteria or OpportunityHistory object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(OpportunityHistoryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from OpportunityHistory object
        }

        if ($criteria->containsKey(OpportunityHistoryTableMap::COL_ID) && $criteria->keyContainsValue(OpportunityHistoryTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.OpportunityHistoryTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = OpportunityHistoryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // OpportunityHistoryTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
OpportunityHistoryTableMap::buildTableMap();
