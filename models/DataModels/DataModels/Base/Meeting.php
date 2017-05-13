<?php

namespace DataModels\DataModels\Base;

use \DateTime;
use \Exception;
use \PDO;
use DataModels\DataModels\Meeting as ChildMeeting;
use DataModels\DataModels\MeetingAttendee as ChildMeetingAttendee;
use DataModels\DataModels\MeetingAttendeeQuery as ChildMeetingAttendeeQuery;
use DataModels\DataModels\MeetingHasAttendee as ChildMeetingHasAttendee;
use DataModels\DataModels\MeetingHasAttendeeQuery as ChildMeetingHasAttendeeQuery;
use DataModels\DataModels\MeetingQuery as ChildMeetingQuery;
use DataModels\DataModels\Map\MeetingHasAttendeeTableMap;
use DataModels\DataModels\Map\MeetingTableMap;
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
 * Base class that represents a row from the 'meeting' table.
 *
 *
 *
 * @package    propel.generator.DataModels.DataModels.Base
 */
abstract class Meeting implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\DataModels\\DataModels\\Map\\MeetingTableMap';


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
     * The value for the event_id field.
     *
     * @var        string
     */
    protected $event_id;

    /**
     * The value for the event_type field.
     *
     * @var        string
     */
    protected $event_type;

    /**
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the event_datetime field.
     *
     * @var        DateTime
     */
    protected $event_datetime;

    /**
     * The value for the event_creator_id field.
     *
     * @var        int
     */
    protected $event_creator_id;

    /**
     * The value for the event_owner_id field.
     *
     * @var        int
     */
    protected $event_owner_id;

    /**
     * The value for the event_description field.
     *
     * @var        string
     */
    protected $event_description;

    /**
     * The value for the account_id field.
     *
     * @var        int
     */
    protected $account_id;

    /**
     * The value for the additional_data field.
     *
     * @var        string
     */
    protected $additional_data;

    /**
     * The value for the event_created_at field.
     *
     * @var        DateTime
     */
    protected $event_created_at;

    /**
     * The value for the event_updated_at field.
     *
     * @var        DateTime
     */
    protected $event_updated_at;

    /**
     * The value for the raw_data field.
     *
     * @var        string
     */
    protected $raw_data;

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
     * @var        ChildMeetingAttendee
     */
    protected $aEventOwner;

    /**
     * @var        ChildMeetingAttendee
     */
    protected $aEventCreator;

    /**
     * @var        ObjectCollection|ChildMeetingHasAttendee[] Collection to store aggregation of ChildMeetingHasAttendee objects.
     */
    protected $collMeetingHasAttendees;
    protected $collMeetingHasAttendeesPartial;

    /**
     * @var        ObjectCollection|ChildMeetingAttendee[] Cross Collection to store aggregation of ChildMeetingAttendee objects.
     */
    protected $collMeetingAttendees;

    /**
     * @var bool
     */
    protected $collMeetingAttendeesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMeetingAttendee[]
     */
    protected $meetingAttendeesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMeetingHasAttendee[]
     */
    protected $meetingHasAttendeesScheduledForDeletion = null;

    /**
     * Initializes internal state of DataModels\DataModels\Base\Meeting object.
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
     * Compares this with another <code>Meeting</code> instance.  If
     * <code>obj</code> is an instance of <code>Meeting</code>, delegates to
     * <code>equals(Meeting)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Meeting The current object, for fluid interface
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
     * Get the [event_id] column value.
     *
     * @return string
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * Get the [event_type] column value.
     *
     * @return string
     */
    public function getEventType()
    {
        return $this->event_type;
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
     * Get the [optionally formatted] temporal [event_datetime] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getEventDatetime($format = NULL)
    {
        if ($format === null) {
            return $this->event_datetime;
        } else {
            return $this->event_datetime instanceof \DateTimeInterface ? $this->event_datetime->format($format) : null;
        }
    }

    /**
     * Get the [event_creator_id] column value.
     *
     * @return int
     */
    public function getEventCreatorId()
    {
        return $this->event_creator_id;
    }

    /**
     * Get the [event_owner_id] column value.
     *
     * @return int
     */
    public function getEventOwnerId()
    {
        return $this->event_owner_id;
    }

    /**
     * Get the [event_description] column value.
     *
     * @return string
     */
    public function getEventDescription()
    {
        return $this->event_description;
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
     * Get the [additional_data] column value.
     *
     * @return string
     */
    public function getAdditionalData()
    {
        return $this->additional_data;
    }

    /**
     * Get the [optionally formatted] temporal [event_created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getEventCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->event_created_at;
        } else {
            return $this->event_created_at instanceof \DateTimeInterface ? $this->event_created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [event_updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getEventUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->event_updated_at;
        } else {
            return $this->event_updated_at instanceof \DateTimeInterface ? $this->event_updated_at->format($format) : null;
        }
    }

    /**
     * Get the [raw_data] column value.
     *
     * @return string
     */
    public function getRawData()
    {
        return $this->raw_data;
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
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[MeetingTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [event_id] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setEventId($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_id !== $v) {
            $this->event_id = $v;
            $this->modifiedColumns[MeetingTableMap::COL_EVENT_ID] = true;
        }

        return $this;
    } // setEventId()

    /**
     * Set the value of [event_type] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setEventType($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_type !== $v) {
            $this->event_type = $v;
            $this->modifiedColumns[MeetingTableMap::COL_EVENT_TYPE] = true;
        }

        return $this;
    } // setEventType()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[MeetingTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Sets the value of [event_datetime] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setEventDatetime($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->event_datetime !== null || $dt !== null) {
            if ($this->event_datetime === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->event_datetime->format("Y-m-d H:i:s.u")) {
                $this->event_datetime = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MeetingTableMap::COL_EVENT_DATETIME] = true;
            }
        } // if either are not null

        return $this;
    } // setEventDatetime()

    /**
     * Set the value of [event_creator_id] column.
     *
     * @param int $v new value
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setEventCreatorId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->event_creator_id !== $v) {
            $this->event_creator_id = $v;
            $this->modifiedColumns[MeetingTableMap::COL_EVENT_CREATOR_ID] = true;
        }

        if ($this->aEventCreator !== null && $this->aEventCreator->getId() !== $v) {
            $this->aEventCreator = null;
        }

        return $this;
    } // setEventCreatorId()

    /**
     * Set the value of [event_owner_id] column.
     *
     * @param int $v new value
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setEventOwnerId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->event_owner_id !== $v) {
            $this->event_owner_id = $v;
            $this->modifiedColumns[MeetingTableMap::COL_EVENT_OWNER_ID] = true;
        }

        if ($this->aEventOwner !== null && $this->aEventOwner->getId() !== $v) {
            $this->aEventOwner = null;
        }

        return $this;
    } // setEventOwnerId()

    /**
     * Set the value of [event_description] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setEventDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_description !== $v) {
            $this->event_description = $v;
            $this->modifiedColumns[MeetingTableMap::COL_EVENT_DESCRIPTION] = true;
        }

        return $this;
    } // setEventDescription()

    /**
     * Set the value of [account_id] column.
     *
     * @param int $v new value
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setAccountId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->account_id !== $v) {
            $this->account_id = $v;
            $this->modifiedColumns[MeetingTableMap::COL_ACCOUNT_ID] = true;
        }

        return $this;
    } // setAccountId()

    /**
     * Set the value of [additional_data] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setAdditionalData($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->additional_data !== $v) {
            $this->additional_data = $v;
            $this->modifiedColumns[MeetingTableMap::COL_ADDITIONAL_DATA] = true;
        }

        return $this;
    } // setAdditionalData()

    /**
     * Sets the value of [event_created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setEventCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->event_created_at !== null || $dt !== null) {
            if ($this->event_created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->event_created_at->format("Y-m-d H:i:s.u")) {
                $this->event_created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MeetingTableMap::COL_EVENT_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setEventCreatedAt()

    /**
     * Sets the value of [event_updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setEventUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->event_updated_at !== null || $dt !== null) {
            if ($this->event_updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->event_updated_at->format("Y-m-d H:i:s.u")) {
                $this->event_updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MeetingTableMap::COL_EVENT_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setEventUpdatedAt()

    /**
     * Set the value of [raw_data] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setRawData($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->raw_data !== $v) {
            $this->raw_data = $v;
            $this->modifiedColumns[MeetingTableMap::COL_RAW_DATA] = true;
        }

        return $this;
    } // setRawData()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MeetingTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MeetingTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MeetingTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MeetingTableMap::translateFieldName('EventId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_id = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MeetingTableMap::translateFieldName('EventType', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_type = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MeetingTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : MeetingTableMap::translateFieldName('EventDatetime', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_datetime = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : MeetingTableMap::translateFieldName('EventCreatorId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_creator_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : MeetingTableMap::translateFieldName('EventOwnerId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_owner_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : MeetingTableMap::translateFieldName('EventDescription', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : MeetingTableMap::translateFieldName('AccountId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->account_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : MeetingTableMap::translateFieldName('AdditionalData', TableMap::TYPE_PHPNAME, $indexType)];
            $this->additional_data = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : MeetingTableMap::translateFieldName('EventCreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : MeetingTableMap::translateFieldName('EventUpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->event_updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : MeetingTableMap::translateFieldName('RawData', TableMap::TYPE_PHPNAME, $indexType)];
            $this->raw_data = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : MeetingTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : MeetingTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 15; // 15 = MeetingTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\DataModels\\DataModels\\Meeting'), 0, $e);
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
        if ($this->aEventCreator !== null && $this->event_creator_id !== $this->aEventCreator->getId()) {
            $this->aEventCreator = null;
        }
        if ($this->aEventOwner !== null && $this->event_owner_id !== $this->aEventOwner->getId()) {
            $this->aEventOwner = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(MeetingTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMeetingQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aEventOwner = null;
            $this->aEventCreator = null;
            $this->collMeetingHasAttendees = null;

            $this->collMeetingAttendees = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Meeting::setDeleted()
     * @see Meeting::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMeetingQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(MeetingTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
                if (!$this->isColumnModified(MeetingTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(MeetingTableMap::COL_UPDATED_AT)) {
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
                MeetingTableMap::addInstanceToPool($this);
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

            if ($this->aEventOwner !== null) {
                if ($this->aEventOwner->isModified() || $this->aEventOwner->isNew()) {
                    $affectedRows += $this->aEventOwner->save($con);
                }
                $this->setEventOwner($this->aEventOwner);
            }

            if ($this->aEventCreator !== null) {
                if ($this->aEventCreator->isModified() || $this->aEventCreator->isNew()) {
                    $affectedRows += $this->aEventCreator->save($con);
                }
                $this->setEventCreator($this->aEventCreator);
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

            if ($this->meetingAttendeesScheduledForDeletion !== null) {
                if (!$this->meetingAttendeesScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->meetingAttendeesScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \DataModels\DataModels\MeetingHasAttendeeQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->meetingAttendeesScheduledForDeletion = null;
                }

            }

            if ($this->collMeetingAttendees) {
                foreach ($this->collMeetingAttendees as $meetingAttendee) {
                    if (!$meetingAttendee->isDeleted() && ($meetingAttendee->isNew() || $meetingAttendee->isModified())) {
                        $meetingAttendee->save($con);
                    }
                }
            }


            if ($this->meetingHasAttendeesScheduledForDeletion !== null) {
                if (!$this->meetingHasAttendeesScheduledForDeletion->isEmpty()) {
                    \DataModels\DataModels\MeetingHasAttendeeQuery::create()
                        ->filterByPrimaryKeys($this->meetingHasAttendeesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->meetingHasAttendeesScheduledForDeletion = null;
                }
            }

            if ($this->collMeetingHasAttendees !== null) {
                foreach ($this->collMeetingHasAttendees as $referrerFK) {
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

        $this->modifiedColumns[MeetingTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MeetingTableMap::COL_ID . ')');
        }
        if (null === $this->id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('meeting_id_seq')");
                $this->id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MeetingTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'event_id';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_TYPE)) {
            $modifiedColumns[':p' . $index++]  = 'event_type';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_DATETIME)) {
            $modifiedColumns[':p' . $index++]  = 'event_datetime';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_CREATOR_ID)) {
            $modifiedColumns[':p' . $index++]  = 'event_creator_id';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_OWNER_ID)) {
            $modifiedColumns[':p' . $index++]  = 'event_owner_id';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'event_description';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++]  = 'account_id';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_ADDITIONAL_DATA)) {
            $modifiedColumns[':p' . $index++]  = 'additional_data';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'event_created_at';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'event_updated_at';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_RAW_DATA)) {
            $modifiedColumns[':p' . $index++]  = 'raw_data';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(MeetingTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO meeting (%s) VALUES (%s)',
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
                    case 'event_id':
                        $stmt->bindValue($identifier, $this->event_id, PDO::PARAM_STR);
                        break;
                    case 'event_type':
                        $stmt->bindValue($identifier, $this->event_type, PDO::PARAM_STR);
                        break;
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'event_datetime':
                        $stmt->bindValue($identifier, $this->event_datetime ? $this->event_datetime->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'event_creator_id':
                        $stmt->bindValue($identifier, $this->event_creator_id, PDO::PARAM_INT);
                        break;
                    case 'event_owner_id':
                        $stmt->bindValue($identifier, $this->event_owner_id, PDO::PARAM_INT);
                        break;
                    case 'event_description':
                        $stmt->bindValue($identifier, $this->event_description, PDO::PARAM_STR);
                        break;
                    case 'account_id':
                        $stmt->bindValue($identifier, $this->account_id, PDO::PARAM_INT);
                        break;
                    case 'additional_data':
                        $stmt->bindValue($identifier, $this->additional_data, PDO::PARAM_STR);
                        break;
                    case 'event_created_at':
                        $stmt->bindValue($identifier, $this->event_created_at ? $this->event_created_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'event_updated_at':
                        $stmt->bindValue($identifier, $this->event_updated_at ? $this->event_updated_at->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);
                        break;
                    case 'raw_data':
                        $stmt->bindValue($identifier, $this->raw_data, PDO::PARAM_STR);
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
        $pos = MeetingTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getEventId();
                break;
            case 2:
                return $this->getEventType();
                break;
            case 3:
                return $this->getName();
                break;
            case 4:
                return $this->getEventDatetime();
                break;
            case 5:
                return $this->getEventCreatorId();
                break;
            case 6:
                return $this->getEventOwnerId();
                break;
            case 7:
                return $this->getEventDescription();
                break;
            case 8:
                return $this->getAccountId();
                break;
            case 9:
                return $this->getAdditionalData();
                break;
            case 10:
                return $this->getEventCreatedAt();
                break;
            case 11:
                return $this->getEventUpdatedAt();
                break;
            case 12:
                return $this->getRawData();
                break;
            case 13:
                return $this->getCreatedAt();
                break;
            case 14:
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

        if (isset($alreadyDumpedObjects['Meeting'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Meeting'][$this->hashCode()] = true;
        $keys = MeetingTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getEventId(),
            $keys[2] => $this->getEventType(),
            $keys[3] => $this->getName(),
            $keys[4] => $this->getEventDatetime(),
            $keys[5] => $this->getEventCreatorId(),
            $keys[6] => $this->getEventOwnerId(),
            $keys[7] => $this->getEventDescription(),
            $keys[8] => $this->getAccountId(),
            $keys[9] => $this->getAdditionalData(),
            $keys[10] => $this->getEventCreatedAt(),
            $keys[11] => $this->getEventUpdatedAt(),
            $keys[12] => $this->getRawData(),
            $keys[13] => $this->getCreatedAt(),
            $keys[14] => $this->getUpdatedAt(),
        );
        if ($result[$keys[4]] instanceof \DateTime) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        if ($result[$keys[10]] instanceof \DateTime) {
            $result[$keys[10]] = $result[$keys[10]]->format('c');
        }

        if ($result[$keys[11]] instanceof \DateTime) {
            $result[$keys[11]] = $result[$keys[11]]->format('c');
        }

        if ($result[$keys[13]] instanceof \DateTime) {
            $result[$keys[13]] = $result[$keys[13]]->format('c');
        }

        if ($result[$keys[14]] instanceof \DateTime) {
            $result[$keys[14]] = $result[$keys[14]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aEventOwner) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'meetingAttendee';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'meeting_attendee';
                        break;
                    default:
                        $key = 'EventOwner';
                }

                $result[$key] = $this->aEventOwner->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aEventCreator) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'meetingAttendee';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'meeting_attendee';
                        break;
                    default:
                        $key = 'EventCreator';
                }

                $result[$key] = $this->aEventCreator->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collMeetingHasAttendees) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'meetingHasAttendees';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'meeting_has_attendees';
                        break;
                    default:
                        $key = 'MeetingHasAttendees';
                }

                $result[$key] = $this->collMeetingHasAttendees->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\DataModels\DataModels\Meeting
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MeetingTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\DataModels\DataModels\Meeting
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setEventId($value);
                break;
            case 2:
                $this->setEventType($value);
                break;
            case 3:
                $this->setName($value);
                break;
            case 4:
                $this->setEventDatetime($value);
                break;
            case 5:
                $this->setEventCreatorId($value);
                break;
            case 6:
                $this->setEventOwnerId($value);
                break;
            case 7:
                $this->setEventDescription($value);
                break;
            case 8:
                $this->setAccountId($value);
                break;
            case 9:
                $this->setAdditionalData($value);
                break;
            case 10:
                $this->setEventCreatedAt($value);
                break;
            case 11:
                $this->setEventUpdatedAt($value);
                break;
            case 12:
                $this->setRawData($value);
                break;
            case 13:
                $this->setCreatedAt($value);
                break;
            case 14:
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
        $keys = MeetingTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setEventId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setEventType($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setName($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setEventDatetime($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setEventCreatorId($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setEventOwnerId($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setEventDescription($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setAccountId($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setAdditionalData($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setEventCreatedAt($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setEventUpdatedAt($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setRawData($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setCreatedAt($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setUpdatedAt($arr[$keys[14]]);
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
     * @return $this|\DataModels\DataModels\Meeting The current object, for fluid interface
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
        $criteria = new Criteria(MeetingTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MeetingTableMap::COL_ID)) {
            $criteria->add(MeetingTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_ID)) {
            $criteria->add(MeetingTableMap::COL_EVENT_ID, $this->event_id);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_TYPE)) {
            $criteria->add(MeetingTableMap::COL_EVENT_TYPE, $this->event_type);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_NAME)) {
            $criteria->add(MeetingTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_DATETIME)) {
            $criteria->add(MeetingTableMap::COL_EVENT_DATETIME, $this->event_datetime);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_CREATOR_ID)) {
            $criteria->add(MeetingTableMap::COL_EVENT_CREATOR_ID, $this->event_creator_id);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_OWNER_ID)) {
            $criteria->add(MeetingTableMap::COL_EVENT_OWNER_ID, $this->event_owner_id);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_DESCRIPTION)) {
            $criteria->add(MeetingTableMap::COL_EVENT_DESCRIPTION, $this->event_description);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_ACCOUNT_ID)) {
            $criteria->add(MeetingTableMap::COL_ACCOUNT_ID, $this->account_id);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_ADDITIONAL_DATA)) {
            $criteria->add(MeetingTableMap::COL_ADDITIONAL_DATA, $this->additional_data);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_CREATED_AT)) {
            $criteria->add(MeetingTableMap::COL_EVENT_CREATED_AT, $this->event_created_at);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_EVENT_UPDATED_AT)) {
            $criteria->add(MeetingTableMap::COL_EVENT_UPDATED_AT, $this->event_updated_at);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_RAW_DATA)) {
            $criteria->add(MeetingTableMap::COL_RAW_DATA, $this->raw_data);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_CREATED_AT)) {
            $criteria->add(MeetingTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(MeetingTableMap::COL_UPDATED_AT)) {
            $criteria->add(MeetingTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildMeetingQuery::create();
        $criteria->add(MeetingTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \DataModels\DataModels\Meeting (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setEventId($this->getEventId());
        $copyObj->setEventType($this->getEventType());
        $copyObj->setName($this->getName());
        $copyObj->setEventDatetime($this->getEventDatetime());
        $copyObj->setEventCreatorId($this->getEventCreatorId());
        $copyObj->setEventOwnerId($this->getEventOwnerId());
        $copyObj->setEventDescription($this->getEventDescription());
        $copyObj->setAccountId($this->getAccountId());
        $copyObj->setAdditionalData($this->getAdditionalData());
        $copyObj->setEventCreatedAt($this->getEventCreatedAt());
        $copyObj->setEventUpdatedAt($this->getEventUpdatedAt());
        $copyObj->setRawData($this->getRawData());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMeetingHasAttendees() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMeetingHasAttendee($relObj->copy($deepCopy));
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
     * @return \DataModels\DataModels\Meeting Clone of current object.
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
     * Declares an association between this object and a ChildMeetingAttendee object.
     *
     * @param  ChildMeetingAttendee $v
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEventOwner(ChildMeetingAttendee $v = null)
    {
        if ($v === null) {
            $this->setEventOwnerId(NULL);
        } else {
            $this->setEventOwnerId($v->getId());
        }

        $this->aEventOwner = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildMeetingAttendee object, it will not be re-added.
        if ($v !== null) {
            $v->addMeetingRelatedByEventOwnerId($this);
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
    public function getEventOwner(ConnectionInterface $con = null)
    {
        if ($this->aEventOwner === null && ($this->event_owner_id !== null)) {
            $this->aEventOwner = ChildMeetingAttendeeQuery::create()->findPk($this->event_owner_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventOwner->addMeetingsRelatedByEventOwnerId($this);
             */
        }

        return $this->aEventOwner;
    }

    /**
     * Declares an association between this object and a ChildMeetingAttendee object.
     *
     * @param  ChildMeetingAttendee $v
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     * @throws PropelException
     */
    public function setEventCreator(ChildMeetingAttendee $v = null)
    {
        if ($v === null) {
            $this->setEventCreatorId(NULL);
        } else {
            $this->setEventCreatorId($v->getId());
        }

        $this->aEventCreator = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildMeetingAttendee object, it will not be re-added.
        if ($v !== null) {
            $v->addMeetingRelatedByEventCreatorId($this);
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
    public function getEventCreator(ConnectionInterface $con = null)
    {
        if ($this->aEventCreator === null && ($this->event_creator_id !== null)) {
            $this->aEventCreator = ChildMeetingAttendeeQuery::create()->findPk($this->event_creator_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aEventCreator->addMeetingsRelatedByEventCreatorId($this);
             */
        }

        return $this->aEventCreator;
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
        if ('MeetingHasAttendee' == $relationName) {
            return $this->initMeetingHasAttendees();
        }
    }

    /**
     * Clears out the collMeetingHasAttendees collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMeetingHasAttendees()
     */
    public function clearMeetingHasAttendees()
    {
        $this->collMeetingHasAttendees = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMeetingHasAttendees collection loaded partially.
     */
    public function resetPartialMeetingHasAttendees($v = true)
    {
        $this->collMeetingHasAttendeesPartial = $v;
    }

    /**
     * Initializes the collMeetingHasAttendees collection.
     *
     * By default this just sets the collMeetingHasAttendees collection to an empty array (like clearcollMeetingHasAttendees());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMeetingHasAttendees($overrideExisting = true)
    {
        if (null !== $this->collMeetingHasAttendees && !$overrideExisting) {
            return;
        }

        $collectionClassName = MeetingHasAttendeeTableMap::getTableMap()->getCollectionClassName();

        $this->collMeetingHasAttendees = new $collectionClassName;
        $this->collMeetingHasAttendees->setModel('\DataModels\DataModels\MeetingHasAttendee');
    }

    /**
     * Gets an array of ChildMeetingHasAttendee objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMeeting is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMeetingHasAttendee[] List of ChildMeetingHasAttendee objects
     * @throws PropelException
     */
    public function getMeetingHasAttendees(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMeetingHasAttendeesPartial && !$this->isNew();
        if (null === $this->collMeetingHasAttendees || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMeetingHasAttendees) {
                // return empty collection
                $this->initMeetingHasAttendees();
            } else {
                $collMeetingHasAttendees = ChildMeetingHasAttendeeQuery::create(null, $criteria)
                    ->filterByMeeting($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMeetingHasAttendeesPartial && count($collMeetingHasAttendees)) {
                        $this->initMeetingHasAttendees(false);

                        foreach ($collMeetingHasAttendees as $obj) {
                            if (false == $this->collMeetingHasAttendees->contains($obj)) {
                                $this->collMeetingHasAttendees->append($obj);
                            }
                        }

                        $this->collMeetingHasAttendeesPartial = true;
                    }

                    return $collMeetingHasAttendees;
                }

                if ($partial && $this->collMeetingHasAttendees) {
                    foreach ($this->collMeetingHasAttendees as $obj) {
                        if ($obj->isNew()) {
                            $collMeetingHasAttendees[] = $obj;
                        }
                    }
                }

                $this->collMeetingHasAttendees = $collMeetingHasAttendees;
                $this->collMeetingHasAttendeesPartial = false;
            }
        }

        return $this->collMeetingHasAttendees;
    }

    /**
     * Sets a collection of ChildMeetingHasAttendee objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $meetingHasAttendees A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMeeting The current object (for fluent API support)
     */
    public function setMeetingHasAttendees(Collection $meetingHasAttendees, ConnectionInterface $con = null)
    {
        /** @var ChildMeetingHasAttendee[] $meetingHasAttendeesToDelete */
        $meetingHasAttendeesToDelete = $this->getMeetingHasAttendees(new Criteria(), $con)->diff($meetingHasAttendees);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->meetingHasAttendeesScheduledForDeletion = clone $meetingHasAttendeesToDelete;

        foreach ($meetingHasAttendeesToDelete as $meetingHasAttendeeRemoved) {
            $meetingHasAttendeeRemoved->setMeeting(null);
        }

        $this->collMeetingHasAttendees = null;
        foreach ($meetingHasAttendees as $meetingHasAttendee) {
            $this->addMeetingHasAttendee($meetingHasAttendee);
        }

        $this->collMeetingHasAttendees = $meetingHasAttendees;
        $this->collMeetingHasAttendeesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MeetingHasAttendee objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MeetingHasAttendee objects.
     * @throws PropelException
     */
    public function countMeetingHasAttendees(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMeetingHasAttendeesPartial && !$this->isNew();
        if (null === $this->collMeetingHasAttendees || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMeetingHasAttendees) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMeetingHasAttendees());
            }

            $query = ChildMeetingHasAttendeeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMeeting($this)
                ->count($con);
        }

        return count($this->collMeetingHasAttendees);
    }

    /**
     * Method called to associate a ChildMeetingHasAttendee object to this object
     * through the ChildMeetingHasAttendee foreign key attribute.
     *
     * @param  ChildMeetingHasAttendee $l ChildMeetingHasAttendee
     * @return $this|\DataModels\DataModels\Meeting The current object (for fluent API support)
     */
    public function addMeetingHasAttendee(ChildMeetingHasAttendee $l)
    {
        if ($this->collMeetingHasAttendees === null) {
            $this->initMeetingHasAttendees();
            $this->collMeetingHasAttendeesPartial = true;
        }

        if (!$this->collMeetingHasAttendees->contains($l)) {
            $this->doAddMeetingHasAttendee($l);

            if ($this->meetingHasAttendeesScheduledForDeletion and $this->meetingHasAttendeesScheduledForDeletion->contains($l)) {
                $this->meetingHasAttendeesScheduledForDeletion->remove($this->meetingHasAttendeesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMeetingHasAttendee $meetingHasAttendee The ChildMeetingHasAttendee object to add.
     */
    protected function doAddMeetingHasAttendee(ChildMeetingHasAttendee $meetingHasAttendee)
    {
        $this->collMeetingHasAttendees[]= $meetingHasAttendee;
        $meetingHasAttendee->setMeeting($this);
    }

    /**
     * @param  ChildMeetingHasAttendee $meetingHasAttendee The ChildMeetingHasAttendee object to remove.
     * @return $this|ChildMeeting The current object (for fluent API support)
     */
    public function removeMeetingHasAttendee(ChildMeetingHasAttendee $meetingHasAttendee)
    {
        if ($this->getMeetingHasAttendees()->contains($meetingHasAttendee)) {
            $pos = $this->collMeetingHasAttendees->search($meetingHasAttendee);
            $this->collMeetingHasAttendees->remove($pos);
            if (null === $this->meetingHasAttendeesScheduledForDeletion) {
                $this->meetingHasAttendeesScheduledForDeletion = clone $this->collMeetingHasAttendees;
                $this->meetingHasAttendeesScheduledForDeletion->clear();
            }
            $this->meetingHasAttendeesScheduledForDeletion[]= clone $meetingHasAttendee;
            $meetingHasAttendee->setMeeting(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Meeting is new, it will return
     * an empty collection; or if this Meeting has previously
     * been saved, it will retrieve related MeetingHasAttendees from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Meeting.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMeetingHasAttendee[] List of ChildMeetingHasAttendee objects
     */
    public function getMeetingHasAttendeesJoinMeetingAttendee(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMeetingHasAttendeeQuery::create(null, $criteria);
        $query->joinWith('MeetingAttendee', $joinBehavior);

        return $this->getMeetingHasAttendees($query, $con);
    }

    /**
     * Clears out the collMeetingAttendees collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMeetingAttendees()
     */
    public function clearMeetingAttendees()
    {
        $this->collMeetingAttendees = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collMeetingAttendees crossRef collection.
     *
     * By default this just sets the collMeetingAttendees collection to an empty collection (like clearMeetingAttendees());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initMeetingAttendees()
    {
        $collectionClassName = MeetingHasAttendeeTableMap::getTableMap()->getCollectionClassName();

        $this->collMeetingAttendees = new $collectionClassName;
        $this->collMeetingAttendeesPartial = true;
        $this->collMeetingAttendees->setModel('\DataModels\DataModels\MeetingAttendee');
    }

    /**
     * Checks if the collMeetingAttendees collection is loaded.
     *
     * @return bool
     */
    public function isMeetingAttendeesLoaded()
    {
        return null !== $this->collMeetingAttendees;
    }

    /**
     * Gets a collection of ChildMeetingAttendee objects related by a many-to-many relationship
     * to the current object by way of the meeting_has_attendee cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMeeting is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildMeetingAttendee[] List of ChildMeetingAttendee objects
     */
    public function getMeetingAttendees(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMeetingAttendeesPartial && !$this->isNew();
        if (null === $this->collMeetingAttendees || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collMeetingAttendees) {
                    $this->initMeetingAttendees();
                }
            } else {

                $query = ChildMeetingAttendeeQuery::create(null, $criteria)
                    ->filterByMeeting($this);
                $collMeetingAttendees = $query->find($con);
                if (null !== $criteria) {
                    return $collMeetingAttendees;
                }

                if ($partial && $this->collMeetingAttendees) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collMeetingAttendees as $obj) {
                        if (!$collMeetingAttendees->contains($obj)) {
                            $collMeetingAttendees[] = $obj;
                        }
                    }
                }

                $this->collMeetingAttendees = $collMeetingAttendees;
                $this->collMeetingAttendeesPartial = false;
            }
        }

        return $this->collMeetingAttendees;
    }

    /**
     * Sets a collection of MeetingAttendee objects related by a many-to-many relationship
     * to the current object by way of the meeting_has_attendee cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $meetingAttendees A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildMeeting The current object (for fluent API support)
     */
    public function setMeetingAttendees(Collection $meetingAttendees, ConnectionInterface $con = null)
    {
        $this->clearMeetingAttendees();
        $currentMeetingAttendees = $this->getMeetingAttendees();

        $meetingAttendeesScheduledForDeletion = $currentMeetingAttendees->diff($meetingAttendees);

        foreach ($meetingAttendeesScheduledForDeletion as $toDelete) {
            $this->removeMeetingAttendee($toDelete);
        }

        foreach ($meetingAttendees as $meetingAttendee) {
            if (!$currentMeetingAttendees->contains($meetingAttendee)) {
                $this->doAddMeetingAttendee($meetingAttendee);
            }
        }

        $this->collMeetingAttendeesPartial = false;
        $this->collMeetingAttendees = $meetingAttendees;

        return $this;
    }

    /**
     * Gets the number of MeetingAttendee objects related by a many-to-many relationship
     * to the current object by way of the meeting_has_attendee cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related MeetingAttendee objects
     */
    public function countMeetingAttendees(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMeetingAttendeesPartial && !$this->isNew();
        if (null === $this->collMeetingAttendees || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMeetingAttendees) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getMeetingAttendees());
                }

                $query = ChildMeetingAttendeeQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByMeeting($this)
                    ->count($con);
            }
        } else {
            return count($this->collMeetingAttendees);
        }
    }

    /**
     * Associate a ChildMeetingAttendee to this object
     * through the meeting_has_attendee cross reference table.
     *
     * @param ChildMeetingAttendee $meetingAttendee
     * @return ChildMeeting The current object (for fluent API support)
     */
    public function addMeetingAttendee(ChildMeetingAttendee $meetingAttendee)
    {
        if ($this->collMeetingAttendees === null) {
            $this->initMeetingAttendees();
        }

        if (!$this->getMeetingAttendees()->contains($meetingAttendee)) {
            // only add it if the **same** object is not already associated
            $this->collMeetingAttendees->push($meetingAttendee);
            $this->doAddMeetingAttendee($meetingAttendee);
        }

        return $this;
    }

    /**
     *
     * @param ChildMeetingAttendee $meetingAttendee
     */
    protected function doAddMeetingAttendee(ChildMeetingAttendee $meetingAttendee)
    {
        $meetingHasAttendee = new ChildMeetingHasAttendee();

        $meetingHasAttendee->setMeetingAttendee($meetingAttendee);

        $meetingHasAttendee->setMeeting($this);

        $this->addMeetingHasAttendee($meetingHasAttendee);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$meetingAttendee->isMeetingsLoaded()) {
            $meetingAttendee->initMeetings();
            $meetingAttendee->getMeetings()->push($this);
        } elseif (!$meetingAttendee->getMeetings()->contains($this)) {
            $meetingAttendee->getMeetings()->push($this);
        }

    }

    /**
     * Remove meetingAttendee of this object
     * through the meeting_has_attendee cross reference table.
     *
     * @param ChildMeetingAttendee $meetingAttendee
     * @return ChildMeeting The current object (for fluent API support)
     */
    public function removeMeetingAttendee(ChildMeetingAttendee $meetingAttendee)
    {
        if ($this->getMeetingAttendees()->contains($meetingAttendee)) {
            $meetingHasAttendee = new ChildMeetingHasAttendee();
            $meetingHasAttendee->setMeetingAttendee($meetingAttendee);
            if ($meetingAttendee->isMeetingsLoaded()) {
                //remove the back reference if available
                $meetingAttendee->getMeetings()->removeObject($this);
            }

            $meetingHasAttendee->setMeeting($this);
            $this->removeMeetingHasAttendee(clone $meetingHasAttendee);
            $meetingHasAttendee->clear();

            $this->collMeetingAttendees->remove($this->collMeetingAttendees->search($meetingAttendee));

            if (null === $this->meetingAttendeesScheduledForDeletion) {
                $this->meetingAttendeesScheduledForDeletion = clone $this->collMeetingAttendees;
                $this->meetingAttendeesScheduledForDeletion->clear();
            }

            $this->meetingAttendeesScheduledForDeletion->push($meetingAttendee);
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
        if (null !== $this->aEventOwner) {
            $this->aEventOwner->removeMeetingRelatedByEventOwnerId($this);
        }
        if (null !== $this->aEventCreator) {
            $this->aEventCreator->removeMeetingRelatedByEventCreatorId($this);
        }
        $this->id = null;
        $this->event_id = null;
        $this->event_type = null;
        $this->name = null;
        $this->event_datetime = null;
        $this->event_creator_id = null;
        $this->event_owner_id = null;
        $this->event_description = null;
        $this->account_id = null;
        $this->additional_data = null;
        $this->event_created_at = null;
        $this->event_updated_at = null;
        $this->raw_data = null;
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
            if ($this->collMeetingHasAttendees) {
                foreach ($this->collMeetingHasAttendees as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMeetingAttendees) {
                foreach ($this->collMeetingAttendees as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMeetingHasAttendees = null;
        $this->collMeetingAttendees = null;
        $this->aEventOwner = null;
        $this->aEventCreator = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MeetingTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildMeeting The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[MeetingTableMap::COL_UPDATED_AT] = true;

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
