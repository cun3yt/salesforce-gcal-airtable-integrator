<?php

namespace DataModels\DataModels;

use DataModels\DataModels\Base\CustomerContact as BaseCustomerContact;
use DataModels\DataModels\CustomerContactQuery as CustomerContactQuery;

/**
 * Skeleton subclass for representing a row from the 'customer_contact' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class CustomerContact extends BaseCustomerContact //implements IMeetingAttendee
{
    const MEETING_ATTENDEE_TYPE = 'CustomerContact';

    public static function getType() {
        return self::MEETING_ATTENDEE_TYPE;
    }

    public static function findInstance(int $id) {
        $q = new CustomerContactQuery();
        return $q->findById($id);
    }

    /**
     * @param Customer $customer
     * @param $emailAddress
     * @return CustomerContact|null
     */
    static function findByCustomerAndEmailAddress(Customer $customer, $emailAddress) {
        $ccq = new CustomerContactQuery();
        $customerContactSet = $ccq->filterByCustomer($customer)->filterByEmail($emailAddress)->find();

        if($customerContactSet->count() <= 0) { return NULL; }

        if($customerContactSet->count() >= 2) {
            trigger_error(__FUNCTION__ . " fetches more than 1 CustomerContact", E_USER_WARNING);
        }

        return $customerContactSet[0];
    }

    /**
     * @return MeetingAttendee|null
     */
    function getMeetingAttendee() {
        $q = new MeetingAttendeeQuery();
        $attendeeSet = $q->filterByRefType(self::getType())->filterByRefId($this->id)->find();

        if($attendeeSet->count() <= 0) { return NULL; }

        if($attendeeSet->count() >= 2) {
            trigger_error(__FUNCTION__ . " fetches more than 1 MeetingAttendee", E_USER_WARNING);
        }

        return $attendeeSet[0];
    }
}
