<?php

namespace DataModels\DataModels;

use DataModels\DataModels\Base\Account as BaseAccount;
use Propel\Runtime\ActiveQuery\Criteria;
use DataModels\DataModels\AccountHistoryQuery as AccountHistoryQuery;

/**
 * Skeleton subclass for representing a row from the 'account' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Account extends BaseAccount
{
    /**
     * @return AccountHistory
     */
    public function getLatestAccountHistory() {
        $q = new AccountHistoryQuery();
        return $q->filterByAccount($this)->orderByCreatedAt(Criteria::DESC)->findOne();
    }
}
