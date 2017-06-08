<?php

namespace DataModels\DataModels;

use DataModels\DataModels\Account as Account;
use DataModels\DataModels\OpportunityQuery as OpportunityQuery;
use DataModels\DataModels\Base\Opportunity as BaseOpportunity;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Skeleton subclass for representing a row from the 'opportunity' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Opportunity extends BaseOpportunity
{
    public static function findByAccountAndSFDCId(Account $account, $sfdcOpptyId) {
        $q = new OpportunityQuery();
        return $q->filterByAccount($account)->findOneBySFDCId($sfdcOpptyId);
    }

    /**
     * @return OpportunityHistory
     */
    public function getLatestOpportunityHistory() {
        $q = new OpportunityHistoryQuery();
        return $q->filterByOpportunity($this)
            ->orderByCreatedAt(Criteria::DESC)
            ->findOne();
    }

    /**
     * @param Account $account
     * @param array $sfdcOppty
     * @return Opportunity
     */
    public static function createOpportunity(Account $account, $sfdcOppty) {
        $opportunity = new Opportunity();
        $opportunity
            ->setAccount($account)
            ->setSFDCId($sfdcOppty['Id'])
            ->save();
        return $opportunity;
    }
}
