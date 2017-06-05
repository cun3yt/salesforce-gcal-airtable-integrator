<?php

namespace DataModels\DataModels;

use Helpers;
use DataModels\DataModels\Base\AccountHistory as BaseAccountHistory;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Skeleton subclass for representing a row from the 'account_history' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class AccountHistory extends BaseAccountHistory
{
    private $historyTrack = array(
        'billing_street' => 'BillingStreet',
        'billing_city' => 'BillingCity',
        'billing_state' => 'BillingState',
        'billing_country' => 'BillingCountry',
        'billing_latitude' => 'BillingLatitude',
        'billing_longitude' => 'BillingLongitude',
        'name' => 'Name',
        'num_employees' => 'NumberOfEmployees',
        'annual_revenue' => 'AnnualRevenue',
        'owner_id' => 'OwnerId',
        'industry' => 'Industry',
        'type' => 'Type',
        'website' => 'Website'
    );

    public function isThereAnyUpdate(array $SFDCResponse, $sfdcHistoryList) {
        if($sfdcHistoryList['totalSize'] < 1) {
            return false;
        }

        $accountHistLatest = $sfdcHistoryList['records'][0];

        if( strtotime($accountHistLatest['CreatedDate']) <= $this->getCreatedAt()->getTimeStamp() ) {
            return false;
        }

        foreach($this->historyTrack as $objField => $responseField) {
            if($this->{$objField} != $SFDCResponse[$responseField]) {
                return true;
            }
        }

        return false;
    }

    public static function createAccountHistory(Account $account, array $SFDCResponse) {
        $history = new AccountHistory();
        $history
            ->setAccount($account)
            ->setBillingStreet($SFDCResponse['BillingStreet'])
            ->setBillingCity($SFDCResponse['BillingCity'])
            ->setBillingState($SFDCResponse['BillingState'])
            ->setBillingCountry($SFDCResponse['BillingCountry'])
            ->setBillingLatitude($SFDCResponse['BillingLatitude'])
            ->setBillingLongitude($SFDCResponse['BillingLongitude'])
            ->setName($SFDCResponse['Name'])
            ->setNumEmployees($SFDCResponse['NumberOfEmployees'])
            ->setIndustry($SFDCResponse['Industry'])
            ->setAnnualRevenue($SFDCResponse['AnnualRevenue'])
            ->setLastActivityDate($SFDCResponse['LastActivityDate'])
            ->setOwnerId($SFDCResponse['OwnerId'])
            ->setType($SFDCResponse['Type'])
            ->setWebsite($SFDCResponse['Website'])
            ->save();
        return $history;
    }
}
