<?php

namespace DataModels\DataModels;

use DataModels\DataModels\Base\CustomerContact as BaseCustomerContact;

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
class CustomerContact extends BaseCustomerContact
{
    static function getMeetingAttendeeRefType() {
        return 'customer_contact';
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
        $attendeeSet = $q->filterByRefType(self::getMeetingAttendeeRefType())->filterByRefId($this->id);

        if($attendeeSet->count() <= 0) { return NULL; }

        if($attendeeSet->count() >= 2) {
            trigger_error(__FUNCTION__ . " fetches more than 1 MeetingAttendee", E_USER_WARNING);
        }

        return $attendeeSet[0];
    }
}
