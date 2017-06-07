<?php

namespace DataModels\DataModels;

use DataModels\DataModels\Opportunity as Opportunity;
use DataModels\DataModels\Base\OpportunityHistory as BaseOpportunityHistory;
use DataModels\DataModels\IHistoryTrackAbility as IHistoryTrackAbility;

/**
 * Skeleton subclass for representing a row from the 'opportunity_history' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class OpportunityHistory extends BaseOpportunityHistory implements IHistoryTrackAbility
{
    /**
     * Association of object attributes to Salesforce API response array items.
     * These items are the ones on which the changes we care.
     *
     * @var array
     */
    private $historyTrack = array(
        'account_sfdc_id' => 'AccountId',
        'amount' => 'Amount',
        'close_date' => 'CloseDate',
        'last_modified_by' => 'LastModifiedById',
        'next_step' => 'NextStep',
        'name' => 'Name',
        'owner_id' => 'OwnerId',
        'stage' => 'StageName',
        'type' => 'Type'
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

    /**
     * @param Opportunity $opportunity
     * @param array $SFDCResponse
     * @return OpportunityHistory
     */
    public static function createOpportunityHistory(Opportunity $opportunity, $SFDCResponse) {
        $opportunityHistory = new OpportunityHistory();
        $opportunityHistory->setOpportunity($opportunity)
            ->setAmount($SFDCResponse['Amount'])
            ->setCloseDate($SFDCResponse['CloseDate'])
            ->setAccountName($SFDCResponse['AccountName']);

        // @todo rest of the set functions will be here!

        return $opportunityHistory;
    }
}
