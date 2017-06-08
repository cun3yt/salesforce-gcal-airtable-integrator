<?php

namespace DataModels\DataModels;

use DataModels\DataModels\Base\ClientCalendarUser as BaseClientCalendarUser;
use DataModels\DataModels\ClientCalendarUserQuery as ClientCalendarUserQuery;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'client_calendar_user' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class ClientCalendarUser extends BaseClientCalendarUser //implements IMeetingAttendee
{
    const MEETING_ATTENDEE_TYPE = 'ClientCalendarUser';

    public static function getType() {
        return self::MEETING_ATTENDEE_TYPE;
    }

    public static function findInstance(int $id) {
        $q = new ClientCalendarUserQuery();
        return $q->findById($id);
    }

    /**
     * @param Client $client
     * @param $emailAddress
     * @return ClientCalendarUser|null
     */
    static function findByClientAndCalendar(Client $client, $emailAddress) {
        $ccq = new ClientCalendarUserQuery();
        $clientCalendarUserSet = $ccq->filterByClient($client)->filterByEmail($emailAddress)->find();

        if($clientCalendarUserSet->count() <= 0) { return NULL; }

        if($clientCalendarUserSet->count() >= 2) {
            trigger_error(__FUNCTION__ . " fetches more than 1 ClientCalendarUser", E_USER_WARNING);
        }

        return $clientCalendarUserSet[0];
    }

    /**
     * @param Client $client
     * @param String $emailAddress
     * @return ClientCalendarUser
     */
    static function findOrCreateByClientAndCalendar(Client $client, $emailAddress) {
        $clientCalenderUser = self::findByClientAndCalendar($client, $emailAddress);

        if($clientCalenderUser) {
            return $clientCalenderUser;
        }

        $clientCalenderUser = new ClientCalendarUser();
        $clientCalenderUser->setClient($client)->setEmail($emailAddress)->save();
        return $clientCalenderUser;
    }

    /**
     * @param ConnectionInterface|null $con
     * @return null|ClientCalendarUser
     */
    public function getMeetingAttendee(ConnectionInterface $con = null) {
        $q = new MeetingAttendeeQuery();

        $attendeeSet = $q->findById($this->id);

        if($attendeeSet->count() <= 0) { return NULL; }

        if($attendeeSet->count() >= 2) {
            trigger_error(__FUNCTION__ . " fetches more than 1 MeetingAttendee", E_USER_WARNING);
        }

        return $attendeeSet[0];
    }
}
