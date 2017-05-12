<?php

namespace DataModels\DataModels;

use DataModels\DataModels\Base\Contact as BaseContact;
use DataModels\DataModels\IMeetingAttendee as IMeetingAttendee;

/**
 * Skeleton subclass for representing a row from the 'contact' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Contact extends BaseContact implements IMeetingAttendee
{
    const MEETING_ATTENDEE_TYPE = 'Contact';

    public static function getType() {
        return self::MEETING_ATTENDEE_TYPE;
    }

    public static function findInstance(int $id) {
        $q = new ContactQuery();
        return $q->findById($id);
    }
}
