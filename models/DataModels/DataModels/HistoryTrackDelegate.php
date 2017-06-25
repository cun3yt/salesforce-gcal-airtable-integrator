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
            $getFnName = $this->snakeCaseToGetFunctionName($objField);

            if(call_user_func($this->historyTracker, $getFnName) != $SFDCResponse[$responseField]) {
                return true;
            }
        }

        return false;
    }

    private function snakeCaseToGetFunctionName($value) {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
    }
}