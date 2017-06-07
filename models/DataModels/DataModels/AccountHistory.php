<?php

namespace DataModels\DataModels;

use DataModels\DataModels\Base\AccountHistory as BaseAccountHistory;
use DataModels\DataModels\IHistoryTrackAbility as IHistoryTrackAbility;

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
class AccountHistory extends BaseAccountHistory implements IHistoryTrackAbility
{
    /**
     * Association of object attributes to Salesforce API response array items.
     * These items are the ones on which the changes we care.
     *
     * @var array
     */
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

    /**
     * @var HistoryTrackDelegate HistoryTrackDelegate
     */
    private $historyTrackDelegate = null;

    public function __construct() {
        parent::__construct();
        $this->historyTrackDelegate = new HistoryTrackDelegate($this);
    }

    public function getHistoryTrack() {
        return $this->historyTrack;
    }

    public function isThereAnyUpdate(array $SFDCResponse, $sfdcHistoryList) {
        return $this->historyTrackDelegate->isThereAnyUpdate($SFDCResponse, $sfdcHistoryList);
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
