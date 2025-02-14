<?php

namespace DataModels\DataModels\Base;

use \DateTime;
use \Exception;
use \PDO;
use DataModels\DataModels\ClientCalendarUser as ChildClientCalendarUser;
use DataModels\DataModels\ClientCalendarUserQuery as ChildClientCalendarUserQuery;
use DataModels\DataModels\Contact as ChildContact;
use DataModels\DataModels\ContactQuery as ChildContactQuery;
use DataModels\DataModels\Meeting as ChildMeeting;
use DataModels\DataModels\MeetingAttendee as ChildMeetingAttendee;
use DataModels\DataModels\MeetingAttendeeQuery as ChildMeetingAttendeeQuery;
use DataModels\DataModels\MeetingHasAttendee as ChildMeetingHasAttendee;
use DataModels\DataModels\MeetingHasAttendeeQuery as ChildMeetingHasAttendeeQuery;
use DataModels\DataModels\MeetingQuery as ChildMeetingQuery;
use DataModels\DataModels\Map\MeetingAttendeeTableMap;
use DataModels\DataModels\Map\MeetingHasAttendeeTableMap;
use DataModels\DataModels\Map\MeetingTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\PropelQuery;
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
 * Base class that represents a row from the 'meeting_attendee' table.
 *
 *
 *
 * @package    propel.generator.DataModels.DataModels.Base
 */
