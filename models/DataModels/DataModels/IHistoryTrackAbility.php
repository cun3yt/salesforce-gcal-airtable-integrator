<?
namespace DataModels\DataModels;

interface IHistoryTrackAbility {
    public function isThereAnyUpdate(array $SFDCResponse, $sfdcHistoryList);
    public function getCreatedAt();
    public function getHistoryTrack();
}
