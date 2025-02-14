<?php

namespace DataModels\DataModels\Base;

use \DateTime;
use \Exception;
use \PDO;
use DataModels\DataModels\Opportunity as ChildOpportunity;
use DataModels\DataModels\OpportunityHistory as ChildOpportunityHistory;
use DataModels\DataModels\OpportunityHistoryQuery as ChildOpportunityHistoryQuery;
use DataModels\DataModels\OpportunityQuery as ChildOpportunityQuery;
use DataModels\DataModels\Map\OpportunityHistoryTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'opportunity_history' table.
 *
 *
 *
 * @package    propel.generator.DataModels.DataModels.Base
 */
abstract class OpportunityHistory implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\DataModels\\DataModels\\Map\\OpportunityHistoryTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the opportunity_id field.
     *
     * @var        int
     */
    protected $opportunity_id;

    /**
     * The value for the sfdc_opportunity_id field.
     *
     * @var        string
     */
    protected $sfdc_opportunity_id;

    /**
     * The value for the account_sfdc_id field.
     *
     * @var        string
     */
    protected $account_sfdc_id;

    /**
     * The value for the amount field.
     *
     * @var        string
     */
    protected $amount;

    /**
     * The value for the close_date field.
     *
     * @var        DateTime
     */
    protected $close_date;

    /**
     * The value for the last_modified_by field.
     *
     * @var        string
     */
    protected $last_modified_by;

    /**
     * The value for the next_step field.
     *
     * @var        string
     */
    protected $next_step;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the owner_id field.
     *
     * @var        string
     */
    protected $owner_id;

    /**
     * The value for the stage field.
     *
     * @var        string
     */
    protected $stage;

    /**
     * The value for the type field.
     *
     * @var        string
     */
    protected $type;

    /**
     * The value for the contact field.
     *
     * @var        string
     */
    protected $contact;

    /**
     * The value for the created_by field.
     *
     * @var        string
     */
    protected $created_by;

    /**
     * The value for the description field.
     *
     * @var        string
     */
    protected $description;

    /**
     * The value for the expected_revenue field.
     *
     * @var        string
     */
    protected $expected_revenue;

    /**
     * The value for the forecast_category field.
     *
     * @var        string
     */
    protected $forecast_category;

    /**
     * The value for the lead_source field.
     *
     * @var        string
     */
    protected $lead_source;

    /**
     * The value for the price_book field.
     *
     * @var        string
     */
    protected $price_book;

    /**
     * The value for the primary_campaign_source field.
     *
     * @var        string
     */
    protected $primary_campaign_source;

    /**
     * The value for the is_private field.
     *
     * @var        boolean
     */
    protected $is_private;

    /**
     * The value for the probability field.
     *
     * @var        string
     */
    protected $probability;

    /**
     * The value for the quantity field.
     *
     * @var        string
     */
    protected $quantity;

    /**
     * The value for the synced_quote field.
     *
     * @var        string
     */
    protected $synced_quote;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * @var        ChildOpportunity
     */
    protected $aOpportunity;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of DataModels\DataModels\Base\OpportunityHistory object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>OpportunityHistory</code> instance.  If
     * <code>obj</code> is an instance of <code>OpportunityHistory</code>, delegates to
     * <code>equals(OpportunityHistory)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|OpportunityHistory The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [opportunity_id] column value.
     *
     * @return int
     */
    public function getOpportunityId()
    {
        return $this->opportunity_id;
    }

    /**
     * Get the [sfdc_opportunity_id] column value.
     *
     * @return string
     */
    public function getSFDCOpportunityId()
    {
        return $this->sfdc_opportunity_id;
    }

    /**
     * Get the [account_sfdc_id] column value.
     *
     * @return string
     */
    public function getAccountSFDCId()
    {
        return $this->account_sfdc_id;
    }

    /**
     * Get the [amount] column value.
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get the [optionally formatted] temporal [close_date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCloseDate($format = NULL)
    {
        if ($format === null) {
            return $this->close_date;
        } else {
            return $this->close_date instanceof \DateTimeInterface ? $this->close_date->format($format) : null;
        }
    }

    /**
     * Get the [last_modified_by] column value.
     *
     * @return string
     */
    public function getLastModifiedBy()
    {
        return $this->last_modified_by;
    }

    /**
     * Get the [next_step] column value.
     *
     * @return string
     */
    public function getNextStep()
    {
        return $this->next_step;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [owner_id] column value.
     *
     * @return string
     */
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * Get the [stage] column value.
     *
     * @return string
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * Get the [type] column value.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the [contact] column value.
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Get the [created_by] column value.
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [expected_revenue] column value.
     *
     * @return string
     */
    public function getExpectedRevenue()
    {
        return $this->expected_revenue;
    }

    /**
     * Get the [forecast_category] column value.
     *
     * @return string
     */
    public function getForecastCategory()
    {
        return $this->forecast_category;
    }

    /**
     * Get the [lead_source] column value.
     *
     * @return string
     */
    public function getLeadSource()
    {
        return $this->lead_source;
    }

    /**
     * Get the [price_book] column value.
     *
     * @return string
     */
    public function getPriceBook()
    {
        return $this->price_book;
    }

    /**
     * Get the [primary_campaign_source] column value.
     *
     * @return string
     */
    public function getPrimaryCampaignSource()
    {
        return $this->primary_campaign_source;
    }

    /**
     * Get the [is_private] column value.
     *
     * @return boolean
     */
    public function getIsPrivate()
    {
        return $this->is_private;
    }

    /**
     * Get the [is_private] column value.
     *
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->getIsPrivate();
    }

    /**
     * Get the [probability] column value.
     *
     * @return string
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * Get the [quantity] column value.
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Get the [synced_quote] column value.
     *
     * @return string
     */
    public function getSyncedQuote()
    {
        return $this->synced_quote;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [opportunity_id] column.
     *
     * @param int $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setOpportunityId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->opportunity_id !== $v) {
            $this->opportunity_id = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_OPPORTUNITY_ID] = true;
        }

        if ($this->aOpportunity !== null && $this->aOpportunity->getId() !== $v) {
            $this->aOpportunity = null;
        }

        return $this;
    } // setOpportunityId()

    /**
     * Set the value of [sfdc_opportunity_id] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setSFDCOpportunityId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sfdc_opportunity_id !== $v) {
            $this->sfdc_opportunity_id = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_SFDC_OPPORTUNITY_ID] = true;
        }

        return $this;
    } // setSFDCOpportunityId()

    /**
     * Set the value of [account_sfdc_id] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setAccountSFDCId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->account_sfdc_id !== $v) {
            $this->account_sfdc_id = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_ACCOUNT_SFDC_ID] = true;
        }

        return $this;
    } // setAccountSFDCId()

    /**
     * Set the value of [amount] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setAmount($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->amount !== $v) {
            $this->amount = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_AMOUNT] = true;
        }

        return $this;
    } // setAmount()

    /**
     * Sets the value of [close_date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setCloseDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->close_date !== null || $dt !== null) {
            if ($this->close_date === null || $dt === null || $dt->format("Y-m-d") !== $this->close_date->format("Y-m-d")) {
                $this->close_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OpportunityHistoryTableMap::COL_CLOSE_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setCloseDate()

    /**
     * Set the value of [last_modified_by] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setLastModifiedBy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_modified_by !== $v) {
            $this->last_modified_by = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_LAST_MODIFIED_BY] = true;
        }

        return $this;
    } // setLastModifiedBy()

    /**
     * Set the value of [next_step] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setNextStep($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->next_step !== $v) {
            $this->next_step = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_NEXT_STEP] = true;
        }

        return $this;
    } // setNextStep()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [owner_id] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setOwnerId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->owner_id !== $v) {
            $this->owner_id = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_OWNER_ID] = true;
        }

        return $this;
    } // setOwnerId()

    /**
     * Set the value of [stage] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setStage($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->stage !== $v) {
            $this->stage = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_STAGE] = true;
        }

        return $this;
    } // setStage()

    /**
     * Set the value of [type] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_TYPE] = true;
        }

        return $this;
    } // setType()

    /**
     * Set the value of [contact] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setContact($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->contact !== $v) {
            $this->contact = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_CONTACT] = true;
        }

        return $this;
    } // setContact()

    /**
     * Set the value of [created_by] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setCreatedBy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->created_by !== $v) {
            $this->created_by = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_CREATED_BY] = true;
        }

        return $this;
    } // setCreatedBy()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [expected_revenue] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setExpectedRevenue($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->expected_revenue !== $v) {
            $this->expected_revenue = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_EXPECTED_REVENUE] = true;
        }

        return $this;
    } // setExpectedRevenue()

    /**
     * Set the value of [forecast_category] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setForecastCategory($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->forecast_category !== $v) {
            $this->forecast_category = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_FORECAST_CATEGORY] = true;
        }

        return $this;
    } // setForecastCategory()

    /**
     * Set the value of [lead_source] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setLeadSource($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->lead_source !== $v) {
            $this->lead_source = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_LEAD_SOURCE] = true;
        }

        return $this;
    } // setLeadSource()

    /**
     * Set the value of [price_book] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setPriceBook($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->price_book !== $v) {
            $this->price_book = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_PRICE_BOOK] = true;
        }

        return $this;
    } // setPriceBook()

    /**
     * Set the value of [primary_campaign_source] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setPrimaryCampaignSource($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->primary_campaign_source !== $v) {
            $this->primary_campaign_source = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_PRIMARY_CAMPAIGN_SOURCE] = true;
        }

        return $this;
    } // setPrimaryCampaignSource()

    /**
     * Sets the value of the [is_private] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setIsPrivate($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_private !== $v) {
            $this->is_private = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_IS_PRIVATE] = true;
        }

        return $this;
    } // setIsPrivate()

    /**
     * Set the value of [probability] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setProbability($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->probability !== $v) {
            $this->probability = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_PROBABILITY] = true;
        }

        return $this;
    } // setProbability()

    /**
     * Set the value of [quantity] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setQuantity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->quantity !== $v) {
            $this->quantity = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_QUANTITY] = true;
        }

        return $this;
    } // setQuantity()

    /**
     * Set the value of [synced_quote] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setSyncedQuote($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->synced_quote !== $v) {
            $this->synced_quote = $v;
            $this->modifiedColumns[OpportunityHistoryTableMap::COL_SYNCED_QUOTE] = true;
        }

        return $this;
    } // setSyncedQuote()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OpportunityHistoryTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[OpportunityHistoryTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : OpportunityHistoryTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : OpportunityHistoryTableMap::translateFieldName('OpportunityId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->opportunity_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : OpportunityHistoryTableMap::translateFieldName('SFDCOpportunityId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sfdc_opportunity_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : OpportunityHistoryTableMap::translateFieldName('AccountSFDCId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->account_sfdc_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : OpportunityHistoryTableMap::translateFieldName('Amount', TableMap::TYPE_PHPNAME, $indexType)];
            $this->amount = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : OpportunityHistoryTableMap::translateFieldName('CloseDate', TableMap::TYPE_PHPNAME, $indexType)];
            $this->close_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : OpportunityHistoryTableMap::translateFieldName('LastModifiedBy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->last_modified_by = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : OpportunityHistoryTableMap::translateFieldName('NextStep', TableMap::TYPE_PHPNAME, $indexType)];
            $this->next_step = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : OpportunityHistoryTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : OpportunityHistoryTableMap::translateFieldName('OwnerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->owner_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : OpportunityHistoryTableMap::translateFieldName('Stage', TableMap::TYPE_PHPNAME, $indexType)];
            $this->stage = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : OpportunityHistoryTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : OpportunityHistoryTableMap::translateFieldName('Contact', TableMap::TYPE_PHPNAME, $indexType)];
            $this->contact = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : OpportunityHistoryTableMap::translateFieldName('CreatedBy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created_by = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : OpportunityHistoryTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : OpportunityHistoryTableMap::translateFieldName('ExpectedRevenue', TableMap::TYPE_PHPNAME, $indexType)];
            $this->expected_revenue = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : OpportunityHistoryTableMap::translateFieldName('ForecastCategory', TableMap::TYPE_PHPNAME, $indexType)];
            $this->forecast_category = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : OpportunityHistoryTableMap::translateFieldName('LeadSource', TableMap::TYPE_PHPNAME, $indexType)];
            $this->lead_source = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : OpportunityHistoryTableMap::translateFieldName('PriceBook', TableMap::TYPE_PHPNAME, $indexType)];
            $this->price_book = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : OpportunityHistoryTableMap::translateFieldName('PrimaryCampaignSource', TableMap::TYPE_PHPNAME, $indexType)];
            $this->primary_campaign_source = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : OpportunityHistoryTableMap::translateFieldName('IsPrivate', TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_private = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : OpportunityHistoryTableMap::translateFieldName('Probability', TableMap::TYPE_PHPNAME, $indexType)];
            $this->probability = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 22 + $startcol : OpportunityHistoryTableMap::translateFieldName('Quantity', TableMap::TYPE_PHPNAME, $indexType)];
            $this->quantity = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 23 + $startcol : OpportunityHistoryTableMap::translateFieldName('SyncedQuote', TableMap::TYPE_PHPNAME, $indexType)];
            $this->synced_quote = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 24 + $startcol : OpportunityHistoryTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 25 + $startcol : OpportunityHistoryTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 26; // 26 = OpportunityHistoryTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\DataModels\\DataModels\\OpportunityHistory'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aOpportunity !== null && $this->opportunity_id !== $this->aOpportunity->getId()) {
            $this->aOpportunity = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(OpportunityHistoryTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildOpportunityHistoryQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aOpportunity = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see OpportunityHistory::setDeleted()
     * @see OpportunityHistory::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(OpportunityHistoryTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildOpportunityHistoryQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(OpportunityHistoryTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(OpportunityHistoryTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
                if (!$this->isColumnModified(OpportunityHistoryTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(OpportunityHistoryTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                OpportunityHistoryTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aOpportunity !== null) {
                if ($this->aOpportunity->isModified() || $this->aOpportunity->isNew()) {
                    $affectedRows += $this->aOpportunity->save($con);
                }
                $this->setOpportunity($this->aOpportunity);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[OpportunityHistoryTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . OpportunityHistoryTableMap::COL_ID . ')');
        }
        if (null === $this->id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('opportunity_history_id_seq')");
                $this->id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_OPPORTUNITY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'opportunity_id';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_SFDC_OPPORTUNITY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'sfdc_opportunity_id';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_ACCOUNT_SFDC_ID)) {
            $modifiedColumns[':p' . $index++]  = 'account_sfdc_id';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_AMOUNT)) {
            $modifiedColumns[':p' . $index++]  = 'amount';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_CLOSE_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'close_date';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_LAST_MODIFIED_BY)) {
            $modifiedColumns[':p' . $index++]  = 'last_modified_by';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_NEXT_STEP)) {
            $modifiedColumns[':p' . $index++]  = 'next_step';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_OWNER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'owner_id';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_STAGE)) {
            $modifiedColumns[':p' . $index++]  = 'stage';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'type';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_CONTACT)) {
            $modifiedColumns[':p' . $index++]  = 'contact';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_CREATED_BY)) {
            $modifiedColumns[':p' . $index++]  = 'created_by';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'description';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_EXPECTED_REVENUE)) {
            $modifiedColumns[':p' . $index++]  = 'expected_revenue';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_FORECAST_CATEGORY)) {
            $modifiedColumns[':p' . $index++]  = 'forecast_category';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_LEAD_SOURCE)) {
            $modifiedColumns[':p' . $index++]  = 'lead_source';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_PRICE_BOOK)) {
            $modifiedColumns[':p' . $index++]  = 'price_book';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_PRIMARY_CAMPAIGN_SOURCE)) {
            $modifiedColumns[':p' . $index++]  = 'primary_campaign_source';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_IS_PRIVATE)) {
            $modifiedColumns[':p' . $index++]  = 'is_private';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_PROBABILITY)) {
            $modifiedColumns[':p' . $index++]  = 'probability';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_QUANTITY)) {
            $modifiedColumns[':p' . $index++]  = 'quantity';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_SYNCED_QUOTE)) {
            $modifiedColumns[':p' . $index++]  = 'synced_quote';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO opportunity_history (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'opportunity_id':
                        $stmt->bindValue($identifier, $this->opportunity_id, PDO::PARAM_INT);
                        break;
                    case 'sfdc_opportunity_id':
                        $stmt->bindValue($identifier, $this->sfdc_opportunity_id, PDO::PARAM_STR);
                        break;
                    case 'account_sfdc_id':
                        $stmt->bindValue($identifier, $this->account_sfdc_id, PDO::PARAM_STR);
                        break;
                    case 'amount':
                        $stmt->bindValue($identifier, $this->amount, PDO::PARAM_INT);
                        break;
                    case 'close_date':
                        $stmt->bindValue($identifier, $this->close_date ? $this->close_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'last_modified_by':
                        $stmt->bindValue($identifier, $this->last_modified_by, PDO::PARAM_STR);
                        break;
                    case 'next_step':
                        $stmt->bindValue($identifier, $this->next_step, PDO::PARAM_STR);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'owner_id':
                        $stmt->bindValue($identifier, $this->owner_id, PDO::PARAM_STR);
                        break;
                    case 'stage':
                        $stmt->bindValue($identifier, $this->stage, PDO::PARAM_STR);
                        break;
                    case 'type':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_STR);
                        break;
                    case 'contact':
                        $stmt->bindValue($identifier, $this->contact, PDO::PARAM_STR);
                        break;
                    case 'created_by':
                        $stmt->bindValue($identifier, $this->created_by, PDO::PARAM_STR);
                        break;
                    case 'description':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case 'expected_revenue':
                        $stmt->bindValue($identifier, $this->expected_revenue, PDO::PARAM_INT);
                        break;
                    case 'forecast_category':
                        $stmt->bindValue($identifier, $this->forecast_category, PDO::PARAM_STR);
                        break;
                    case 'lead_source':
                        $stmt->bindValue($identifier, $this->lead_source, PDO::PARAM_STR);
                        break;
                    case 'price_book':
                        $stmt->bindValue($identifier, $this->price_book, PDO::PARAM_STR);
                        break;
                    case 'primary_campaign_source':
                        $stmt->bindValue($identifier, $this->primary_campaign_source, PDO::PARAM_STR);
                        break;
                    case 'is_private':
                        $stmt->bindValue($identifier, $this->is_private, PDO::PARAM_BOOL);
                        break;
                    case 'probability':
                        $stmt->bindValue($identifier, $this->probability, PDO::PARAM_INT);
                        break;
                    case 'quantity':
                        $stmt->bindValue($identifier, $this->quantity, PDO::PARAM_INT);
                        break;
                    case 'synced_quote':
                        $stmt->bindValue($identifier, $this->synced_quote, PDO::PARAM_STR);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OpportunityHistoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getOpportunityId();
                break;
            case 2:
                return $this->getSFDCOpportunityId();
                break;
            case 3:
                return $this->getAccountSFDCId();
                break;
            case 4:
                return $this->getAmount();
                break;
            case 5:
                return $this->getCloseDate();
                break;
            case 6:
                return $this->getLastModifiedBy();
                break;
            case 7:
                return $this->getNextStep();
                break;
            case 8:
                return $this->getName();
                break;
            case 9:
                return $this->getOwnerId();
                break;
            case 10:
                return $this->getStage();
                break;
            case 11:
                return $this->getType();
                break;
            case 12:
                return $this->getContact();
                break;
            case 13:
                return $this->getCreatedBy();
                break;
            case 14:
                return $this->getDescription();
                break;
            case 15:
                return $this->getExpectedRevenue();
                break;
            case 16:
                return $this->getForecastCategory();
                break;
            case 17:
                return $this->getLeadSource();
                break;
            case 18:
                return $this->getPriceBook();
                break;
            case 19:
                return $this->getPrimaryCampaignSource();
                break;
            case 20:
                return $this->getIsPrivate();
                break;
            case 21:
                return $this->getProbability();
                break;
            case 22:
                return $this->getQuantity();
                break;
            case 23:
                return $this->getSyncedQuote();
                break;
            case 24:
                return $this->getCreatedAt();
                break;
            case 25:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['OpportunityHistory'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['OpportunityHistory'][$this->hashCode()] = true;
        $keys = OpportunityHistoryTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getOpportunityId(),
            $keys[2] => $this->getSFDCOpportunityId(),
            $keys[3] => $this->getAccountSFDCId(),
            $keys[4] => $this->getAmount(),
            $keys[5] => $this->getCloseDate(),
            $keys[6] => $this->getLastModifiedBy(),
            $keys[7] => $this->getNextStep(),
            $keys[8] => $this->getName(),
            $keys[9] => $this->getOwnerId(),
            $keys[10] => $this->getStage(),
            $keys[11] => $this->getType(),
            $keys[12] => $this->getContact(),
            $keys[13] => $this->getCreatedBy(),
            $keys[14] => $this->getDescription(),
            $keys[15] => $this->getExpectedRevenue(),
            $keys[16] => $this->getForecastCategory(),
            $keys[17] => $this->getLeadSource(),
            $keys[18] => $this->getPriceBook(),
            $keys[19] => $this->getPrimaryCampaignSource(),
            $keys[20] => $this->getIsPrivate(),
            $keys[21] => $this->getProbability(),
            $keys[22] => $this->getQuantity(),
            $keys[23] => $this->getSyncedQuote(),
            $keys[24] => $this->getCreatedAt(),
            $keys[25] => $this->getUpdatedAt(),
        );
        if ($result[$keys[5]] instanceof \DateTime) {
            $result[$keys[5]] = $result[$keys[5]]->format('c');
        }

        if ($result[$keys[24]] instanceof \DateTime) {
            $result[$keys[24]] = $result[$keys[24]]->format('c');
        }

        if ($result[$keys[25]] instanceof \DateTime) {
            $result[$keys[25]] = $result[$keys[25]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aOpportunity) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'opportunity';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'opportunity';
                        break;
                    default:
                        $key = 'Opportunity';
                }

                $result[$key] = $this->aOpportunity->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\DataModels\DataModels\OpportunityHistory
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = OpportunityHistoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\DataModels\DataModels\OpportunityHistory
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setOpportunityId($value);
                break;
            case 2:
                $this->setSFDCOpportunityId($value);
                break;
            case 3:
                $this->setAccountSFDCId($value);
                break;
            case 4:
                $this->setAmount($value);
                break;
            case 5:
                $this->setCloseDate($value);
                break;
            case 6:
                $this->setLastModifiedBy($value);
                break;
            case 7:
                $this->setNextStep($value);
                break;
            case 8:
                $this->setName($value);
                break;
            case 9:
                $this->setOwnerId($value);
                break;
            case 10:
                $this->setStage($value);
                break;
            case 11:
                $this->setType($value);
                break;
            case 12:
                $this->setContact($value);
                break;
            case 13:
                $this->setCreatedBy($value);
                break;
            case 14:
                $this->setDescription($value);
                break;
            case 15:
                $this->setExpectedRevenue($value);
                break;
            case 16:
                $this->setForecastCategory($value);
                break;
            case 17:
                $this->setLeadSource($value);
                break;
            case 18:
                $this->setPriceBook($value);
                break;
            case 19:
                $this->setPrimaryCampaignSource($value);
                break;
            case 20:
                $this->setIsPrivate($value);
                break;
            case 21:
                $this->setProbability($value);
                break;
            case 22:
                $this->setQuantity($value);
                break;
            case 23:
                $this->setSyncedQuote($value);
                break;
            case 24:
                $this->setCreatedAt($value);
                break;
            case 25:
                $this->setUpdatedAt($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = OpportunityHistoryTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setOpportunityId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setSFDCOpportunityId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setAccountSFDCId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setAmount($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setCloseDate($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setLastModifiedBy($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setNextStep($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setName($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setOwnerId($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setStage($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setType($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setContact($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setCreatedBy($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setDescription($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setExpectedRevenue($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setForecastCategory($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setLeadSource($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setPriceBook($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setPrimaryCampaignSource($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setIsPrivate($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setProbability($arr[$keys[21]]);
        }
        if (array_key_exists($keys[22], $arr)) {
            $this->setQuantity($arr[$keys[22]]);
        }
        if (array_key_exists($keys[23], $arr)) {
            $this->setSyncedQuote($arr[$keys[23]]);
        }
        if (array_key_exists($keys[24], $arr)) {
            $this->setCreatedAt($arr[$keys[24]]);
        }
        if (array_key_exists($keys[25], $arr)) {
            $this->setUpdatedAt($arr[$keys[25]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(OpportunityHistoryTableMap::DATABASE_NAME);

        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_ID)) {
            $criteria->add(OpportunityHistoryTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_OPPORTUNITY_ID)) {
            $criteria->add(OpportunityHistoryTableMap::COL_OPPORTUNITY_ID, $this->opportunity_id);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_SFDC_OPPORTUNITY_ID)) {
            $criteria->add(OpportunityHistoryTableMap::COL_SFDC_OPPORTUNITY_ID, $this->sfdc_opportunity_id);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_ACCOUNT_SFDC_ID)) {
            $criteria->add(OpportunityHistoryTableMap::COL_ACCOUNT_SFDC_ID, $this->account_sfdc_id);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_AMOUNT)) {
            $criteria->add(OpportunityHistoryTableMap::COL_AMOUNT, $this->amount);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_CLOSE_DATE)) {
            $criteria->add(OpportunityHistoryTableMap::COL_CLOSE_DATE, $this->close_date);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_LAST_MODIFIED_BY)) {
            $criteria->add(OpportunityHistoryTableMap::COL_LAST_MODIFIED_BY, $this->last_modified_by);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_NEXT_STEP)) {
            $criteria->add(OpportunityHistoryTableMap::COL_NEXT_STEP, $this->next_step);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_NAME)) {
            $criteria->add(OpportunityHistoryTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_OWNER_ID)) {
            $criteria->add(OpportunityHistoryTableMap::COL_OWNER_ID, $this->owner_id);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_STAGE)) {
            $criteria->add(OpportunityHistoryTableMap::COL_STAGE, $this->stage);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_TYPE)) {
            $criteria->add(OpportunityHistoryTableMap::COL_TYPE, $this->type);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_CONTACT)) {
            $criteria->add(OpportunityHistoryTableMap::COL_CONTACT, $this->contact);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_CREATED_BY)) {
            $criteria->add(OpportunityHistoryTableMap::COL_CREATED_BY, $this->created_by);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_DESCRIPTION)) {
            $criteria->add(OpportunityHistoryTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_EXPECTED_REVENUE)) {
            $criteria->add(OpportunityHistoryTableMap::COL_EXPECTED_REVENUE, $this->expected_revenue);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_FORECAST_CATEGORY)) {
            $criteria->add(OpportunityHistoryTableMap::COL_FORECAST_CATEGORY, $this->forecast_category);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_LEAD_SOURCE)) {
            $criteria->add(OpportunityHistoryTableMap::COL_LEAD_SOURCE, $this->lead_source);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_PRICE_BOOK)) {
            $criteria->add(OpportunityHistoryTableMap::COL_PRICE_BOOK, $this->price_book);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_PRIMARY_CAMPAIGN_SOURCE)) {
            $criteria->add(OpportunityHistoryTableMap::COL_PRIMARY_CAMPAIGN_SOURCE, $this->primary_campaign_source);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_IS_PRIVATE)) {
            $criteria->add(OpportunityHistoryTableMap::COL_IS_PRIVATE, $this->is_private);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_PROBABILITY)) {
            $criteria->add(OpportunityHistoryTableMap::COL_PROBABILITY, $this->probability);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_QUANTITY)) {
            $criteria->add(OpportunityHistoryTableMap::COL_QUANTITY, $this->quantity);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_SYNCED_QUOTE)) {
            $criteria->add(OpportunityHistoryTableMap::COL_SYNCED_QUOTE, $this->synced_quote);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_CREATED_AT)) {
            $criteria->add(OpportunityHistoryTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(OpportunityHistoryTableMap::COL_UPDATED_AT)) {
            $criteria->add(OpportunityHistoryTableMap::COL_UPDATED_AT, $this->updated_at);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildOpportunityHistoryQuery::create();
        $criteria->add(OpportunityHistoryTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \DataModels\DataModels\OpportunityHistory (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setOpportunityId($this->getOpportunityId());
        $copyObj->setSFDCOpportunityId($this->getSFDCOpportunityId());
        $copyObj->setAccountSFDCId($this->getAccountSFDCId());
        $copyObj->setAmount($this->getAmount());
        $copyObj->setCloseDate($this->getCloseDate());
        $copyObj->setLastModifiedBy($this->getLastModifiedBy());
        $copyObj->setNextStep($this->getNextStep());
        $copyObj->setName($this->getName());
        $copyObj->setOwnerId($this->getOwnerId());
        $copyObj->setStage($this->getStage());
        $copyObj->setType($this->getType());
        $copyObj->setContact($this->getContact());
        $copyObj->setCreatedBy($this->getCreatedBy());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setExpectedRevenue($this->getExpectedRevenue());
        $copyObj->setForecastCategory($this->getForecastCategory());
        $copyObj->setLeadSource($this->getLeadSource());
        $copyObj->setPriceBook($this->getPriceBook());
        $copyObj->setPrimaryCampaignSource($this->getPrimaryCampaignSource());
        $copyObj->setIsPrivate($this->getIsPrivate());
        $copyObj->setProbability($this->getProbability());
        $copyObj->setQuantity($this->getQuantity());
        $copyObj->setSyncedQuote($this->getSyncedQuote());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \DataModels\DataModels\OpportunityHistory Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildOpportunity object.
     *
     * @param  ChildOpportunity $v
     * @return $this|\DataModels\DataModels\OpportunityHistory The current object (for fluent API support)
     * @throws PropelException
     */
    public function setOpportunity(ChildOpportunity $v = null)
    {
        if ($v === null) {
            $this->setOpportunityId(NULL);
        } else {
            $this->setOpportunityId($v->getId());
        }

        $this->aOpportunity = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildOpportunity object, it will not be re-added.
        if ($v !== null) {
            $v->addOpportunityHistory($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildOpportunity object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildOpportunity The associated ChildOpportunity object.
     * @throws PropelException
     */
    public function getOpportunity(ConnectionInterface $con = null)
    {
        if ($this->aOpportunity === null && ($this->opportunity_id !== null)) {
            $this->aOpportunity = ChildOpportunityQuery::create()->findPk($this->opportunity_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aOpportunity->addOpportunityHistories($this);
             */
        }

        return $this->aOpportunity;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aOpportunity) {
            $this->aOpportunity->removeOpportunityHistory($this);
        }
        $this->id = null;
        $this->opportunity_id = null;
        $this->sfdc_opportunity_id = null;
        $this->account_sfdc_id = null;
        $this->amount = null;
        $this->close_date = null;
        $this->last_modified_by = null;
        $this->next_step = null;
        $this->name = null;
        $this->owner_id = null;
        $this->stage = null;
        $this->type = null;
        $this->contact = null;
        $this->created_by = null;
        $this->description = null;
        $this->expected_revenue = null;
        $this->forecast_category = null;
        $this->lead_source = null;
        $this->price_book = null;
        $this->primary_campaign_source = null;
        $this->is_private = null;
        $this->probability = null;
        $this->quantity = null;
        $this->synced_quote = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
        } // if ($deep)

        $this->aOpportunity = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(OpportunityHistoryTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildOpportunityHistory The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[OpportunityHistoryTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