abstract class MeetingAttendee implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\DataModels\\DataModels\\Map\\MeetingAttendeeTableMap';


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
     * The value for the descendant_class field.
     *
     * @var        string
     */
    protected $descendant_class;

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
     * @var        ObjectCollection|ChildMeeting[] Collection to store aggregation of ChildMeeting objects.
     */
    protected $collMeetingsRelatedByEventOwnerId;
    protected $collMeetingsRelatedByEventOwnerIdPartial;

    /**
     * @var        ObjectCollection|ChildMeeting[] Collection to store aggregation of ChildMeeting objects.
     */
    protected $collMeetingsRelatedByEventCreatorId;
    protected $collMeetingsRelatedByEventCreatorIdPartial;

    /**
     * @var        ObjectCollection|ChildMeetingHasAttendee[] Collection to store aggregation of ChildMeetingHasAttendee objects.
     */
    protected $collMeetingHasAttendees;
    protected $collMeetingHasAttendeesPartial;

    /**
     * @var        ChildContact one-to-one related ChildContact object
     */
    protected $singleContact;

    /**
     * @var        ChildClientCalendarUser one-to-one related ChildClientCalendarUser object
     */
    protected $singleClientCalendarUser;

    /**
     * @var        ObjectCollection|ChildMeeting[] Cross Collection to store aggregation of ChildMeeting objects.
     */
    protected $collMeetings;

    /**
     * @var bool
     */
    protected $collMeetingsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMeeting[]
     */
    protected $meetingsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMeeting[]
     */
    protected $meetingsRelatedByEventOwnerIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMeeting[]
     */
    protected $meetingsRelatedByEventCreatorIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMeetingHasAttendee[]
     */
    protected $meetingHasAttendeesScheduledForDeletion = null;

    /**
     * Initializes internal state of DataModels\DataModels\Base\MeetingAttendee object.
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
     * Compares this with another <code>MeetingAttendee</code> instance.  If
     * <code>obj</code> is an instance of <code>MeetingAttendee</code>, delegates to
     * <code>equals(MeetingAttendee)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|MeetingAttendee The current object, for fluid interface
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
     * Get the [descendant_class] column value.
     *
     * @return string
     */
    public function getDescendantClass()
    {
        return $this->descendant_class;
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
     * @return $this|\DataModels\DataModels\MeetingAttendee The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[MeetingAttendeeTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [descendant_class] column.
     *
     * @param string $v new value
     * @return $this|\DataModels\DataModels\MeetingAttendee The current object (for fluent API support)
     */
    public function setDescendantClass($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->descendant_class !== $v) {
            $this->descendant_class = $v;
            $this->modifiedColumns[MeetingAttendeeTableMap::COL_DESCENDANT_CLASS] = true;
        }

        return $this;
    } // setDescendantClass()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\MeetingAttendee The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->created_at->format("Y-m-d H:i:s.u")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MeetingAttendeeTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\DataModels\DataModels\MeetingAttendee The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->updated_at->format("Y-m-d H:i:s.u")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[MeetingAttendeeTableMap::COL_UPDATED_AT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MeetingAttendeeTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MeetingAttendeeTableMap::translateFieldName('DescendantClass', TableMap::TYPE_PHPNAME, $indexType)];
            $this->descendant_class = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MeetingAttendeeTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MeetingAttendeeTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = MeetingAttendeeTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\DataModels\\DataModels\\MeetingAttendee'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(MeetingAttendeeTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMeetingAttendeeQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collMeetingsRelatedByEventOwnerId = null;

            $this->collMeetingsRelatedByEventCreatorId = null;

            $this->collMeetingHasAttendees = null;

            $this->singleContact = null;

            $this->singleClientCalendarUser = null;

            $this->collMeetings = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see MeetingAttendee::setDeleted()
     * @see MeetingAttendee::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingAttendeeTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMeetingAttendeeQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(MeetingAttendeeTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(MeetingAttendeeTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
                if (!$this->isColumnModified(MeetingAttendeeTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(\Propel\Runtime\Util\PropelDateTime::createHighPrecision());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(MeetingAttendeeTableMap::COL_UPDATED_AT)) {
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
                MeetingAttendeeTableMap::addInstanceToPool($this);
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

            if ($this->meetingsScheduledForDeletion !== null) {
                if (!$this->meetingsScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->meetingsScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \DataModels\DataModels\MeetingHasAttendeeQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->meetingsScheduledForDeletion = null;
                }

            }

            if ($this->collMeetings) {
                foreach ($this->collMeetings as $meeting) {
                    if (!$meeting->isDeleted() && ($meeting->isNew() || $meeting->isModified())) {
                        $meeting->save($con);
                    }
                }
            }


            if ($this->meetingsRelatedByEventOwnerIdScheduledForDeletion !== null) {
                if (!$this->meetingsRelatedByEventOwnerIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->meetingsRelatedByEventOwnerIdScheduledForDeletion as $meetingRelatedByEventOwnerId) {
                        // need to save related object because we set the relation to null
                        $meetingRelatedByEventOwnerId->save($con);
                    }
                    $this->meetingsRelatedByEventOwnerIdScheduledForDeletion = null;
                }
            }

            if ($this->collMeetingsRelatedByEventOwnerId !== null) {
                foreach ($this->collMeetingsRelatedByEventOwnerId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->meetingsRelatedByEventCreatorIdScheduledForDeletion !== null) {
                if (!$this->meetingsRelatedByEventCreatorIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->meetingsRelatedByEventCreatorIdScheduledForDeletion as $meetingRelatedByEventCreatorId) {
                        // need to save related object because we set the relation to null
                        $meetingRelatedByEventCreatorId->save($con);
                    }
                    $this->meetingsRelatedByEventCreatorIdScheduledForDeletion = null;
                }
            }

            if ($this->collMeetingsRelatedByEventCreatorId !== null) {
                foreach ($this->collMeetingsRelatedByEventCreatorId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
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

            if ($this->singleContact !== null) {
                if (!$this->singleContact->isDeleted() && ($this->singleContact->isNew() || $this->singleContact->isModified())) {
                    $affectedRows += $this->singleContact->save($con);
                }
            }

            if ($this->singleClientCalendarUser !== null) {
                if (!$this->singleClientCalendarUser->isDeleted() && ($this->singleClientCalendarUser->isNew() || $this->singleClientCalendarUser->isModified())) {
                    $affectedRows += $this->singleClientCalendarUser->save($con);
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

        $this->modifiedColumns[MeetingAttendeeTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MeetingAttendeeTableMap::COL_ID . ')');
        }
        if (null === $this->id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('meeting_attendee_id_seq')");
                $this->id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MeetingAttendeeTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(MeetingAttendeeTableMap::COL_DESCENDANT_CLASS)) {
            $modifiedColumns[':p' . $index++]  = 'descendant_class';
        }
        if ($this->isColumnModified(MeetingAttendeeTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(MeetingAttendeeTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO meeting_attendee (%s) VALUES (%s)',
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
                    case 'descendant_class':
                        $stmt->bindValue($identifier, $this->descendant_class, PDO::PARAM_STR);
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
        $pos = MeetingAttendeeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getDescendantClass();
                break;
            case 2:
                return $this->getCreatedAt();
                break;
            case 3:
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

        if (isset($alreadyDumpedObjects['MeetingAttendee'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['MeetingAttendee'][$this->hashCode()] = true;
        $keys = MeetingAttendeeTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getDescendantClass(),
            $keys[2] => $this->getCreatedAt(),
            $keys[3] => $this->getUpdatedAt(),
        );
        if ($result[$keys[2]] instanceof \DateTime) {
            $result[$keys[2]] = $result[$keys[2]]->format('c');
        }

        if ($result[$keys[3]] instanceof \DateTime) {
            $result[$keys[3]] = $result[$keys[3]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collMeetingsRelatedByEventOwnerId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'meetings';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'meetings';
                        break;
                    default:
                        $key = 'Meetings';
                }

                $result[$key] = $this->collMeetingsRelatedByEventOwnerId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMeetingsRelatedByEventCreatorId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'meetings';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'meetings';
                        break;
                    default:
                        $key = 'Meetings';
                }

                $result[$key] = $this->collMeetingsRelatedByEventCreatorId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
            if (null !== $this->singleContact) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'contact';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'contact';
                        break;
                    default:
                        $key = 'Contact';
                }

                $result[$key] = $this->singleContact->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->singleClientCalendarUser) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'clientCalendarUser';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'client_calendar_user';
                        break;
                    default:
                        $key = 'ClientCalendarUser';
                }

                $result[$key] = $this->singleClientCalendarUser->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
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
     * @return $this|\DataModels\DataModels\MeetingAttendee
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MeetingAttendeeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\DataModels\DataModels\MeetingAttendee
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setDescendantClass($value);
                break;
            case 2:
                $this->setCreatedAt($value);
                break;
            case 3:
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
        $keys = MeetingAttendeeTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setDescendantClass($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setCreatedAt($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setUpdatedAt($arr[$keys[3]]);
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
     * @return $this|\DataModels\DataModels\MeetingAttendee The current object, for fluid interface
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
        $criteria = new Criteria(MeetingAttendeeTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MeetingAttendeeTableMap::COL_ID)) {
            $criteria->add(MeetingAttendeeTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(MeetingAttendeeTableMap::COL_DESCENDANT_CLASS)) {
            $criteria->add(MeetingAttendeeTableMap::COL_DESCENDANT_CLASS, $this->descendant_class);
        }
        if ($this->isColumnModified(MeetingAttendeeTableMap::COL_CREATED_AT)) {
            $criteria->add(MeetingAttendeeTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(MeetingAttendeeTableMap::COL_UPDATED_AT)) {
            $criteria->add(MeetingAttendeeTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildMeetingAttendeeQuery::create();
        $criteria->add(MeetingAttendeeTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \DataModels\DataModels\MeetingAttendee (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setDescendantClass($this->getDescendantClass());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMeetingsRelatedByEventOwnerId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMeetingRelatedByEventOwnerId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMeetingsRelatedByEventCreatorId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMeetingRelatedByEventCreatorId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMeetingHasAttendees() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMeetingHasAttendee($relObj->copy($deepCopy));
                }
            }

            $relObj = $this->getContact();
            if ($relObj) {
                $copyObj->setContact($relObj->copy($deepCopy));
            }

            $relObj = $this->getClientCalendarUser();
            if ($relObj) {
                $copyObj->setClientCalendarUser($relObj->copy($deepCopy));
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
     * @return \DataModels\DataModels\MeetingAttendee Clone of current object.
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('MeetingRelatedByEventOwnerId' == $relationName) {
            return $this->initMeetingsRelatedByEventOwnerId();
        }
        if ('MeetingRelatedByEventCreatorId' == $relationName) {
            return $this->initMeetingsRelatedByEventCreatorId();
        }
        if ('MeetingHasAttendee' == $relationName) {
            return $this->initMeetingHasAttendees();
        }
    }

    /**
     * Clears out the collMeetingsRelatedByEventOwnerId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMeetingsRelatedByEventOwnerId()
     */
    public function clearMeetingsRelatedByEventOwnerId()
    {
        $this->collMeetingsRelatedByEventOwnerId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMeetingsRelatedByEventOwnerId collection loaded partially.
     */
    public function resetPartialMeetingsRelatedByEventOwnerId($v = true)
    {
        $this->collMeetingsRelatedByEventOwnerIdPartial = $v;
    }

    /**
     * Initializes the collMeetingsRelatedByEventOwnerId collection.
     *
     * By default this just sets the collMeetingsRelatedByEventOwnerId collection to an empty array (like clearcollMeetingsRelatedByEventOwnerId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMeetingsRelatedByEventOwnerId($overrideExisting = true)
    {
        if (null !== $this->collMeetingsRelatedByEventOwnerId && !$overrideExisting) {
            return;
        }

        $collectionClassName = MeetingTableMap::getTableMap()->getCollectionClassName();

        $this->collMeetingsRelatedByEventOwnerId = new $collectionClassName;
        $this->collMeetingsRelatedByEventOwnerId->setModel('\DataModels\DataModels\Meeting');
    }

    /**
     * Gets an array of ChildMeeting objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMeetingAttendee is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMeeting[] List of ChildMeeting objects
     * @throws PropelException
     */
    public function getMeetingsRelatedByEventOwnerId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMeetingsRelatedByEventOwnerIdPartial && !$this->isNew();
        if (null === $this->collMeetingsRelatedByEventOwnerId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMeetingsRelatedByEventOwnerId) {
                // return empty collection
                $this->initMeetingsRelatedByEventOwnerId();
            } else {
                $collMeetingsRelatedByEventOwnerId = ChildMeetingQuery::create(null, $criteria)
                    ->filterByEventOwner($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMeetingsRelatedByEventOwnerIdPartial && count($collMeetingsRelatedByEventOwnerId)) {
                        $this->initMeetingsRelatedByEventOwnerId(false);

                        foreach ($collMeetingsRelatedByEventOwnerId as $obj) {
                            if (false == $this->collMeetingsRelatedByEventOwnerId->contains($obj)) {
                                $this->collMeetingsRelatedByEventOwnerId->append($obj);
                            }
                        }

                        $this->collMeetingsRelatedByEventOwnerIdPartial = true;
                    }

                    return $collMeetingsRelatedByEventOwnerId;
                }

                if ($partial && $this->collMeetingsRelatedByEventOwnerId) {
                    foreach ($this->collMeetingsRelatedByEventOwnerId as $obj) {
                        if ($obj->isNew()) {
                            $collMeetingsRelatedByEventOwnerId[] = $obj;
                        }
                    }
                }

                $this->collMeetingsRelatedByEventOwnerId = $collMeetingsRelatedByEventOwnerId;
                $this->collMeetingsRelatedByEventOwnerIdPartial = false;
            }
        }

        return $this->collMeetingsRelatedByEventOwnerId;
    }

    /**
     * Sets a collection of ChildMeeting objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $meetingsRelatedByEventOwnerId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMeetingAttendee The current object (for fluent API support)
     */
    public function setMeetingsRelatedByEventOwnerId(Collection $meetingsRelatedByEventOwnerId, ConnectionInterface $con = null)
    {
        /** @var ChildMeeting[] $meetingsRelatedByEventOwnerIdToDelete */
        $meetingsRelatedByEventOwnerIdToDelete = $this->getMeetingsRelatedByEventOwnerId(new Criteria(), $con)->diff($meetingsRelatedByEventOwnerId);


        $this->meetingsRelatedByEventOwnerIdScheduledForDeletion = $meetingsRelatedByEventOwnerIdToDelete;

        foreach ($meetingsRelatedByEventOwnerIdToDelete as $meetingRelatedByEventOwnerIdRemoved) {
            $meetingRelatedByEventOwnerIdRemoved->setEventOwner(null);
        }

        $this->collMeetingsRelatedByEventOwnerId = null;
        foreach ($meetingsRelatedByEventOwnerId as $meetingRelatedByEventOwnerId) {
            $this->addMeetingRelatedByEventOwnerId($meetingRelatedByEventOwnerId);
        }

        $this->collMeetingsRelatedByEventOwnerId = $meetingsRelatedByEventOwnerId;
        $this->collMeetingsRelatedByEventOwnerIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Meeting objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Meeting objects.
     * @throws PropelException
     */
    public function countMeetingsRelatedByEventOwnerId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMeetingsRelatedByEventOwnerIdPartial && !$this->isNew();
        if (null === $this->collMeetingsRelatedByEventOwnerId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMeetingsRelatedByEventOwnerId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMeetingsRelatedByEventOwnerId());
            }

            $query = ChildMeetingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEventOwner($this)
                ->count($con);
        }

        return count($this->collMeetingsRelatedByEventOwnerId);
    }

    /**
     * Method called to associate a ChildMeeting object to this object
     * through the ChildMeeting foreign key attribute.
     *
     * @param  ChildMeeting $l ChildMeeting
     * @return $this|\DataModels\DataModels\MeetingAttendee The current object (for fluent API support)
     */
    public function addMeetingRelatedByEventOwnerId(ChildMeeting $l)
    {
        if ($this->collMeetingsRelatedByEventOwnerId === null) {
            $this->initMeetingsRelatedByEventOwnerId();
            $this->collMeetingsRelatedByEventOwnerIdPartial = true;
        }

        if (!$this->collMeetingsRelatedByEventOwnerId->contains($l)) {
            $this->doAddMeetingRelatedByEventOwnerId($l);

            if ($this->meetingsRelatedByEventOwnerIdScheduledForDeletion and $this->meetingsRelatedByEventOwnerIdScheduledForDeletion->contains($l)) {
                $this->meetingsRelatedByEventOwnerIdScheduledForDeletion->remove($this->meetingsRelatedByEventOwnerIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMeeting $meetingRelatedByEventOwnerId The ChildMeeting object to add.
     */
    protected function doAddMeetingRelatedByEventOwnerId(ChildMeeting $meetingRelatedByEventOwnerId)
    {
        $this->collMeetingsRelatedByEventOwnerId[]= $meetingRelatedByEventOwnerId;
        $meetingRelatedByEventOwnerId->setEventOwner($this);
    }

    /**
     * @param  ChildMeeting $meetingRelatedByEventOwnerId The ChildMeeting object to remove.
     * @return $this|ChildMeetingAttendee The current object (for fluent API support)
     */
    public function removeMeetingRelatedByEventOwnerId(ChildMeeting $meetingRelatedByEventOwnerId)
    {
        if ($this->getMeetingsRelatedByEventOwnerId()->contains($meetingRelatedByEventOwnerId)) {
            $pos = $this->collMeetingsRelatedByEventOwnerId->search($meetingRelatedByEventOwnerId);
            $this->collMeetingsRelatedByEventOwnerId->remove($pos);
            if (null === $this->meetingsRelatedByEventOwnerIdScheduledForDeletion) {
                $this->meetingsRelatedByEventOwnerIdScheduledForDeletion = clone $this->collMeetingsRelatedByEventOwnerId;
                $this->meetingsRelatedByEventOwnerIdScheduledForDeletion->clear();
            }
            $this->meetingsRelatedByEventOwnerIdScheduledForDeletion[]= $meetingRelatedByEventOwnerId;
            $meetingRelatedByEventOwnerId->setEventOwner(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MeetingAttendee is new, it will return
     * an empty collection; or if this MeetingAttendee has previously
     * been saved, it will retrieve related MeetingsRelatedByEventOwnerId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MeetingAttendee.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMeeting[] List of ChildMeeting objects
     */
    public function getMeetingsRelatedByEventOwnerIdJoinClientCalendarUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMeetingQuery::create(null, $criteria);
        $query->joinWith('ClientCalendarUser', $joinBehavior);

        return $this->getMeetingsRelatedByEventOwnerId($query, $con);
    }

    /**
     * Clears out the collMeetingsRelatedByEventCreatorId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMeetingsRelatedByEventCreatorId()
     */
    public function clearMeetingsRelatedByEventCreatorId()
    {
        $this->collMeetingsRelatedByEventCreatorId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMeetingsRelatedByEventCreatorId collection loaded partially.
     */
    public function resetPartialMeetingsRelatedByEventCreatorId($v = true)
    {
        $this->collMeetingsRelatedByEventCreatorIdPartial = $v;
    }

    /**
     * Initializes the collMeetingsRelatedByEventCreatorId collection.
     *
     * By default this just sets the collMeetingsRelatedByEventCreatorId collection to an empty array (like clearcollMeetingsRelatedByEventCreatorId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMeetingsRelatedByEventCreatorId($overrideExisting = true)
    {
        if (null !== $this->collMeetingsRelatedByEventCreatorId && !$overrideExisting) {
            return;
        }

        $collectionClassName = MeetingTableMap::getTableMap()->getCollectionClassName();

        $this->collMeetingsRelatedByEventCreatorId = new $collectionClassName;
        $this->collMeetingsRelatedByEventCreatorId->setModel('\DataModels\DataModels\Meeting');
    }

    /**
     * Gets an array of ChildMeeting objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMeetingAttendee is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMeeting[] List of ChildMeeting objects
     * @throws PropelException
     */
    public function getMeetingsRelatedByEventCreatorId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMeetingsRelatedByEventCreatorIdPartial && !$this->isNew();
        if (null === $this->collMeetingsRelatedByEventCreatorId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMeetingsRelatedByEventCreatorId) {
                // return empty collection
                $this->initMeetingsRelatedByEventCreatorId();
            } else {
                $collMeetingsRelatedByEventCreatorId = ChildMeetingQuery::create(null, $criteria)
                    ->filterByEventCreator($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMeetingsRelatedByEventCreatorIdPartial && count($collMeetingsRelatedByEventCreatorId)) {
                        $this->initMeetingsRelatedByEventCreatorId(false);

                        foreach ($collMeetingsRelatedByEventCreatorId as $obj) {
                            if (false == $this->collMeetingsRelatedByEventCreatorId->contains($obj)) {
                                $this->collMeetingsRelatedByEventCreatorId->append($obj);
                            }
                        }

                        $this->collMeetingsRelatedByEventCreatorIdPartial = true;
                    }

                    return $collMeetingsRelatedByEventCreatorId;
                }

                if ($partial && $this->collMeetingsRelatedByEventCreatorId) {
                    foreach ($this->collMeetingsRelatedByEventCreatorId as $obj) {
                        if ($obj->isNew()) {
                            $collMeetingsRelatedByEventCreatorId[] = $obj;
                        }
                    }
                }

                $this->collMeetingsRelatedByEventCreatorId = $collMeetingsRelatedByEventCreatorId;
                $this->collMeetingsRelatedByEventCreatorIdPartial = false;
            }
        }

        return $this->collMeetingsRelatedByEventCreatorId;
    }

    /**
     * Sets a collection of ChildMeeting objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $meetingsRelatedByEventCreatorId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMeetingAttendee The current object (for fluent API support)
     */
    public function setMeetingsRelatedByEventCreatorId(Collection $meetingsRelatedByEventCreatorId, ConnectionInterface $con = null)
    {
        /** @var ChildMeeting[] $meetingsRelatedByEventCreatorIdToDelete */
        $meetingsRelatedByEventCreatorIdToDelete = $this->getMeetingsRelatedByEventCreatorId(new Criteria(), $con)->diff($meetingsRelatedByEventCreatorId);


        $this->meetingsRelatedByEventCreatorIdScheduledForDeletion = $meetingsRelatedByEventCreatorIdToDelete;

        foreach ($meetingsRelatedByEventCreatorIdToDelete as $meetingRelatedByEventCreatorIdRemoved) {
            $meetingRelatedByEventCreatorIdRemoved->setEventCreator(null);
        }

        $this->collMeetingsRelatedByEventCreatorId = null;
        foreach ($meetingsRelatedByEventCreatorId as $meetingRelatedByEventCreatorId) {
            $this->addMeetingRelatedByEventCreatorId($meetingRelatedByEventCreatorId);
        }

        $this->collMeetingsRelatedByEventCreatorId = $meetingsRelatedByEventCreatorId;
        $this->collMeetingsRelatedByEventCreatorIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Meeting objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Meeting objects.
     * @throws PropelException
     */
    public function countMeetingsRelatedByEventCreatorId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMeetingsRelatedByEventCreatorIdPartial && !$this->isNew();
        if (null === $this->collMeetingsRelatedByEventCreatorId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMeetingsRelatedByEventCreatorId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMeetingsRelatedByEventCreatorId());
            }

            $query = ChildMeetingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEventCreator($this)
                ->count($con);
        }

        return count($this->collMeetingsRelatedByEventCreatorId);
    }

    /**
     * Method called to associate a ChildMeeting object to this object
     * through the ChildMeeting foreign key attribute.
     *
     * @param  ChildMeeting $l ChildMeeting
     * @return $this|\DataModels\DataModels\MeetingAttendee The current object (for fluent API support)
     */
    public function addMeetingRelatedByEventCreatorId(ChildMeeting $l)
    {
        if ($this->collMeetingsRelatedByEventCreatorId === null) {
            $this->initMeetingsRelatedByEventCreatorId();
            $this->collMeetingsRelatedByEventCreatorIdPartial = true;
        }

        if (!$this->collMeetingsRelatedByEventCreatorId->contains($l)) {
            $this->doAddMeetingRelatedByEventCreatorId($l);

            if ($this->meetingsRelatedByEventCreatorIdScheduledForDeletion and $this->meetingsRelatedByEventCreatorIdScheduledForDeletion->contains($l)) {
                $this->meetingsRelatedByEventCreatorIdScheduledForDeletion->remove($this->meetingsRelatedByEventCreatorIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMeeting $meetingRelatedByEventCreatorId The ChildMeeting object to add.
     */
    protected function doAddMeetingRelatedByEventCreatorId(ChildMeeting $meetingRelatedByEventCreatorId)
    {
        $this->collMeetingsRelatedByEventCreatorId[]= $meetingRelatedByEventCreatorId;
        $meetingRelatedByEventCreatorId->setEventCreator($this);
    }

    /**
     * @param  ChildMeeting $meetingRelatedByEventCreatorId The ChildMeeting object to remove.
     * @return $this|ChildMeetingAttendee The current object (for fluent API support)
     */
    public function removeMeetingRelatedByEventCreatorId(ChildMeeting $meetingRelatedByEventCreatorId)
    {
        if ($this->getMeetingsRelatedByEventCreatorId()->contains($meetingRelatedByEventCreatorId)) {
            $pos = $this->collMeetingsRelatedByEventCreatorId->search($meetingRelatedByEventCreatorId);
            $this->collMeetingsRelatedByEventCreatorId->remove($pos);
            if (null === $this->meetingsRelatedByEventCreatorIdScheduledForDeletion) {
                $this->meetingsRelatedByEventCreatorIdScheduledForDeletion = clone $this->collMeetingsRelatedByEventCreatorId;
                $this->meetingsRelatedByEventCreatorIdScheduledForDeletion->clear();
            }
            $this->meetingsRelatedByEventCreatorIdScheduledForDeletion[]= $meetingRelatedByEventCreatorId;
            $meetingRelatedByEventCreatorId->setEventCreator(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MeetingAttendee is new, it will return
     * an empty collection; or if this MeetingAttendee has previously
     * been saved, it will retrieve related MeetingsRelatedByEventCreatorId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MeetingAttendee.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMeeting[] List of ChildMeeting objects
     */
    public function getMeetingsRelatedByEventCreatorIdJoinClientCalendarUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMeetingQuery::create(null, $criteria);
        $query->joinWith('ClientCalendarUser', $joinBehavior);

        return $this->getMeetingsRelatedByEventCreatorId($query, $con);
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
     * If this ChildMeetingAttendee is new, it will return
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
                    ->filterByMeetingAttendee($this)
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
     * @return $this|ChildMeetingAttendee The current object (for fluent API support)
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
            $meetingHasAttendeeRemoved->setMeetingAttendee(null);
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
                ->filterByMeetingAttendee($this)
                ->count($con);
        }

        return count($this->collMeetingHasAttendees);
    }

    /**
     * Method called to associate a ChildMeetingHasAttendee object to this object
     * through the ChildMeetingHasAttendee foreign key attribute.
     *
     * @param  ChildMeetingHasAttendee $l ChildMeetingHasAttendee
     * @return $this|\DataModels\DataModels\MeetingAttendee The current object (for fluent API support)
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
        $meetingHasAttendee->setMeetingAttendee($this);
    }

    /**
     * @param  ChildMeetingHasAttendee $meetingHasAttendee The ChildMeetingHasAttendee object to remove.
     * @return $this|ChildMeetingAttendee The current object (for fluent API support)
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
            $meetingHasAttendee->setMeetingAttendee(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this MeetingAttendee is new, it will return
     * an empty collection; or if this MeetingAttendee has previously
     * been saved, it will retrieve related MeetingHasAttendees from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in MeetingAttendee.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMeetingHasAttendee[] List of ChildMeetingHasAttendee objects
     */
    public function getMeetingHasAttendeesJoinMeeting(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMeetingHasAttendeeQuery::create(null, $criteria);
        $query->joinWith('Meeting', $joinBehavior);

        return $this->getMeetingHasAttendees($query, $con);
    }

    /**
     * Gets a single ChildContact object, which is related to this object by a one-to-one relationship.
     *
     * @param  ConnectionInterface $con optional connection object
     * @return ChildContact
     * @throws PropelException
     */
    public function getContact(ConnectionInterface $con = null)
    {

        if ($this->singleContact === null && !$this->isNew()) {
            $this->singleContact = ChildContactQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleContact;
    }

    /**
     * Sets a single ChildContact object as related to this object by a one-to-one relationship.
     *
     * @param  ChildContact $v ChildContact
     * @return $this|\DataModels\DataModels\MeetingAttendee The current object (for fluent API support)
     * @throws PropelException
     */
    public function setContact(ChildContact $v = null)
    {
        $this->singleContact = $v;

        // Make sure that that the passed-in ChildContact isn't already associated with this object
        if ($v !== null && $v->getMeetingAttendee(null, false) === null) {
            $v->setMeetingAttendee($this);
        }

        return $this;
    }

    /**
     * Gets a single ChildClientCalendarUser object, which is related to this object by a one-to-one relationship.
     *
     * @param  ConnectionInterface $con optional connection object
     * @return ChildClientCalendarUser
     * @throws PropelException
     */
    public function getClientCalendarUser(ConnectionInterface $con = null)
    {

        if ($this->singleClientCalendarUser === null && !$this->isNew()) {
            $this->singleClientCalendarUser = ChildClientCalendarUserQuery::create()->findPk($this->getPrimaryKey(), $con);
        }

        return $this->singleClientCalendarUser;
    }

    /**
     * Sets a single ChildClientCalendarUser object as related to this object by a one-to-one relationship.
     *
     * @param  ChildClientCalendarUser $v ChildClientCalendarUser
     * @return $this|\DataModels\DataModels\MeetingAttendee The current object (for fluent API support)
     * @throws PropelException
     */
    public function setClientCalendarUser(ChildClientCalendarUser $v = null)
    {
        $this->singleClientCalendarUser = $v;

        // Make sure that that the passed-in ChildClientCalendarUser isn't already associated with this object
        if ($v !== null && $v->getMeetingAttendee(null, false) === null) {
            $v->setMeetingAttendee($this);
        }

        return $this;
    }

    /**
     * Clears out the collMeetings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMeetings()
     */
    public function clearMeetings()
    {
        $this->collMeetings = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collMeetings crossRef collection.
     *
     * By default this just sets the collMeetings collection to an empty collection (like clearMeetings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initMeetings()
    {
        $collectionClassName = MeetingHasAttendeeTableMap::getTableMap()->getCollectionClassName();

        $this->collMeetings = new $collectionClassName;
        $this->collMeetingsPartial = true;
        $this->collMeetings->setModel('\DataModels\DataModels\Meeting');
    }

    /**
     * Checks if the collMeetings collection is loaded.
     *
     * @return bool
     */
    public function isMeetingsLoaded()
    {
        return null !== $this->collMeetings;
    }

    /**
     * Gets a collection of ChildMeeting objects related by a many-to-many relationship
     * to the current object by way of the meeting_has_attendee cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMeetingAttendee is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildMeeting[] List of ChildMeeting objects
     */
    public function getMeetings(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMeetingsPartial && !$this->isNew();
        if (null === $this->collMeetings || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collMeetings) {
                    $this->initMeetings();
                }
            } else {

                $query = ChildMeetingQuery::create(null, $criteria)
                    ->filterByMeetingAttendee($this);
                $collMeetings = $query->find($con);
                if (null !== $criteria) {
                    return $collMeetings;
                }

                if ($partial && $this->collMeetings) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collMeetings as $obj) {
                        if (!$collMeetings->contains($obj)) {
                            $collMeetings[] = $obj;
                        }
                    }
                }

                $this->collMeetings = $collMeetings;
                $this->collMeetingsPartial = false;
            }
        }

        return $this->collMeetings;
    }

    /**
     * Sets a collection of Meeting objects related by a many-to-many relationship
     * to the current object by way of the meeting_has_attendee cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $meetings A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildMeetingAttendee The current object (for fluent API support)
     */
    public function setMeetings(Collection $meetings, ConnectionInterface $con = null)
    {
        $this->clearMeetings();
        $currentMeetings = $this->getMeetings();

        $meetingsScheduledForDeletion = $currentMeetings->diff($meetings);

        foreach ($meetingsScheduledForDeletion as $toDelete) {
            $this->removeMeeting($toDelete);
        }

        foreach ($meetings as $meeting) {
            if (!$currentMeetings->contains($meeting)) {
                $this->doAddMeeting($meeting);
            }
        }

        $this->collMeetingsPartial = false;
        $this->collMeetings = $meetings;

        return $this;
    }

    /**
     * Gets the number of Meeting objects related by a many-to-many relationship
     * to the current object by way of the meeting_has_attendee cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related Meeting objects
     */
    public function countMeetings(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMeetingsPartial && !$this->isNew();
        if (null === $this->collMeetings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMeetings) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getMeetings());
                }

                $query = ChildMeetingQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByMeetingAttendee($this)
                    ->count($con);
            }
        } else {
            return count($this->collMeetings);
        }
    }

    /**
     * Associate a ChildMeeting to this object
     * through the meeting_has_attendee cross reference table.
     *
     * @param ChildMeeting $meeting
     * @return ChildMeetingAttendee The current object (for fluent API support)
     */
    public function addMeeting(ChildMeeting $meeting)
    {
        if ($this->collMeetings === null) {
            $this->initMeetings();
        }

        if (!$this->getMeetings()->contains($meeting)) {
            // only add it if the **same** object is not already associated
            $this->collMeetings->push($meeting);
            $this->doAddMeeting($meeting);
        }

        return $this;
    }

    /**
     *
     * @param ChildMeeting $meeting
     */
    protected function doAddMeeting(ChildMeeting $meeting)
    {
        $meetingHasAttendee = new ChildMeetingHasAttendee();

        $meetingHasAttendee->setMeeting($meeting);

        $meetingHasAttendee->setMeetingAttendee($this);

        $this->addMeetingHasAttendee($meetingHasAttendee);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$meeting->isMeetingAttendeesLoaded()) {
            $meeting->initMeetingAttendees();
            $meeting->getMeetingAttendees()->push($this);
        } elseif (!$meeting->getMeetingAttendees()->contains($this)) {
            $meeting->getMeetingAttendees()->push($this);
        }

    }

    /**
     * Remove meeting of this object
     * through the meeting_has_attendee cross reference table.
     *
     * @param ChildMeeting $meeting
     * @return ChildMeetingAttendee The current object (for fluent API support)
     */
    public function removeMeeting(ChildMeeting $meeting)
    {
        if ($this->getMeetings()->contains($meeting)) {
            $meetingHasAttendee = new ChildMeetingHasAttendee();
            $meetingHasAttendee->setMeeting($meeting);
            if ($meeting->isMeetingAttendeesLoaded()) {
                //remove the back reference if available
                $meeting->getMeetingAttendees()->removeObject($this);
            }

            $meetingHasAttendee->setMeetingAttendee($this);
            $this->removeMeetingHasAttendee(clone $meetingHasAttendee);
            $meetingHasAttendee->clear();

            $this->collMeetings->remove($this->collMeetings->search($meeting));

            if (null === $this->meetingsScheduledForDeletion) {
                $this->meetingsScheduledForDeletion = clone $this->collMeetings;
                $this->meetingsScheduledForDeletion->clear();
            }

            $this->meetingsScheduledForDeletion->push($meeting);
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
        $this->id = null;
        $this->descendant_class = null;
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
            if ($this->collMeetingsRelatedByEventOwnerId) {
                foreach ($this->collMeetingsRelatedByEventOwnerId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMeetingsRelatedByEventCreatorId) {
                foreach ($this->collMeetingsRelatedByEventCreatorId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMeetingHasAttendees) {
                foreach ($this->collMeetingHasAttendees as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->singleContact) {
                $this->singleContact->clearAllReferences($deep);
            }
            if ($this->singleClientCalendarUser) {
                $this->singleClientCalendarUser->clearAllReferences($deep);
            }
            if ($this->collMeetings) {
                foreach ($this->collMeetings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMeetingsRelatedByEventOwnerId = null;
        $this->collMeetingsRelatedByEventCreatorId = null;
        $this->collMeetingHasAttendees = null;
        $this->singleContact = null;
        $this->singleClientCalendarUser = null;
        $this->collMeetings = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MeetingAttendeeTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildMeetingAttendee The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[MeetingAttendeeTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    // concrete_inheritance_parent behavior

    /**
     * Whether or not this object is the parent of a child object
     *
     * @return    bool
     */
    public function hasChildObject()
    {
        return $this->getDescendantClass() !== null;
    }

    /**
     * Get the child object of this object
     *
     * @return    mixed
     */
    public function getChildObject()
    {
        if (!$this->hasChildObject()) {
            return null;
        }
        $childObjectClass = $this->getDescendantClass();
        $childObject = PropelQuery::from($childObjectClass)->findPk($this->getPrimaryKey());

        return $childObject->hasChildObject() ? $childObject->getChildObject() : $childObject;
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
