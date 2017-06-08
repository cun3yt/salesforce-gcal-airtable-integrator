<?php

namespace DataModels\DataModels;

use DataModels\DataModels\Base\Meeting as BaseMeeting;

/**
 * Skeleton subclass for representing a row from the 'meeting' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Meeting extends BaseMeeting
{
    const PROCESS_STATE_NOT_PROCESSED = "not processed";
    const PROCESS_STATE_PROCESSED = "processed";
}
