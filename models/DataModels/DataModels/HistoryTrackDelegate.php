<?php

namespace DataModels\DataModels;

class HistoryTrackDelegate {
    protected $historyTracker = array();

    public function __construct(IHistoryTrackAbility $historyObject) {
        $this->historyTracker = $historyObject;
    }

    public function isThereAnyUpdate(array $SFDCResponse, $sfdcHistoryList) {
        if($sfdcHistoryList['totalSize'] < 1) {
            return false;
        }

        $accountHistLatest = $sfdcHistoryList['records'][0];

        if( strtotime($accountHistLatest['CreatedDate']) <= $this->historyTracker->getCreatedAt()->getTimeStamp() ) {
            return false;
        }

        foreach($this->historyTracker->getHistoryTrack() as $objField => $responseField) {
            if($this->historyTracker->{$objField} != $SFDCResponse[$responseField]) {
                return true;
            }
        }

        return false;
    }
}