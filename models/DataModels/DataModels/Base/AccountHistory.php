<?php

namespace DataModels\DataModels\Base;

use \DateTime;
use \Exception;
use \PDO;
use DataModels\DataModels\Account as ChildAccount;
use DataModels\DataModels\AccountHistory as ChildAccountHistory;
use DataModels\DataModels\AccountHistoryQuery as ChildAccountHistoryQuery;
use DataModels\DataModels\AccountQuery as ChildAccountQuery;
use DataModels\DataModels\Map\AccountHistoryTableMap;
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
 * Base class that represents a row from the 'account_history' table.
 *
 *
 *
 * @package    propel.generator.DataModels.DataModels.Base
 */
abstract class AccountHistory implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\DataModels\\DataModels\\Map\\AccountHistoryTableMap';


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
     * The value for the account_id field.
     *
     * @var        int
     */
    protected $account_id;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the num_employees field.
     *
     * @var        int
     */
    protected $num_employees;

    /**
     * The value for the website field.
     *
     * @var        string
     */
    protected $website;

    /**
     * The value for the annual_revenue field.
     *
     * @var        string
     */
    protected $annual_revenue;

    /**
     * The value for the industry field.
     *
     * @var        string
     */
    protected $industry;

    /**
     * The value for the type field.
     *
     * @var        string
     */
    protected $type;

    /**
     * The value for the billing_latitude field.
     *
     * @var        string
     */
    protected $billing_latitude;

    /**
     * The value for the billing_longitude field.
     *
     * @var        string
     */
    protected $billing_longitude;

    /**
     * The value for the billing_postal_code field.
     *
     * @var        string
     */
    protected $billing_postal_code;

    /**
     * The value for the billing_state field.
     *
     * @var        string
     */
    protected $billing_state;

    /**
     * The value for the billing_cycle_id field.
     *
     * @var        int
     */
    protected $billing_cycle_id;

    /**
     * The value for the billing_city field.
     *
     * @var        string
     */
    protected $billing_city;

    /**
     * The value for the billing_street field.
     *
     * @var        string
     */
    protected $billing_street;

    /**
     * The value for the billing_country field.
     *
     * @var        string
     */
    protected $billing_country;

    /**
     * The value for the last_activity_date field.
     *
     * @var        DateTime
     */
    protected $last_activity_date;

    /**
     * The value for the owner_id field.
     *
     * @var        string
     */
    protected $owner_id;

    /**
     * The value for the account_status_15five_only field.
     *
     * @var        string
     */
    protected $account_status_15five_only;

    /**
     * The value for the arr_15five_only field.
     *
     * @var        string
     */
    protected $arr_15five_only;

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
     * @var        ChildAccount
     */
    protected $aAccount;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of DataModels\DataModels\Base\AccountHistory object.
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
     * Compares this with another <code>AccountHistory</code> instance.  If
     * <code>obj</code> is an instance of <code>AccountHistory</code>, delegates to
     * <code>equals(AccountHistory)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|AccountHistory The current object, for fluid interface
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
     * Get the [account_id] column value.
     *
     * @return int
     */
    public function getAccountId()
    {
        return $this->account_id;
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
     * Get the [num_employees] column value.
     *
     * @return int
     */
    public function getNumEmployees()
    {
        return $this->num_employees;
    }

    /**
     * Get the [website] column value.
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Get the [annual_revenue] column value.
     *
     * @return string
     */
    public function getAnnualRevenue()
    {
        return $this->annual_revenue;
    }

    /**
     * Get the [industry] column value.
     *
     * @return string
     */
    public function getIndustry()
    {
        return $this->industry;
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
     * Get the [billing_latitude] column value.
     *
     * @return string
     */
    public function getBillingLatitude()
    {
        return $this->billing_latitude;
    }

    /**
     * Get the [billing_longitude] column value.
     *
     * @return string
     */
    public function getBillingLongitude()
    {
        return $this->billing_longitude;
    }

    /**
     * Get the [billing_postal_code] column value.
     *
     * @return string
     */
    public function getBillingPostalCode()
    {
        return $this->billing_postal_code;
    }

    /**
     * Get the [billing_state] column value.
     *
     * @return string
     */
    public function getBillingState()
    {
        return $this->billing_state;
    }

    /**
     * Get the [billing_cycle_id] column value.
     *
     * @return int
     */
    public function getBillingCycleId()
    {
        return $this->billing_cycle_id;
    }

    /**
     * Get the [billing_city] column value.
     *
     * @return string
     */
    public function getBillingCity()
    {
        return $this->billing_city;
    }

    /**
     * Get the [billing_street] column value.
     *
     * @return string
     */
    public function getBillingStreet()
    {
        return $this->billing_street;
    }

    /**
     * Get the [billing_country] column value.
     *
     * @return string
     */
    public function getBillingCountry()
    {
        return $this->billing_country;
    }

    /**
     * Get the [optionally formatted] temporal [last_activity_date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastActivityDate($format = NULL)
    {
        if ($format === null) {
            return $this->last_activity_date;
        } else {
            return $this->last_activity_date instanceof \DateTimeInterface ? $this->last_activity_date->format($format) : null;
        }
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
     * Get the [account_status_15five_only] column value.
     *
     * @return string
     */
    public function getAccountStatus15FiveHack()
    {
        return $this->account_status_15five_only;
    }

    /**
     * Get the [arr_15five_only] column value.
     *
     * @return string
     */
    public function getARR15FiveHack()
    {
        return $this->arr_15five_only;
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
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [account_id] column.
     *
     * @param int $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setAccountId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->account_id !== $v) {
            $this->account_id = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_ACCOUNT_ID] = true;
        }

        if ($this->aAccount !== null && $this->aAccount->getId() !== $v) {
            $this->aAccount = null;
        }

        return $this;
    } // setAccountId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [num_employees] column.
     *
     * @param int $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setNumEmployees($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->num_employees !== $v) {
            $this->num_employees = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_NUM_EMPLOYEES] = true;
        }

        return $this;
    } // setNumEmployees()

    /**
     * Set the value of [website] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setWebsite($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->website !== $v) {
            $this->website = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_WEBSITE] = true;
        }

        return $this;
    } // setWebsite()

    /**
     * Set the value of [annual_revenue] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setAnnualRevenue($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->annual_revenue !== $v) {
            $this->annual_revenue = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_ANNUAL_REVENUE] = true;
        }

        return $this;
    } // setAnnualRevenue()

    /**
     * Set the value of [industry] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setIndustry($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->industry !== $v) {
            $this->industry = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_INDUSTRY] = true;
        }

        return $this;
    } // setIndustry()

    /**
     * Set the value of [type] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_TYPE] = true;
        }

        return $this;
    } // setType()

    /**
     * Set the value of [billing_latitude] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setBillingLatitude($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->billing_latitude !== $v) {
            $this->billing_latitude = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_BILLING_LATITUDE] = true;
        }

        return $this;
    } // setBillingLatitude()

    /**
     * Set the value of [billing_longitude] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setBillingLongitude($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->billing_longitude !== $v) {
            $this->billing_longitude = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_BILLING_LONGITUDE] = true;
        }

        return $this;
    } // setBillingLongitude()

    /**
     * Set the value of [billing_postal_code] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setBillingPostalCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->billing_postal_code !== $v) {
            $this->billing_postal_code = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_BILLING_POSTAL_CODE] = true;
        }

        return $this;
    } // setBillingPostalCode()

    /**
     * Set the value of [billing_state] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setBillingState($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->billing_state !== $v) {
            $this->billing_state = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_BILLING_STATE] = true;
        }

        return $this;
    } // setBillingState()

    /**
     * Set the value of [billing_cycle_id] column.
     *
     * @param int $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setBillingCycleId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->billing_cycle_id !== $v) {
            $this->billing_cycle_id = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_BILLING_CYCLE_ID] = true;
        }

        return $this;
    } // setBillingCycleId()

    /**
     * Set the value of [billing_city] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setBillingCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->billing_city !== $v) {
            $this->billing_city = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_BILLING_CITY] = true;
        }

        return $this;
    } // setBillingCity()

    /**
     * Set the value of [billing_street] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setBillingStreet($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->billing_street !== $v) {
            $this->billing_street = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_BILLING_STREET] = true;
        }

        return $this;
    } // setBillingStreet()

    /**
     * Set the value of [billing_country] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setBillingCountry($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->billing_country !== $v) {
            $this->billing_country = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_BILLING_COUNTRY] = true;
        }

        return $this;
    } // setBillingCountry()

    /**
     * Sets the value of [last_activity_date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setLastActivityDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_activity_date !== null || $dt !== null) {
            if ($this->last_activity_date === null || $dt === null || $dt->format("Y-m-d") !== $this->last_activity_date->format("Y-m-d")) {
                $this->last_activity_date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AccountHistoryTableMap::COL_LAST_ACTIVITY_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setLastActivityDate()

    /**
     * Set the value of [owner_id] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setOwnerId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->owner_id !== $v) {
            $this->owner_id = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_OWNER_ID] = true;
        }

        return $this;
    } // setOwnerId()

    /**
     * Set the value of [account_status_15five_only] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setAccountStatus15FiveHack($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->account_status_15five_only !== $v) {
            $this->account_status_15five_only = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_ACCOUNT_STATUS_15FIVE_ONLY] = true;
        }

        return $this;
    } // setAccountStatus15FiveHack()

    /**
     * Set the value of [arr_15five_only] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setARR15FiveHack($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->arr_15five_only !== $v) {
            $this->arr_15five_only = $v;
            $this->modifiedColumns[AccountHistoryTableMap::COL_ARR_15FIVE_ONLY] = true;
        }

        return $this;
    } // setARR15FiveHack()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AccountHistoryTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AccountHistoryTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : AccountHistoryTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : AccountHistoryTableMap::translateFieldName('AccountId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->account_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : AccountHistoryTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : AccountHistoryTableMap::translateFieldName('NumEmployees', TableMap::TYPE_PHPNAME, $indexType)];
            $this->num_employees = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : AccountHistoryTableMap::translateFieldName('Website', TableMap::TYPE_PHPNAME, $indexType)];
            $this->website = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : AccountHistoryTableMap::translateFieldName('AnnualRevenue', TableMap::TYPE_PHPNAME, $indexType)];
            $this->annual_revenue = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : AccountHistoryTableMap::translateFieldName('Industry', TableMap::TYPE_PHPNAME, $indexType)];
            $this->industry = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : AccountHistoryTableMap::translateFieldName('Type', TableMap::TYPE_PHPNAME, $indexType)];
            $this->type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : AccountHistoryTableMap::translateFieldName('BillingLatitude', TableMap::TYPE_PHPNAME, $indexType)];
            $this->billing_latitude = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : AccountHistoryTableMap::translateFieldName('BillingLongitude', TableMap::TYPE_PHPNAME, $indexType)];
            $this->billing_longitude = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : AccountHistoryTableMap::translateFieldName('BillingPostalCode', TableMap::TYPE_PHPNAME, $indexType)];
            $this->billing_postal_code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : AccountHistoryTableMap::translateFieldName('BillingState', TableMap::TYPE_PHPNAME, $indexType)];
            $this->billing_state = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : AccountHistoryTableMap::translateFieldName('BillingCycleId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->billing_cycle_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : AccountHistoryTableMap::translateFieldName('BillingCity', TableMap::TYPE_PHPNAME, $indexType)];
            $this->billing_city = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : AccountHistoryTableMap::translateFieldName('BillingStreet', TableMap::TYPE_PHPNAME, $indexType)];
            $this->billing_street = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : AccountHistoryTableMap::translateFieldName('BillingCountry', TableMap::TYPE_PHPNAME, $indexType)];
            $this->billing_country = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : AccountHistoryTableMap::translateFieldName('LastActivityDate', TableMap::TYPE_PHPNAME, $indexType)];
            $this->last_activity_date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : AccountHistoryTableMap::translateFieldName('OwnerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->owner_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 18 + $startcol : AccountHistoryTableMap::translateFieldName('AccountStatus15FiveHack', TableMap::TYPE_PHPNAME, $indexType)];
            $this->account_status_15five_only = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 19 + $startcol : AccountHistoryTableMap::translateFieldName('ARR15FiveHack', TableMap::TYPE_PHPNAME, $indexType)];
            $this->arr_15five_only = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 20 + $startcol : AccountHistoryTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 21 + $startcol : AccountHistoryTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 22; // 22 = AccountHistoryTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\DataModels\\DataModels\\AccountHistory'), 0, $e);
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
        if ($this->aAccount !== null && $this->account_id !== $this->aAccount->getId()) {
            $this->aAccount = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(AccountHistoryTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildAccountHistoryQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAccount = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see AccountHistory::setDeleted()
     * @see AccountHistory::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AccountHistoryTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildAccountHistoryQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(AccountHistoryTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(AccountHistoryTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
                if (!$this->isColumnModified(AccountHistoryTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(AccountHistoryTableMap::COL_UPDATED_AT)) {
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
                AccountHistoryTableMap::addInstanceToPool($this);
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

            if ($this->aAccount !== null) {
                if ($this->aAccount->isModified() || $this->aAccount->isNew()) {
                    $affectedRows += $this->aAccount->save($con);
                }
                $this->setAccount($this->aAccount);
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

        $this->modifiedColumns[AccountHistoryTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . AccountHistoryTableMap::COL_ID . ')');
        }
        if (null === $this->id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('account_history_id_seq')");
                $this->id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AccountHistoryTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'account_id';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_NUM_EMPLOYEES)) {
            $modifiedColumns[':p' . $index++]  = 'num_employees';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_WEBSITE)) {
            $modifiedColumns[':p' . $index++]  = 'website';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_ANNUAL_REVENUE)) {
            $modifiedColumns[':p' . $index++]  = 'annual_revenue';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_INDUSTRY)) {
            $modifiedColumns[':p' . $index++]  = 'industry';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'type';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_LATITUDE)) {
            $modifiedColumns[':p' . $index++]  = 'billing_latitude';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_LONGITUDE)) {
            $modifiedColumns[':p' . $index++]  = 'billing_longitude';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_POSTAL_CODE)) {
            $modifiedColumns[':p' . $index++]  = 'billing_postal_code';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_STATE)) {
            $modifiedColumns[':p' . $index++]  = 'billing_state';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_CYCLE_ID)) {
            $modifiedColumns[':p' . $index++]  = 'billing_cycle_id';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_CITY)) {
            $modifiedColumns[':p' . $index++]  = 'billing_city';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_STREET)) {
            $modifiedColumns[':p' . $index++]  = 'billing_street';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = 'billing_country';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_LAST_ACTIVITY_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'last_activity_date';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_OWNER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'owner_id';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_ACCOUNT_STATUS_15FIVE_ONLY)) {
            $modifiedColumns[':p' . $index++]  = 'account_status_15five_only';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_ARR_15FIVE_ONLY)) {
            $modifiedColumns[':p' . $index++]  = 'arr_15five_only';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO account_history (%s) VALUES (%s)',
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
                    case 'account_id':
                        $stmt->bindValue($identifier, $this->account_id, PDO::PARAM_INT);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'num_employees':
                        $stmt->bindValue($identifier, $this->num_employees, PDO::PARAM_INT);
                        break;
                    case 'website':
                        $stmt->bindValue($identifier, $this->website, PDO::PARAM_STR);
                        break;
                    case 'annual_revenue':
                        $stmt->bindValue($identifier, $this->annual_revenue, PDO::PARAM_STR);
                        break;
                    case 'industry':
                        $stmt->bindValue($identifier, $this->industry, PDO::PARAM_STR);
                        break;
                    case 'type':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_STR);
                        break;
                    case 'billing_latitude':
                        $stmt->bindValue($identifier, $this->billing_latitude, PDO::PARAM_STR);
                        break;
                    case 'billing_longitude':
                        $stmt->bindValue($identifier, $this->billing_longitude, PDO::PARAM_STR);
                        break;
                    case 'billing_postal_code':
                        $stmt->bindValue($identifier, $this->billing_postal_code, PDO::PARAM_STR);
                        break;
                    case 'billing_state':
                        $stmt->bindValue($identifier, $this->billing_state, PDO::PARAM_STR);
                        break;
                    case 'billing_cycle_id':
                        $stmt->bindValue($identifier, $this->billing_cycle_id, PDO::PARAM_INT);
                        break;
                    case 'billing_city':
                        $stmt->bindValue($identifier, $this->billing_city, PDO::PARAM_STR);
                        break;
                    case 'billing_street':
                        $stmt->bindValue($identifier, $this->billing_street, PDO::PARAM_STR);
                        break;
                    case 'billing_country':
                        $stmt->bindValue($identifier, $this->billing_country, PDO::PARAM_STR);
                        break;
                    case 'last_activity_date':
                        $stmt->bindValue($identifier, $this->last_activity_date ? $this->last_activity_date->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'owner_id':
                        $stmt->bindValue($identifier, $this->owner_id, PDO::PARAM_STR);
                        break;
                    case 'account_status_15five_only':
                        $stmt->bindValue($identifier, $this->account_status_15five_only, PDO::PARAM_STR);
                        break;
                    case 'arr_15five_only':
                        $stmt->bindValue($identifier, $this->arr_15five_only, PDO::PARAM_INT);
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
        $pos = AccountHistoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAccountId();
                break;
            case 2:
                return $this->getName();
                break;
            case 3:
                return $this->getNumEmployees();
                break;
            case 4:
                return $this->getWebsite();
                break;
            case 5:
                return $this->getAnnualRevenue();
                break;
            case 6:
                return $this->getIndustry();
                break;
            case 7:
                return $this->getType();
                break;
            case 8:
                return $this->getBillingLatitude();
                break;
            case 9:
                return $this->getBillingLongitude();
                break;
            case 10:
                return $this->getBillingPostalCode();
                break;
            case 11:
                return $this->getBillingState();
                break;
            case 12:
                return $this->getBillingCycleId();
                break;
            case 13:
                return $this->getBillingCity();
                break;
            case 14:
                return $this->getBillingStreet();
                break;
            case 15:
                return $this->getBillingCountry();
                break;
            case 16:
                return $this->getLastActivityDate();
                break;
            case 17:
                return $this->getOwnerId();
                break;
            case 18:
                return $this->getAccountStatus15FiveHack();
                break;
            case 19:
                return $this->getARR15FiveHack();
                break;
            case 20:
                return $this->getCreatedAt();
                break;
            case 21:
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

        if (isset($alreadyDumpedObjects['AccountHistory'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['AccountHistory'][$this->hashCode()] = true;
        $keys = AccountHistoryTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAccountId(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getNumEmployees(),
            $keys[4] => $this->getWebsite(),
            $keys[5] => $this->getAnnualRevenue(),
            $keys[6] => $this->getIndustry(),
            $keys[7] => $this->getType(),
            $keys[8] => $this->getBillingLatitude(),
            $keys[9] => $this->getBillingLongitude(),
            $keys[10] => $this->getBillingPostalCode(),
            $keys[11] => $this->getBillingState(),
            $keys[12] => $this->getBillingCycleId(),
            $keys[13] => $this->getBillingCity(),
            $keys[14] => $this->getBillingStreet(),
            $keys[15] => $this->getBillingCountry(),
            $keys[16] => $this->getLastActivityDate(),
            $keys[17] => $this->getOwnerId(),
            $keys[18] => $this->getAccountStatus15FiveHack(),
            $keys[19] => $this->getARR15FiveHack(),
            $keys[20] => $this->getCreatedAt(),
            $keys[21] => $this->getUpdatedAt(),
        );
        if ($result[$keys[16]] instanceof \DateTime) {
            $result[$keys[16]] = $result[$keys[16]]->format('c');
        }

        if ($result[$keys[20]] instanceof \DateTime) {
            $result[$keys[20]] = $result[$keys[20]]->format('c');
        }

        if ($result[$keys[21]] instanceof \DateTime) {
            $result[$keys[21]] = $result[$keys[21]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aAccount) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'account';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'account';
                        break;
                    default:
                        $key = 'Account';
                }

                $result[$key] = $this->aAccount->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
     * @return $this|\DataModels\DataModels\AccountHistory
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = AccountHistoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\DataModels\DataModels\AccountHistory
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setAccountId($value);
                break;
            case 2:
                $this->setName($value);
                break;
            case 3:
                $this->setNumEmployees($value);
                break;
            case 4:
                $this->setWebsite($value);
                break;
            case 5:
                $this->setAnnualRevenue($value);
                break;
            case 6:
                $this->setIndustry($value);
                break;
            case 7:
                $this->setType($value);
                break;
            case 8:
                $this->setBillingLatitude($value);
                break;
            case 9:
                $this->setBillingLongitude($value);
                break;
            case 10:
                $this->setBillingPostalCode($value);
                break;
            case 11:
                $this->setBillingState($value);
                break;
            case 12:
                $this->setBillingCycleId($value);
                break;
            case 13:
                $this->setBillingCity($value);
                break;
            case 14:
                $this->setBillingStreet($value);
                break;
            case 15:
                $this->setBillingCountry($value);
                break;
            case 16:
                $this->setLastActivityDate($value);
                break;
            case 17:
                $this->setOwnerId($value);
                break;
            case 18:
                $this->setAccountStatus15FiveHack($value);
                break;
            case 19:
                $this->setARR15FiveHack($value);
                break;
            case 20:
                $this->setCreatedAt($value);
                break;
            case 21:
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
        $keys = AccountHistoryTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setAccountId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setName($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setNumEmployees($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setWebsite($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setAnnualRevenue($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setIndustry($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setType($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setBillingLatitude($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setBillingLongitude($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setBillingPostalCode($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setBillingState($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setBillingCycleId($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setBillingCity($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setBillingStreet($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setBillingCountry($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setLastActivityDate($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setOwnerId($arr[$keys[17]]);
        }
        if (array_key_exists($keys[18], $arr)) {
            $this->setAccountStatus15FiveHack($arr[$keys[18]]);
        }
        if (array_key_exists($keys[19], $arr)) {
            $this->setARR15FiveHack($arr[$keys[19]]);
        }
        if (array_key_exists($keys[20], $arr)) {
            $this->setCreatedAt($arr[$keys[20]]);
        }
        if (array_key_exists($keys[21], $arr)) {
            $this->setUpdatedAt($arr[$keys[21]]);
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
     * @return $this|\DataModels\DataModels\AccountHistory The current object, for fluid interface
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
        $criteria = new Criteria(AccountHistoryTableMap::DATABASE_NAME);

        if ($this->isColumnModified(AccountHistoryTableMap::COL_ID)) {
            $criteria->add(AccountHistoryTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_ACCOUNT_ID)) {
            $criteria->add(AccountHistoryTableMap::COL_ACCOUNT_ID, $this->account_id);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_NAME)) {
            $criteria->add(AccountHistoryTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_NUM_EMPLOYEES)) {
            $criteria->add(AccountHistoryTableMap::COL_NUM_EMPLOYEES, $this->num_employees);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_WEBSITE)) {
            $criteria->add(AccountHistoryTableMap::COL_WEBSITE, $this->website);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_ANNUAL_REVENUE)) {
            $criteria->add(AccountHistoryTableMap::COL_ANNUAL_REVENUE, $this->annual_revenue);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_INDUSTRY)) {
            $criteria->add(AccountHistoryTableMap::COL_INDUSTRY, $this->industry);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_TYPE)) {
            $criteria->add(AccountHistoryTableMap::COL_TYPE, $this->type);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_LATITUDE)) {
            $criteria->add(AccountHistoryTableMap::COL_BILLING_LATITUDE, $this->billing_latitude);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_LONGITUDE)) {
            $criteria->add(AccountHistoryTableMap::COL_BILLING_LONGITUDE, $this->billing_longitude);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_POSTAL_CODE)) {
            $criteria->add(AccountHistoryTableMap::COL_BILLING_POSTAL_CODE, $this->billing_postal_code);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_STATE)) {
            $criteria->add(AccountHistoryTableMap::COL_BILLING_STATE, $this->billing_state);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_CYCLE_ID)) {
            $criteria->add(AccountHistoryTableMap::COL_BILLING_CYCLE_ID, $this->billing_cycle_id);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_CITY)) {
            $criteria->add(AccountHistoryTableMap::COL_BILLING_CITY, $this->billing_city);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_STREET)) {
            $criteria->add(AccountHistoryTableMap::COL_BILLING_STREET, $this->billing_street);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_BILLING_COUNTRY)) {
            $criteria->add(AccountHistoryTableMap::COL_BILLING_COUNTRY, $this->billing_country);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_LAST_ACTIVITY_DATE)) {
            $criteria->add(AccountHistoryTableMap::COL_LAST_ACTIVITY_DATE, $this->last_activity_date);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_OWNER_ID)) {
            $criteria->add(AccountHistoryTableMap::COL_OWNER_ID, $this->owner_id);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_ACCOUNT_STATUS_15FIVE_ONLY)) {
            $criteria->add(AccountHistoryTableMap::COL_ACCOUNT_STATUS_15FIVE_ONLY, $this->account_status_15five_only);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_ARR_15FIVE_ONLY)) {
            $criteria->add(AccountHistoryTableMap::COL_ARR_15FIVE_ONLY, $this->arr_15five_only);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_CREATED_AT)) {
            $criteria->add(AccountHistoryTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(AccountHistoryTableMap::COL_UPDATED_AT)) {
            $criteria->add(AccountHistoryTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildAccountHistoryQuery::create();
        $criteria->add(AccountHistoryTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \DataModels\DataModels\AccountHistory (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setAccountId($this->getAccountId());
        $copyObj->setName($this->getName());
        $copyObj->setNumEmployees($this->getNumEmployees());
        $copyObj->setWebsite($this->getWebsite());
        $copyObj->setAnnualRevenue($this->getAnnualRevenue());
        $copyObj->setIndustry($this->getIndustry());
        $copyObj->setType($this->getType());
        $copyObj->setBillingLatitude($this->getBillingLatitude());
        $copyObj->setBillingLongitude($this->getBillingLongitude());
        $copyObj->setBillingPostalCode($this->getBillingPostalCode());
        $copyObj->setBillingState($this->getBillingState());
        $copyObj->setBillingCycleId($this->getBillingCycleId());
        $copyObj->setBillingCity($this->getBillingCity());
        $copyObj->setBillingStreet($this->getBillingStreet());
        $copyObj->setBillingCountry($this->getBillingCountry());
        $copyObj->setLastActivityDate($this->getLastActivityDate());
        $copyObj->setOwnerId($this->getOwnerId());
        $copyObj->setAccountStatus15FiveHack($this->getAccountStatus15FiveHack());
        $copyObj->setARR15FiveHack($this->getARR15FiveHack());
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
     * @return \DataModels\DataModels\AccountHistory Clone of current object.
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
     * Declares an association between this object and a ChildAccount object.
     *
     * @param  ChildAccount $v
     * @return $this|\DataModels\DataModels\AccountHistory The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAccount(ChildAccount $v = null)
    {
        if ($v === null) {
            $this->setAccountId(NULL);
        } else {
            $this->setAccountId($v->getId());
        }

        $this->aAccount = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAccount object, it will not be re-added.
        if ($v !== null) {
            $v->addAccountHistory($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAccount object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildAccount The associated ChildAccount object.
     * @throws PropelException
     */
    public function getAccount(ConnectionInterface $con = null)
    {
        if ($this->aAccount === null && ($this->account_id !== null)) {
            $this->aAccount = ChildAccountQuery::create()->findPk($this->account_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAccount->addAccountHistories($this);
             */
        }

        return $this->aAccount;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aAccount) {
            $this->aAccount->removeAccountHistory($this);
        }
        $this->id = null;
        $this->account_id = null;
        $this->name = null;
        $this->num_employees = null;
        $this->website = null;
        $this->annual_revenue = null;
        $this->industry = null;
        $this->type = null;
        $this->billing_latitude = null;
        $this->billing_longitude = null;
        $this->billing_postal_code = null;
        $this->billing_state = null;
        $this->billing_cycle_id = null;
        $this->billing_city = null;
        $this->billing_street = null;
        $this->billing_country = null;
        $this->last_activity_date = null;
        $this->owner_id = null;
        $this->account_status_15five_only = null;
        $this->arr_15five_only = null;
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

        $this->aAccount = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(AccountHistoryTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildAccountHistory The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[AccountHistoryTableMap::COL_UPDATED_AT] = true;

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
