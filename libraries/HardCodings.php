<?
use DataModels\DataModels\Account;
use DataModels\DataModels\AccountHistory;
use DataModels\DataModels\Client;

class HardCodings {
    static function emailDomain15Five() {
        return "15five.com";
    }

    static function get15FiveCustomFields() {
        return array(
            'Account_Status__c' => 'AccountStatus15FiveHack',
            'ARR__c' => 'ARR15FiveHack'
        );
    }

    static function extendAccountHistoryFieldIf15Five(Account $account, AccountHistory $history, $SFDCResponse) {
        if($account->getClient()->getEmailDomain() != self::emailDomain15Five()) {
            return $history;
        }

        $history->setARR15FiveHack($SFDCResponse['ARR__c'])
            ->setAccountStatus15FiveHack($SFDCResponse['Account_Status__c']);

        return $history;
    }

    static function extendSelectSegmentIf15Five($selectSegment, Client $client = NULL) {
        if(!$client || $client->getEmailDomain() != self::emailDomain15Five()) {
            return $selectSegment;
        }

        $extension = implode(', ', array_keys(self::get15FiveCustomFields()));
        return "$selectSegment, {$extension} ";
    }
}