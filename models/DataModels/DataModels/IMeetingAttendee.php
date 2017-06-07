<?
namespace DataModels\DataModels;

/**
 * Interface IMeetingAttendee
 * @package DataModels\DataModels
 *
 * @todo Kill if not necessary!
 */
interface IMeetingAttendee {
    public static function getType();
    public static function findInstance(int $id);
}