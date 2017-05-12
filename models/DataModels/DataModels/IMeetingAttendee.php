<?
namespace DataModels\DataModels;

interface IMeetingAttendee {
    public static function getType();
    public static function findInstance(int $id);
}