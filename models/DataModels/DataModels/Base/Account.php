<?php

namespace DataModels\DataModels\Base;

use \DateTime;
use \Exception;
use \PDO;
use DataModels\DataModels\Account as ChildAccount;
use DataModels\DataModels\AccountHistory as ChildAccountHistory;
use DataModels\DataModels\AccountHistoryQuery as ChildAccountHistoryQuery;
use DataModels\DataModels\AccountQuery as ChildAccountQuery;
use DataModels\DataModels\Client as ChildClient;
use DataModels\DataModels\ClientQuery as ChildClientQuery;
use DataModels\DataModels\Contact as ChildContact;
use DataModels\DataModels\ContactQuery as ChildContactQuery;
use DataModels\DataModels\Map\AccountHistoryTableMap;
use DataModels\DataModels\Map\AccountTableMap;
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
 * Base class that represents a row from the 'account' table.
 *
 *
 *
 * @package    propel.generator.DataModels.DataModels.Base
 */
abstract class Account implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\DataModels\\DataModels\\Map\\AccountTableMap';


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
     * The value for the email_domain field.
     *
     * @var        string
     */
    protected $email_domain;

    /**
     * The value for the sfdc_account_id field.
     *
     * @var        string
     */
    protected $sfdc_account_id;

    /**
     * The value for the client_id field.
     *
     * @var        int
     */
    protected $client_id;

    /**
     * The value for the sfdc_last_check_time field.
     *
     * @var        DateTime
     */
    protected $sfdc_last_check_time;

    /**
     * The value for the sfdc_oppty_last_check_time field.
     *
     * @var        DateTime
     */
    protected $sfdc_oppty_last_check_time;

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
     * @var        ChildClient
     */
    protected $aClient;

    /**
     * @var        ObjectCollection|ChildAccountHistory[] Collection to store aggregation of ChildAccountHistory objects.
     */
    protected $collAccountHistories;
    protected $collAccountHistoriesPartial;

    /**
     * @var        ObjectCollection|ChildContact[] Collection to store aggregation of ChildContact objects.
     */
    protected $collContacts;
    protected $collContactsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildAccountHistory[]
     */
    protected $accountHistoriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildContact[]
     */
    protected $contactsScheduledForDeletion = null;

    /**
     * Initializes internal state of DataModels\DataModels\Base\Account object.
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
     * Compares this with another <code>Account</code> instance.  If
     * <code>obj</code> is an instance of <code>Account</code>, delegates to
     * <code>equals(Account)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Account The current object, for fluid interface
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
     * Get the [email_domain] column value.
     *
     * @return string
     */
    public function getEmailDomain()
    {
        return $this->email_domain;
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
     * Get the [client_id] column value.
     *
     * @return int
     */
    public function getClientId()
    {
        return $this->client_id;
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
     * Get the [optionally formatted] temporal [sfdc_oppty_last_check_time] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getSFDCOpptyLastCheckTime($format = NULL)
    {
        if ($format === null) {
            return $this->sfdc_oppty_last_check_time;
        } else {
            return $this->sfdc_oppty_last_check_time instanceof \DateTimeInterface ? $this->sfdc_oppty_last_check_time->format($format) : null;
        }
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
     * @return $this|\DataModels\DataModels\Account The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[AccountTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [email_domain] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Account The current object (for fluent API support)
     */
    public function setEmailDomain($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email_domain !== $v) {
            $this->email_domain = $v;
            $this->modifiedColumns[AccountTableMap::COL_EMAIL_DOMAIN] = true;
        }

        return $this;
    } // setEmailDomain()

    /**
     * Set the value of [sfdc_account_id] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Account The current object (for fluent API support)
     */
    public function setSfdcAccountId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->sfdc_account_id !== $v) {
            $this->sfdc_account_id = $v;
            $this->modifiedColumns[AccountTableMap::COL_SFDC_ACCOUNT_ID] = true;
        }

        return $this;
    } // setSfdcAccountId()

    /**
     * Set the value of [client_id] column.
     *
     * @param int $v new value
     * @return $this|\DataModels\DataModels\Account The current object (for fluent API support)
     */
    public function setClientId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->client_id !== $v) {
            $this->client_id = $v;
            $this->modifiedColumns[AccountTableMap::COL_CLIENT_ID] = true;
        }

        if ($this->aClient !== null && $this->aClient->getId() !== $v) {
            $this->aClient = null;
        }

        return $this;
    } // setClientId()

    /**
     * Sets the value of [sfdc_last_check_time] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\Account The current object (for fluent API support)
     */
    public function setSFDCLastCheckTime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->sfdc_last_check_time !== null || $dt !== null) {
            if ($this->sfdc_last_check_time === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->sfdc_last_check_time->format("Y-m-d H:i:s.u")) {
                $this->sfdc_last_check_time = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AccountTableMap::COL_SFDC_LAST_CHECK_TIME] = true;
            }
        } // if either are not null

        return $this;
    } // setSFDCLastCheckTime()

    /**
     * Sets the value of [sfdc_oppty_last_check_time] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\Account The current object (for fluent API support)
     */
    public function setSFDCOpptyLastCheckTime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->sfdc_oppty_last_check_time !== null || $dt !== null) {
            if ($this->sfdc_oppty_last_check_time === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->sfdc_oppty_last_check_time->format("Y-m-d H:i:s.u")) {
                $this->sfdc_oppty_last_check_time = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AccountTableMap::COL_SFDC_OPPTY_LAST_CHECK_TIME] = true;
            }
        } // if either are not null

        return $this;
    } // setSFDCOpptyLastCheckTime()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\Account The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AccountTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\Account The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[AccountTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : AccountTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : AccountTableMap::translateFieldName('EmailDomain', TableMap::TYPE_PHPNAME, $indexType)];
            $this->email_domain = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : AccountTableMap::translateFieldName('SfdcAccountId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sfdc_account_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : AccountTableMap::translateFieldName('ClientId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->client_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : AccountTableMap::translateFieldName('SFDCLastCheckTime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sfdc_last_check_time = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : AccountTableMap::translateFieldName('SFDCOpptyLastCheckTime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->sfdc_oppty_last_check_time = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : AccountTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : AccountTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 8; // 8 = AccountTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\DataModels\\DataModels\\Account'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(AccountTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildAccountQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aClient = null;
            $this->collAccountHistories = null;

            $this->collContacts = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Account::setDeleted()
     * @see Account::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(AccountTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildAccountQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(AccountTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(AccountTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
                if (!$this->isColumnModified(AccountTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(AccountTableMap::COL_UPDATED_AT)) {
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
                AccountTableMap::addInstanceToPool($this);
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

            if ($this->aClient !== null) {
                if ($this->aClient->isModified() || $this->aClient->isNew()) {
                    $affectedRows += $this->aClient->save($con);
                }
                $this->setClient($this->aClient);
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

            if ($this->accountHistoriesScheduledForDeletion !== null) {
                if (!$this->accountHistoriesScheduledForDeletion->isEmpty()) {
                    foreach ($this->accountHistoriesScheduledForDeletion as $accountHistory) {
                        // need to save related object because we set the relation to null
                        $accountHistory->save($con);
                    }
                    $this->accountHistoriesScheduledForDeletion = null;
                }
            }

            if ($this->collAccountHistories !== null) {
                foreach ($this->collAccountHistories as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->contactsScheduledForDeletion !== null) {
                if (!$this->contactsScheduledForDeletion->isEmpty()) {
                    foreach ($this->contactsScheduledForDeletion as $contact) {
                        // need to save related object because we set the relation to null
                        $contact->save($con);
                    }
                    $this->contactsScheduledForDeletion = null;
                }
            }

            if ($this->collContacts !== null) {
                foreach ($this->collContacts as $referrerFK) {
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

        $this->modifiedColumns[AccountTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . AccountTableMap::COL_ID . ')');
        }
        if (null === $this->id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('account_id_seq')");
                $this->id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AccountTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(AccountTableMap::COL_EMAIL_DOMAIN)) {
            $modifiedColumns[':p' . $index++]  = 'email_domain';
        }
        if ($this->isColumnModified(AccountTableMap::COL_SFDC_ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'sfdc_account_id';
        }
        if ($this->isColumnModified(AccountTableMap::COL_CLIENT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'client_id';
        }
        if ($this->isColumnModified(AccountTableMap::COL_SFDC_LAST_CHECK_TIME)) {
            $modifiedColumns[':p' . $index++]  = 'sfdc_last_check_time';
        }
        if ($this->isColumnModified(AccountTableMap::COL_SFDC_OPPTY_LAST_CHECK_TIME)) {
            $modifiedColumns[':p' . $index++]  = 'sfdc_oppty_last_check_time';
        }
        if ($this->isColumnModified(AccountTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(AccountTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO account (%s) VALUES (%s)',
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
                    case 'email_domain':
                        $stmt->bindValue($identifier, $this->email_domain, PDO::PARAM_STR);
                        break;
                    case 'sfdc_account_id':
                        $stmt->bindValue($identifier, $this->sfdc_account_id, PDO::PARAM_STR);
                        break;
                    case 'client_id':
                        $stmt->bindValue($identifier, $this->client_id, PDO::PARAM_INT);
                        break;
                    case 'sfdc_last_check_time':
                        $stmt->bindValue($identifier, $this->sfdc_last_check_time ? $this->sfdc_last_check_time->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'sfdc_oppty_last_check_time':
                        $stmt->bindValue($identifier, $this->sfdc_oppty_last_check_time ? $this->sfdc_oppty_last_check_time->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
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
        $pos = AccountTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEmailDomain();
                break;
            case 2:
                return $this->getSfdcAccountId();
                break;
            case 3:
                return $this->getClientId();
                break;
            case 4:
                return $this->getSFDCLastCheckTime();
                break;
            case 5:
                return $this->getSFDCOpptyLastCheckTime();
                break;
            case 6:
                return $this->getCreatedAt();
                break;
            case 7:
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

        if (isset($alreadyDumpedObjects['Account'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Account'][$this->hashCode()] = true;
        $keys = AccountTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getEmailDomain(),
            $keys[2] => $this->getSfdcAccountId(),
            $keys[3] => $this->getClientId(),
            $keys[4] => $this->getSFDCLastCheckTime(),
            $keys[5] => $this->getSFDCOpptyLastCheckTime(),
            $keys[6] => $this->getCreatedAt(),
            $keys[7] => $this->getUpdatedAt(),
        );
        if ($result[$keys[4]] instanceof \DateTime) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        if ($result[$keys[5]] instanceof \DateTime) {
            $result[$keys[5]] = $result[$keys[5]]->format('c');
        }

        if ($result[$keys[6]] instanceof \DateTime) {
            $result[$keys[6]] = $result[$keys[6]]->format('c');
        }

        if ($result[$keys[7]] instanceof \DateTime) {
            $result[$keys[7]] = $result[$keys[7]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
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
            if (null !== $this->collAccountHistories) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'accountHistories';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'account_histories';
                        break;
                    default:
                        $key = 'AccountHistories';
                }

                $result[$key] = $this->collAccountHistories->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collContacts) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'contacts';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'contacts';
                        break;
                    default:
                        $key = 'Contacts';
                }

                $result[$key] = $this->collContacts->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\DataModels\DataModels\Account
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = AccountTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\DataModels\DataModels\Account
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setEmailDomain($value);
                break;
            case 2:
                $this->setSfdcAccountId($value);
                break;
            case 3:
                $this->setClientId($value);
                break;
            case 4:
                $this->setSFDCLastCheckTime($value);
                break;
            case 5:
                $this->setSFDCOpptyLastCheckTime($value);
                break;
            case 6:
                $this->setCreatedAt($value);
                break;
            case 7:
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
        $keys = AccountTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEmailDomain($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setSfdcAccountId($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setClientId($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setSFDCLastCheckTime($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setSFDCOpptyLastCheckTime($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCreatedAt($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setUpdatedAt($arr[$keys[7]]);
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
     * @return $this|\DataModels\DataModels\Account The current object, for fluid interface
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
        $criteria = new Criteria(AccountTableMap::DATABASE_NAME);

        if ($this->isColumnModified(AccountTableMap::COL_ID)) {
            $criteria->add(AccountTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(AccountTableMap::COL_EMAIL_DOMAIN)) {
            $criteria->add(AccountTableMap::COL_EMAIL_DOMAIN, $this->email_domain);
        }
        if ($this->isColumnModified(AccountTableMap::COL_SFDC_ACCOUNT_ID)) {
            $criteria->add(AccountTableMap::COL_SFDC_ACCOUNT_ID, $this->sfdc_account_id);
        }
        if ($this->isColumnModified(AccountTableMap::COL_CLIENT_ID)) {
            $criteria->add(AccountTableMap::COL_CLIENT_ID, $this->client_id);
        }
        if ($this->isColumnModified(AccountTableMap::COL_SFDC_LAST_CHECK_TIME)) {
            $criteria->add(AccountTableMap::COL_SFDC_LAST_CHECK_TIME, $this->sfdc_last_check_time);
        }
        if ($this->isColumnModified(AccountTableMap::COL_SFDC_OPPTY_LAST_CHECK_TIME)) {
            $criteria->add(AccountTableMap::COL_SFDC_OPPTY_LAST_CHECK_TIME, $this->sfdc_oppty_last_check_time);
        }
        if ($this->isColumnModified(AccountTableMap::COL_CREATED_AT)) {
            $criteria->add(AccountTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(AccountTableMap::COL_UPDATED_AT)) {
            $criteria->add(AccountTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildAccountQuery::create();
        $criteria->add(AccountTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \DataModels\DataModels\Account (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEmailDomain($this->getEmailDomain());
        $copyObj->setSfdcAccountId($this->getSfdcAccountId());
        $copyObj->setClientId($this->getClientId());
        $copyObj->setSFDCLastCheckTime($this->getSFDCLastCheckTime());
        $copyObj->setSFDCOpptyLastCheckTime($this->getSFDCOpptyLastCheckTime());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getAccountHistories() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAccountHistory($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getContacts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addContact($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

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
     * @return \DataModels\DataModels\Account Clone of current object.
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
     * Declares an association between this object and a ChildClient object.
     *
     * @param  ChildClient $v
     * @return $this|\DataModels\DataModels\Account The current object (for fluent API support)
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
            $v->addAccount($this);
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
                $this->aClient->addAccounts($this);
             */
        }

        return $this->aClient;
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
        if ('AccountHistory' == $relationName) {
            return $this->initAccountHistories();
        }
        if ('Contact' == $relationName) {
            return $this->initContacts();
        }
    }

    /**
     * Clears out the collAccountHistories collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addAccountHistories()
     */
    public function clearAccountHistories()
    {
        $this->collAccountHistories = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collAccountHistories collection loaded partially.
     */
    public function resetPartialAccountHistories($v = true)
    {
        $this->collAccountHistoriesPartial = $v;
    }

    /**
     * Initializes the collAccountHistories collection.
     *
     * By default this just sets the collAccountHistories collection to an empty array (like clearcollAccountHistories());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAccountHistories($overrideExisting = true)
    {
        if (null !== $this->collAccountHistories && !$overrideExisting) {
            return;
        }

        $collectionClassName = AccountHistoryTableMap::getTableMap()->getCollectionClassName();

        $this->collAccountHistories = new $collectionClassName;
        $this->collAccountHistories->setModel('\DataModels\DataModels\AccountHistory');
    }

    /**
     * Gets an array of ChildAccountHistory objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAccount is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildAccountHistory[] List of ChildAccountHistory objects
     * @throws PropelException
     */
    public function getAccountHistories(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collAccountHistoriesPartial && !$this->isNew();
        if (null === $this->collAccountHistories || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAccountHistories) {
                // return empty collection
                $this->initAccountHistories();
            } else {
                $collAccountHistories = ChildAccountHistoryQuery::create(null, $criteria)
                    ->filterByAccount($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collAccountHistoriesPartial && count($collAccountHistories)) {
                        $this->initAccountHistories(false);

                        foreach ($collAccountHistories as $obj) {
                            if (false == $this->collAccountHistories->contains($obj)) {
                                $this->collAccountHistories->append($obj);
                            }
                        }

                        $this->collAccountHistoriesPartial = true;
                    }

                    return $collAccountHistories;
                }

                if ($partial && $this->collAccountHistories) {
                    foreach ($this->collAccountHistories as $obj) {
                        if ($obj->isNew()) {
                            $collAccountHistories[] = $obj;
                        }
                    }
                }

                $this->collAccountHistories = $collAccountHistories;
                $this->collAccountHistoriesPartial = false;
            }
        }

        return $this->collAccountHistories;
    }

    /**
     * Sets a collection of ChildAccountHistory objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $accountHistories A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAccount The current object (for fluent API support)
     */
    public function setAccountHistories(Collection $accountHistories, ConnectionInterface $con = null)
    {
        /** @var ChildAccountHistory[] $accountHistoriesToDelete */
        $accountHistoriesToDelete = $this->getAccountHistories(new Criteria(), $con)->diff($accountHistories);


        $this->accountHistoriesScheduledForDeletion = $accountHistoriesToDelete;

        foreach ($accountHistoriesToDelete as $accountHistoryRemoved) {
            $accountHistoryRemoved->setAccount(null);
        }

        $this->collAccountHistories = null;
        foreach ($accountHistories as $accountHistory) {
            $this->addAccountHistory($accountHistory);
        }

        $this->collAccountHistories = $accountHistories;
        $this->collAccountHistoriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related AccountHistory objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related AccountHistory objects.
     * @throws PropelException
     */
    public function countAccountHistories(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collAccountHistoriesPartial && !$this->isNew();
        if (null === $this->collAccountHistories || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAccountHistories) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAccountHistories());
            }

            $query = ChildAccountHistoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAccount($this)
                ->count($con);
        }

        return count($this->collAccountHistories);
    }

    /**
     * Method called to associate a ChildAccountHistory object to this object
     * through the ChildAccountHistory foreign key attribute.
     *
     * @param  ChildAccountHistory $l ChildAccountHistory
     * @return $this|\DataModels\DataModels\Account The current object (for fluent API support)
     */
    public function addAccountHistory(ChildAccountHistory $l)
    {
        if ($this->collAccountHistories === null) {
            $this->initAccountHistories();
            $this->collAccountHistoriesPartial = true;
        }

        if (!$this->collAccountHistories->contains($l)) {
            $this->doAddAccountHistory($l);

            if ($this->accountHistoriesScheduledForDeletion and $this->accountHistoriesScheduledForDeletion->contains($l)) {
                $this->accountHistoriesScheduledForDeletion->remove($this->accountHistoriesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildAccountHistory $accountHistory The ChildAccountHistory object to add.
     */
    protected function doAddAccountHistory(ChildAccountHistory $accountHistory)
    {
        $this->collAccountHistories[]= $accountHistory;
        $accountHistory->setAccount($this);
    }

    /**
     * @param  ChildAccountHistory $accountHistory The ChildAccountHistory object to remove.
     * @return $this|ChildAccount The current object (for fluent API support)
     */
    public function removeAccountHistory(ChildAccountHistory $accountHistory)
    {
        if ($this->getAccountHistories()->contains($accountHistory)) {
            $pos = $this->collAccountHistories->search($accountHistory);
            $this->collAccountHistories->remove($pos);
            if (null === $this->accountHistoriesScheduledForDeletion) {
                $this->accountHistoriesScheduledForDeletion = clone $this->collAccountHistories;
                $this->accountHistoriesScheduledForDeletion->clear();
            }
            $this->accountHistoriesScheduledForDeletion[]= $accountHistory;
            $accountHistory->setAccount(null);
        }

        return $this;
    }

    /**
     * Clears out the collContacts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addContacts()
     */
    public function clearContacts()
    {
        $this->collContacts = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collContacts collection loaded partially.
     */
    public function resetPartialContacts($v = true)
    {
        $this->collContactsPartial = $v;
    }

    /**
     * Initializes the collContacts collection.
     *
     * By default this just sets the collContacts collection to an empty array (like clearcollContacts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initContacts($overrideExisting = true)
    {
        if (null !== $this->collContacts && !$overrideExisting) {
            return;
        }

        $collectionClassName = ContactTableMap::getTableMap()->getCollectionClassName();

        $this->collContacts = new $collectionClassName;
        $this->collContacts->setModel('\DataModels\DataModels\Contact');
    }

    /**
     * Gets an array of ChildContact objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildAccount is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildContact[] List of ChildContact objects
     * @throws PropelException
     */
    public function getContacts(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collContactsPartial && !$this->isNew();
        if (null === $this->collContacts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collContacts) {
                // return empty collection
                $this->initContacts();
            } else {
                $collContacts = ChildContactQuery::create(null, $criteria)
                    ->filterByAccount($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collContactsPartial && count($collContacts)) {
                        $this->initContacts(false);

                        foreach ($collContacts as $obj) {
                            if (false == $this->collContacts->contains($obj)) {
                                $this->collContacts->append($obj);
                            }
                        }

                        $this->collContactsPartial = true;
                    }

                    return $collContacts;
                }

                if ($partial && $this->collContacts) {
                    foreach ($this->collContacts as $obj) {
                        if ($obj->isNew()) {
                            $collContacts[] = $obj;
                        }
                    }
                }

                $this->collContacts = $collContacts;
                $this->collContactsPartial = false;
            }
        }

        return $this->collContacts;
    }

    /**
     * Sets a collection of ChildContact objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $contacts A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildAccount The current object (for fluent API support)
     */
    public function setContacts(Collection $contacts, ConnectionInterface $con = null)
    {
        /** @var ChildContact[] $contactsToDelete */
        $contactsToDelete = $this->getContacts(new Criteria(), $con)->diff($contacts);


        $this->contactsScheduledForDeletion = $contactsToDelete;

        foreach ($contactsToDelete as $contactRemoved) {
            $contactRemoved->setAccount(null);
        }

        $this->collContacts = null;
        foreach ($contacts as $contact) {
            $this->addContact($contact);
        }

        $this->collContacts = $contacts;
        $this->collContactsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Contact objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Contact objects.
     * @throws PropelException
     */
    public function countContacts(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collContactsPartial && !$this->isNew();
        if (null === $this->collContacts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collContacts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getContacts());
            }

            $query = ChildContactQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByAccount($this)
                ->count($con);
        }

        return count($this->collContacts);
    }

    /**
     * Method called to associate a ChildContact object to this object
     * through the ChildContact foreign key attribute.
     *
     * @param  ChildContact $l ChildContact
     * @return $this|\DataModels\DataModels\Account The current object (for fluent API support)
     */
    public function addContact(ChildContact $l)
    {
        if ($this->collContacts === null) {
            $this->initContacts();
            $this->collContactsPartial = true;
        }

        if (!$this->collContacts->contains($l)) {
            $this->doAddContact($l);

            if ($this->contactsScheduledForDeletion and $this->contactsScheduledForDeletion->contains($l)) {
                $this->contactsScheduledForDeletion->remove($this->contactsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildContact $contact The ChildContact object to add.
     */
    protected function doAddContact(ChildContact $contact)
    {
        $this->collContacts[]= $contact;
        $contact->setAccount($this);
    }

    /**
     * @param  ChildContact $contact The ChildContact object to remove.
     * @return $this|ChildAccount The current object (for fluent API support)
     */
    public function removeContact(ChildContact $contact)
    {
        if ($this->getContacts()->contains($contact)) {
            $pos = $this->collContacts->search($contact);
            $this->collContacts->remove($pos);
            if (null === $this->contactsScheduledForDeletion) {
                $this->contactsScheduledForDeletion = clone $this->collContacts;
                $this->contactsScheduledForDeletion->clear();
            }
            $this->contactsScheduledForDeletion[]= $contact;
            $contact->setAccount(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related Contacts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildContact[] List of ChildContact objects
     */
    public function getContactsJoinClient(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildContactQuery::create(null, $criteria);
        $query->joinWith('Client', $joinBehavior);

        return $this->getContacts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related Contacts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildContact[] List of ChildContact objects
     */
    public function getContactsJoinMeetingAttendee(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildContactQuery::create(null, $criteria);
        $query->joinWith('MeetingAttendee', $joinBehavior);

        return $this->getContacts($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aClient) {
            $this->aClient->removeAccount($this);
        }
        $this->id = null;
        $this->email_domain = null;
        $this->sfdc_account_id = null;
        $this->client_id = null;
        $this->sfdc_last_check_time = null;
        $this->sfdc_oppty_last_check_time = null;
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
            if ($this->collAccountHistories) {
                foreach ($this->collAccountHistories as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collContacts) {
                foreach ($this->collContacts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collAccountHistories = null;
        $this->collContacts = null;
        $this->aClient = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(AccountTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildAccount The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[AccountTableMap::COL_UPDATED_AT] = true;

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
