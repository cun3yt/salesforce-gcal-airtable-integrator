<?php

namespace DataModels\DataModels\Base;

use \DateTime;
use \Exception;
use \PDO;
use DataModels\DataModels\Account as ChildAccount;
use DataModels\DataModels\AccountQuery as ChildAccountQuery;
use DataModels\DataModels\Client as ChildClient;
use DataModels\DataModels\ClientQuery as ChildClientQuery;
use DataModels\DataModels\Contact as ChildContact;
use DataModels\DataModels\ContactHistory as ChildContactHistory;
use DataModels\DataModels\ContactHistoryQuery as ChildContactHistoryQuery;
use DataModels\DataModels\ContactQuery as ChildContactQuery;
use DataModels\DataModels\MeetingAttendee as ChildMeetingAttendee;
use DataModels\DataModels\MeetingAttendeeQuery as ChildMeetingAttendeeQuery;
use DataModels\DataModels\Map\ContactHistoryTableMap;
use DataModels\DataModels\Map\ContactTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'contact' table.
 *
 *
 *
 * @package    propel.generator.DataModels.DataModels.Base
 */
abstract class Contact extends ChildMeetingAttendee implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\DataModels\\DataModels\\Map\\ContactTableMap';


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
     * The value for the email field.
     *
     * @var        string
     */
    protected $email;

    /**
     * The value for the client_id field.
     *
     * @var        int
     */
    protected $client_id;

    /**
     * The value for the account_id field.
     *
     * @var        int
     */
    protected $account_id;

    /**
     * The value for the sfdc_contact_id field.
     *
     * @var        string
     */
    protected $sfdc_contact_id;

    /**
     * The value for the sfdc_account_id field.
     *
     * @var        string
     */
    protected $sfdc_account_id;

    /**
     * The value for the sfdc_contact_name field.
     *
     * @var        string
     */
    protected $sfdc_contact_name;

    /**
     * The value for the sfdc_contact_title field.
     *
     * @var        string
     */
    protected $sfdc_contact_title;

    /**
     * The value for the sfdc_last_check_time field.
     *
     * @var        DateTime
     */
    protected $sfdc_last_check_time;

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

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
     * @var        ChildClient
     */
    protected $aClient;

    /**
     * @var        ChildMeetingAttendee
     */
    protected $aMeetingAttendee;

    /**
     * @var        ObjectCollection|ChildContactHistory[] Collection to store aggregation of ChildContactHistory objects.
     */
    protected $collContactHistories;
    protected $collContactHistoriesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildContactHistory[]
     */
    protected $contactHistoriesScheduledForDeletion = null;

    /**
     * Initializes internal state of DataModels\DataModels\Base\Contact object.
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
     * Compares this with another <code>Contact</code> instance.  If
     * <code>obj</code> is an instance of <code>Contact</code>, delegates to
     * <code>equals(Contact)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Contact The current object, for fluid interface
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
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the [client_id] column value.
     *
     * @return int
     */
    public function getClientId()
    {
        return $this->client_id;
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
     * Get the [sfdc_contact_id] column value.
     *
     * @return string
     */
    public function getSfdcContactId()
    {
        return $this->sfdc_contact_id;
    }

    /**
     * Get the [sfdc_account_id] column value.
     *
     * @return string
     */
    public function getSfdcAccountId()
    {
        return $this->sfdc_account_id;
    }

    /**
     * Get the [sfdc_contact_name] column value.
     *
     * @return string
     */
    public function getSfdcContactName()
    {
        return $this->sfdc_contact_name;
    }

    /**
     * Get the [sfdc_contact_title] column value.
     *
     * @return string
     */
    public function getSfdcTitle()
    {
        return $this->sfdc_contact_title;
    }

    /**
     * Get the [optionally formatted] temporal [sfdc_last_check_time] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getSFDCLastCheckTime($format = NULL)
    {
        if ($format === null) {
            return $this->sfdc_last_check_time;
        } else {
            return $this->sfdc_last_check_time instanceof \DateTimeInterface ? $this->sfdc_last_check_time->format($format) : null;
        }
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
     * Set the value of [email] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[ContactTableMap::COL_EMAIL] = true;
        }

        return $this;
    } // setEmail()

    /**
     * Set the value of [client_id] column.
     *
     * @param int $v new value
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     */
    public function setClientId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->client_id !== $v) {
            $this->client_id = $v;
            $this->modifiedColumns[ContactTableMap::COL_CLIENT_ID] = true;
        }

        if ($this->aClient !== null && $this->aClient->getId() !== $v) {
            $this->aClient = null;
        }

        return $this;
    } // setClientId()

    /**
     * Set the value of [account_id] column.
     *
     * @param int $v new value
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     */
    public function setAccountId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->account_id !== $v) {
            $this->account_id = $v;
            $this->modifiedColumns[ContactTableMap::COL_ACCOUNT_ID] = true;
        }

        if ($this->aAccount !== null && $this->aAccount->getId() !== $v) {
            $this->aAccount = null;
        }

        return $this;
    } // setAccountId()

    /**
     * Set the value of [sfdc_contact_id] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     */
    public function setSfdcContactId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sfdc_contact_id !== $v) {
            $this->sfdc_contact_id = $v;
            $this->modifiedColumns[ContactTableMap::COL_SFDC_CONTACT_ID] = true;
        }

        return $this;
    } // setSfdcContactId()

    /**
     * Set the value of [sfdc_account_id] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     */
    public function setSfdcAccountId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sfdc_account_id !== $v) {
            $this->sfdc_account_id = $v;
            $this->modifiedColumns[ContactTableMap::COL_SFDC_ACCOUNT_ID] = true;
        }

        return $this;
    } // setSfdcAccountId()

    /**
     * Set the value of [sfdc_contact_name] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     */
    public function setSfdcContactName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sfdc_contact_name !== $v) {
            $this->sfdc_contact_name = $v;
            $this->modifiedColumns[ContactTableMap::COL_SFDC_CONTACT_NAME] = true;
        }

        return $this;
    } // setSfdcContactName()

    /**
     * Set the value of [sfdc_contact_title] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     */
    public function setSfdcTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sfdc_contact_title !== $v) {
            $this->sfdc_contact_title = $v;
            $this->modifiedColumns[ContactTableMap::COL_SFDC_CONTACT_TITLE] = true;
        }

        return $this;
    } // setSfdcTitle()

    /**
     * Sets the value of [sfdc_last_check_time] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     */
    public function setSFDCLastCheckTime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->sfdc_last_check_time !== null || $dt !== null) {
            if ($this->sfdc_last_check_time === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->sfdc_last_check_time->format("Y-m-d H:i:s.u")) {
                $this->sfdc_last_check_time = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ContactTableMap::COL_SFDC_LAST_CHECK_TIME] = true;
            }
        } // if either are not null

        return $this;
    } // setSFDCLastCheckTime()

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[ContactTableMap::COL_ID] = true;
        }

        if ($this->aMeetingAttendee !== null && $this->aMeetingAttendee->getId() !== $v) {
            $this->aMeetingAttendee = null;
        }

        return $this;
    } // setId()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ContactTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[ContactTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : ContactTableMap::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : ContactTableMap::translateFieldName('ClientId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->client_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : ContactTableMap::translateFieldName('AccountId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->account_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : ContactTableMap::translateFieldName('SfdcContactId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sfdc_contact_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : ContactTableMap::translateFieldName('SfdcAccountId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sfdc_account_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : ContactTableMap::translateFieldName('SfdcContactName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sfdc_contact_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : ContactTableMap::translateFieldName('SfdcTitle', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sfdc_contact_title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : ContactTableMap::translateFieldName('SFDCLastCheckTime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sfdc_last_check_time = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : ContactTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : ContactTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : ContactTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 11; // 11 = ContactTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\DataModels\\DataModels\\Contact'), 0, $e);
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
        if ($this->aClient !== null && $this->client_id !== $this->aClient->getId()) {
            $this->aClient = null;
        }
        if ($this->aAccount !== null && $this->account_id !== $this->aAccount->getId()) {
            $this->aAccount = null;
        }
        if ($this->aMeetingAttendee !== null && $this->id !== $this->aMeetingAttendee->getId()) {
            $this->aMeetingAttendee = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(ContactTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildContactQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAccount = null;
            $this->aClient = null;
            $this->aMeetingAttendee = null;
            $this->collContactHistories = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Contact::setDeleted()
     * @see Contact::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(ContactTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildContactQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // concrete_inheritance behavior
                $this->getParentOrCreate($con)->delete($con);

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
            $con = Propel::getServiceContainer()->getWriteConnection(ContactTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            // concrete_inheritance behavior
            $parent = $this->getSyncParent($con);
            $parent->save($con);
            $this->setPrimaryKey($parent->getPrimaryKey());

            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(ContactTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
                if (!$this->isColumnModified(ContactTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(ContactTableMap::COL_UPDATED_AT)) {
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
                ContactTableMap::addInstanceToPool($this);
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

            if ($this->aClient !== null) {
                if ($this->aClient->isModified() || $this->aClient->isNew()) {
                    $affectedRows += $this->aClient->save($con);
                }
                $this->setClient($this->aClient);
            }

            if ($this->aMeetingAttendee !== null) {
                if ($this->aMeetingAttendee->isModified() || $this->aMeetingAttendee->isNew()) {
                    $affectedRows += $this->aMeetingAttendee->save($con);
                }
                $this->setMeetingAttendee($this->aMeetingAttendee);
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

            if ($this->contactHistoriesScheduledForDeletion !== null) {
                if (!$this->contactHistoriesScheduledForDeletion->isEmpty()) {
                    foreach ($this->contactHistoriesScheduledForDeletion as $contactHistory) {
                        // need to save related object because we set the relation to null
                        $contactHistory->save($con);
                    }
                    $this->contactHistoriesScheduledForDeletion = null;
                }
            }

            if ($this->collContactHistories !== null) {
                foreach ($this->collContactHistories as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ContactTableMap::COL_EMAIL)) {
            $modifiedColumns[':p' . $index++]  = 'email';
        }
        if ($this->isColumnModified(ContactTableMap::COL_CLIENT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'client_id';
        }
        if ($this->isColumnModified(ContactTableMap::COL_ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'account_id';
        }
        if ($this->isColumnModified(ContactTableMap::COL_SFDC_CONTACT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'sfdc_contact_id';
        }
        if ($this->isColumnModified(ContactTableMap::COL_SFDC_ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'sfdc_account_id';
        }
        if ($this->isColumnModified(ContactTableMap::COL_SFDC_CONTACT_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'sfdc_contact_name';
        }
        if ($this->isColumnModified(ContactTableMap::COL_SFDC_CONTACT_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'sfdc_contact_title';
        }
        if ($this->isColumnModified(ContactTableMap::COL_SFDC_LAST_CHECK_TIME)) {
            $modifiedColumns[':p' . $index++]  = 'sfdc_last_check_time';
        }
        if ($this->isColumnModified(ContactTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(ContactTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(ContactTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO contact (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'email':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
                        break;
                    case 'client_id':
                        $stmt->bindValue($identifier, $this->client_id, PDO::PARAM_INT);
                        break;
                    case 'account_id':
                        $stmt->bindValue($identifier, $this->account_id, PDO::PARAM_INT);
                        break;
                    case 'sfdc_contact_id':
                        $stmt->bindValue($identifier, $this->sfdc_contact_id, PDO::PARAM_STR);
                        break;
                    case 'sfdc_account_id':
                        $stmt->bindValue($identifier, $this->sfdc_account_id, PDO::PARAM_STR);
                        break;
                    case 'sfdc_contact_name':
                        $stmt->bindValue($identifier, $this->sfdc_contact_name, PDO::PARAM_STR);
                        break;
                    case 'sfdc_contact_title':
                        $stmt->bindValue($identifier, $this->sfdc_contact_title, PDO::PARAM_STR);
                        break;
                    case 'sfdc_last_check_time':
                        $stmt->bindValue($identifier, $this->sfdc_last_check_time ? $this->sfdc_last_check_time->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
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
        $pos = ContactTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEmail();
                break;
            case 1:
                return $this->getClientId();
                break;
            case 2:
                return $this->getAccountId();
                break;
            case 3:
                return $this->getSfdcContactId();
                break;
            case 4:
                return $this->getSfdcAccountId();
                break;
            case 5:
                return $this->getSfdcContactName();
                break;
            case 6:
                return $this->getSfdcTitle();
                break;
            case 7:
                return $this->getSFDCLastCheckTime();
                break;
            case 8:
                return $this->getId();
                break;
            case 9:
                return $this->getCreatedAt();
                break;
            case 10:
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

        if (isset($alreadyDumpedObjects['Contact'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Contact'][$this->hashCode()] = true;
        $keys = ContactTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getEmail(),
            $keys[1] => $this->getClientId(),
            $keys[2] => $this->getAccountId(),
            $keys[3] => $this->getSfdcContactId(),
            $keys[4] => $this->getSfdcAccountId(),
            $keys[5] => $this->getSfdcContactName(),
            $keys[6] => $this->getSfdcTitle(),
            $keys[7] => $this->getSFDCLastCheckTime(),
            $keys[8] => $this->getId(),
            $keys[9] => $this->getCreatedAt(),
            $keys[10] => $this->getUpdatedAt(),
        );
        if ($result[$keys[7]] instanceof \DateTime) {
            $result[$keys[7]] = $result[$keys[7]]->format('c');
        }

        if ($result[$keys[9]] instanceof \DateTime) {
            $result[$keys[9]] = $result[$keys[9]]->format('c');
        }

        if ($result[$keys[10]] instanceof \DateTime) {
            $result[$keys[10]] = $result[$keys[10]]->format('c');
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
            if (null !== $this->aClient) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'client';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'client';
                        break;
                    default:
                        $key = 'Client';
                }

                $result[$key] = $this->aClient->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aMeetingAttendee) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'meetingAttendee';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'meeting_attendee';
                        break;
                    default:
                        $key = 'MeetingAttendee';
                }

                $result[$key] = $this->aMeetingAttendee->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collContactHistories) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'contactHistories';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'contact_histories';
                        break;
                    default:
                        $key = 'ContactHistories';
                }

                $result[$key] = $this->collContactHistories->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\DataModels\DataModels\Contact
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = ContactTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\DataModels\DataModels\Contact
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setEmail($value);
                break;
            case 1:
                $this->setClientId($value);
                break;
            case 2:
                $this->setAccountId($value);
                break;
            case 3:
                $this->setSfdcContactId($value);
                break;
            case 4:
                $this->setSfdcAccountId($value);
                break;
            case 5:
                $this->setSfdcContactName($value);
                break;
            case 6:
                $this->setSfdcTitle($value);
                break;
            case 7:
                $this->setSFDCLastCheckTime($value);
                break;
            case 8:
                $this->setId($value);
                break;
            case 9:
                $this->setCreatedAt($value);
                break;
            case 10:
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
        $keys = ContactTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setEmail($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setClientId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setAccountId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setSfdcContactId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setSfdcAccountId($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setSfdcContactName($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setSfdcTitle($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setSFDCLastCheckTime($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setId($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setCreatedAt($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setUpdatedAt($arr[$keys[10]]);
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
     * @return $this|\DataModels\DataModels\Contact The current object, for fluid interface
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
        $criteria = new Criteria(ContactTableMap::DATABASE_NAME);

        if ($this->isColumnModified(ContactTableMap::COL_EMAIL)) {
            $criteria->add(ContactTableMap::COL_EMAIL, $this->email);
        }
        if ($this->isColumnModified(ContactTableMap::COL_CLIENT_ID)) {
            $criteria->add(ContactTableMap::COL_CLIENT_ID, $this->client_id);
        }
        if ($this->isColumnModified(ContactTableMap::COL_ACCOUNT_ID)) {
            $criteria->add(ContactTableMap::COL_ACCOUNT_ID, $this->account_id);
        }
        if ($this->isColumnModified(ContactTableMap::COL_SFDC_CONTACT_ID)) {
            $criteria->add(ContactTableMap::COL_SFDC_CONTACT_ID, $this->sfdc_contact_id);
        }
        if ($this->isColumnModified(ContactTableMap::COL_SFDC_ACCOUNT_ID)) {
            $criteria->add(ContactTableMap::COL_SFDC_ACCOUNT_ID, $this->sfdc_account_id);
        }
        if ($this->isColumnModified(ContactTableMap::COL_SFDC_CONTACT_NAME)) {
            $criteria->add(ContactTableMap::COL_SFDC_CONTACT_NAME, $this->sfdc_contact_name);
        }
        if ($this->isColumnModified(ContactTableMap::COL_SFDC_CONTACT_TITLE)) {
            $criteria->add(ContactTableMap::COL_SFDC_CONTACT_TITLE, $this->sfdc_contact_title);
        }
        if ($this->isColumnModified(ContactTableMap::COL_SFDC_LAST_CHECK_TIME)) {
            $criteria->add(ContactTableMap::COL_SFDC_LAST_CHECK_TIME, $this->sfdc_last_check_time);
        }
        if ($this->isColumnModified(ContactTableMap::COL_ID)) {
            $criteria->add(ContactTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(ContactTableMap::COL_CREATED_AT)) {
            $criteria->add(ContactTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(ContactTableMap::COL_UPDATED_AT)) {
            $criteria->add(ContactTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildContactQuery::create();
        $criteria->add(ContactTableMap::COL_ID, $this->id);

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

        $validPrimaryKeyFKs = 1;
        $primaryKeyFKs = [];

        //relation contact_fk_5860de to table meeting_attendee
        if ($this->aMeetingAttendee && $hash = spl_object_hash($this->aMeetingAttendee)) {
            $primaryKeyFKs[] = $hash;
        } else {
            $validPrimaryKeyFKs = false;
        }

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
     * @param      object $copyObj An object of \DataModels\DataModels\Contact (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEmail($this->getEmail());
        $copyObj->setClientId($this->getClientId());
        $copyObj->setAccountId($this->getAccountId());
        $copyObj->setSfdcContactId($this->getSfdcContactId());
        $copyObj->setSfdcAccountId($this->getSfdcAccountId());
        $copyObj->setSfdcContactName($this->getSfdcContactName());
        $copyObj->setSfdcTitle($this->getSfdcTitle());
        $copyObj->setSFDCLastCheckTime($this->getSFDCLastCheckTime());
        $copyObj->setId($this->getId());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getContactHistories() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addContactHistory($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
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
     * @return \DataModels\DataModels\Contact Clone of current object.
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
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
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
            $v->addContact($this);
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
                $this->aAccount->addContacts($this);
             */
        }

        return $this->aAccount;
    }

    /**
     * Declares an association between this object and a ChildClient object.
     *
     * @param  ChildClient $v
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     * @throws PropelException
     */
    public function setClient(ChildClient $v = null)
    {
        if ($v === null) {
            $this->setClientId(NULL);
        } else {
            $this->setClientId($v->getId());
        }

        $this->aClient = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildClient object, it will not be re-added.
        if ($v !== null) {
            $v->addContact($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildClient object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildClient The associated ChildClient object.
     * @throws PropelException
     */
    public function getClient(ConnectionInterface $con = null)
    {
        if ($this->aClient === null && ($this->client_id !== null)) {
            $this->aClient = ChildClientQuery::create()->findPk($this->client_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aClient->addContacts($this);
             */
        }

        return $this->aClient;
    }

    /**
     * Declares an association between this object and a ChildMeetingAttendee object.
     *
     * @param  ChildMeetingAttendee $v
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMeetingAttendee(ChildMeetingAttendee $v = null)
    {
        if ($v === null) {
            $this->setId(NULL);
        } else {
            $this->setId($v->getId());
        }

        $this->aMeetingAttendee = $v;

        // Add binding for other direction of this 1:1 relationship.
        if ($v !== null) {
            $v->setContact($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildMeetingAttendee object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildMeetingAttendee The associated ChildMeetingAttendee object.
     * @throws PropelException
     */
    public function getMeetingAttendee(ConnectionInterface $con = null)
    {
        if ($this->aMeetingAttendee === null && ($this->id !== null)) {
            $this->aMeetingAttendee = ChildMeetingAttendeeQuery::create()->findPk($this->id, $con);
            // Because this foreign key represents a one-to-one relationship, we will create a bi-directional association.
            $this->aMeetingAttendee->setContact($this);
        }

        return $this->aMeetingAttendee;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ContactHistory' == $relationName) {
            return $this->initContactHistories();
        }
    }

    /**
     * Clears out the collContactHistories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addContactHistories()
     */
    public function clearContactHistories()
    {
        $this->collContactHistories = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collContactHistories collection loaded partially.
     */
    public function resetPartialContactHistories($v = true)
    {
        $this->collContactHistoriesPartial = $v;
    }

    /**
     * Initializes the collContactHistories collection.
     *
     * By default this just sets the collContactHistories collection to an empty array (like clearcollContactHistories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initContactHistories($overrideExisting = true)
    {
        if (null !== $this->collContactHistories && !$overrideExisting) {
            return;
        }

        $collectionClassName = ContactHistoryTableMap::getTableMap()->getCollectionClassName();

        $this->collContactHistories = new $collectionClassName;
        $this->collContactHistories->setModel('\DataModels\DataModels\ContactHistory');
    }

    /**
     * Gets an array of ChildContactHistory objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildContact is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildContactHistory[] List of ChildContactHistory objects
     * @throws PropelException
     */
    public function getContactHistories(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collContactHistoriesPartial && !$this->isNew();
        if (null === $this->collContactHistories || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collContactHistories) {
                // return empty collection
                $this->initContactHistories();
            } else {
                $collContactHistories = ChildContactHistoryQuery::create(null, $criteria)
                    ->filterByContact($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collContactHistoriesPartial && count($collContactHistories)) {
                        $this->initContactHistories(false);

                        foreach ($collContactHistories as $obj) {
                            if (false == $this->collContactHistories->contains($obj)) {
                                $this->collContactHistories->append($obj);
                            }
                        }

                        $this->collContactHistoriesPartial = true;
                    }

                    return $collContactHistories;
                }

                if ($partial && $this->collContactHistories) {
                    foreach ($this->collContactHistories as $obj) {
                        if ($obj->isNew()) {
                            $collContactHistories[] = $obj;
                        }
                    }
                }

                $this->collContactHistories = $collContactHistories;
                $this->collContactHistoriesPartial = false;
            }
        }

        return $this->collContactHistories;
    }

    /**
     * Sets a collection of ChildContactHistory objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $contactHistories A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildContact The current object (for fluent API support)
     */
    public function setContactHistories(Collection $contactHistories, ConnectionInterface $con = null)
    {
        /** @var ChildContactHistory[] $contactHistoriesToDelete */
        $contactHistoriesToDelete = $this->getContactHistories(new Criteria(), $con)->diff($contactHistories);


        $this->contactHistoriesScheduledForDeletion = $contactHistoriesToDelete;

        foreach ($contactHistoriesToDelete as $contactHistoryRemoved) {
            $contactHistoryRemoved->setContact(null);
        }

        $this->collContactHistories = null;
        foreach ($contactHistories as $contactHistory) {
            $this->addContactHistory($contactHistory);
        }

        $this->collContactHistories = $contactHistories;
        $this->collContactHistoriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ContactHistory objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related ContactHistory objects.
     * @throws PropelException
     */
    public function countContactHistories(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collContactHistoriesPartial && !$this->isNew();
        if (null === $this->collContactHistories || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collContactHistories) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getContactHistories());
            }

            $query = ChildContactHistoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByContact($this)
                ->count($con);
        }

        return count($this->collContactHistories);
    }

    /**
     * Method called to associate a ChildContactHistory object to this object
     * through the ChildContactHistory foreign key attribute.
     *
     * @param  ChildContactHistory $l ChildContactHistory
     * @return $this|\DataModels\DataModels\Contact The current object (for fluent API support)
     */
    public function addContactHistory(ChildContactHistory $l)
    {
        if ($this->collContactHistories === null) {
            $this->initContactHistories();
            $this->collContactHistoriesPartial = true;
        }

        if (!$this->collContactHistories->contains($l)) {
            $this->doAddContactHistory($l);

            if ($this->contactHistoriesScheduledForDeletion and $this->contactHistoriesScheduledForDeletion->contains($l)) {
                $this->contactHistoriesScheduledForDeletion->remove($this->contactHistoriesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildContactHistory $contactHistory The ChildContactHistory object to add.
     */
    protected function doAddContactHistory(ChildContactHistory $contactHistory)
    {
        $this->collContactHistories[]= $contactHistory;
        $contactHistory->setContact($this);
    }

    /**
     * @param  ChildContactHistory $contactHistory The ChildContactHistory object to remove.
     * @return $this|ChildContact The current object (for fluent API support)
     */
    public function removeContactHistory(ChildContactHistory $contactHistory)
    {
        if ($this->getContactHistories()->contains($contactHistory)) {
            $pos = $this->collContactHistories->search($contactHistory);
            $this->collContactHistories->remove($pos);
            if (null === $this->contactHistoriesScheduledForDeletion) {
                $this->contactHistoriesScheduledForDeletion = clone $this->collContactHistories;
                $this->contactHistoriesScheduledForDeletion->clear();
            }
            $this->contactHistoriesScheduledForDeletion[]= $contactHistory;
            $contactHistory->setContact(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aAccount) {
            $this->aAccount->removeContact($this);
        }
        if (null !== $this->aClient) {
            $this->aClient->removeContact($this);
        }
        if (null !== $this->aMeetingAttendee) {
            $this->aMeetingAttendee->removeContact($this);
        }
        $this->email = null;
        $this->client_id = null;
        $this->account_id = null;
        $this->sfdc_contact_id = null;
        $this->sfdc_account_id = null;
        $this->sfdc_contact_name = null;
        $this->sfdc_contact_title = null;
        $this->sfdc_last_check_time = null;
        $this->id = null;
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
            if ($this->collContactHistories) {
                foreach ($this->collContactHistories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collContactHistories = null;
        $this->aAccount = null;
        $this->aClient = null;
        $this->aMeetingAttendee = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ContactTableMap::DEFAULT_STRING_FORMAT);
    }

    // concrete_inheritance behavior

    /**
     * Get or Create the parent ChildMeetingAttendee object of the current object
     *
     * @return    ChildMeetingAttendee The parent object
     */
    public function getParentOrCreate($con = null)
    {
        if ($this->isNew()) {
            if ($this->isPrimaryKeyNull()) {
                $parent = new ChildMeetingAttendee();
                $parent->setDescendantClass('DataModels\DataModels\Contact');

                return $parent;
            } else {
                $parent = \DataModels\DataModels\MeetingAttendeeQuery::create()->findPk($this->getPrimaryKey(), $con);
                if (null === $parent || null !== $parent->getDescendantClass()) {
                    $parent = new ChildMeetingAttendee();
                    $parent->setPrimaryKey($this->getPrimaryKey());
                    $parent->setDescendantClass('DataModels\DataModels\Contact');
                }

                return $parent;
            }
        } else {
            return ChildMeetingAttendeeQuery::create()->findPk($this->getPrimaryKey(), $con);
        }
    }

    /**
     * Create or Update the parent MeetingAttendee object
     * And return its primary key
     *
     * @return    int The primary key of the parent object
     */
    public function getSyncParent($con = null)
    {
        $parent = $this->getParentOrCreate($con);
        $parent->setCreatedAt($this->getCreatedAt());
        $parent->setUpdatedAt($this->getUpdatedAt());

        return $parent;
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildContact The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[ContactTableMap::COL_UPDATED_AT] = true;

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
