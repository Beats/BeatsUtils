<?php
namespace Beats\Utils;

class UTC {

  const FORMAT_DATE      = 'o-m-d';
  const FORMAT_TIME      = 'H:i:s';
  const FORMAT_TIMESTAMP = 'o-m-d\TH:i:s';

  /**
   * @param mixed|null $zone
   * @return \DateTimeZone
   * @throws UTCException
   */
  static public function createTimeZone($zone = 'UTC') {
    if ($zone instanceof \DateTimeZone) {
      return $zone;
    } elseif (is_numeric($zone)) { // GMT offset in hours, with DST in affect
      $zone = timezone_name_from_abbr('', $zone * 3600, 1);
      if (empty($zone)) {
        throw new UTCException("Invalid timezone offset: $zone");
      }
    }
    try {
      return new \DateTimeZone($zone);
    } catch (\Exception $ex) {
      throw new UTCException("Invalid timezone format: $zone", 0, $ex);
    }
  }

  /**
   * Creates a \DateTime object based on given value;
   *
   * If the zone is not set, the value will be parsed as an UTC time.
   *
   * @param mixed|null $time
   * @param mixed|null $zone
   * @return \DateTime
   * @throws UTCException
   */
  static public function createDateTime($time = null, $zone = null) {
    static $utc;
    if (empty($utc)) {
      $utc = self::createTimeZone('UTC');
    }
    if (empty($zone)) {
      $zone = $utc;
    } else {
      $zone = self::createTimeZone($zone);
    }
    if ($time instanceof \DateTime) {
      return $time->setTimezone($zone);
    } elseif (is_null($time)) {
      $time = 'now';
    } elseif (is_numeric($time)) {
      $time = '@' . $time;
    } elseif (!is_string($time)) {
      throw new UTCException("Invalid time format: " . gettype($time));
    }
    try {
      return new \DateTime($time, $zone);
    } catch (\Exception $ex) {
      throw new UTCException("Invalid time format: $time", 0, $ex);
    }
  }

  /**
   * Created a string representation of the given time according to the specified format
   *
   * @param \DateTime $time
   * @param string $format
   * @return string
   * @throws UTCException
   */
  static private function _format(\DateTime $time, $format) {
    $text = $time->format($format);
    if (empty($text) || !is_string($format)) {
      throw new UTCException("Invalid format given: $format");
    }
    return $text;
  }

  /********************************************************************************************************************/

  /**
   * Normalize the timezone parameter to a PHP friendly string
   *
   * @param mixed|null $zone
   * @return string
   * @param mixed|null $zone
   */
  static public function normalizeTimeZone($zone) {
    return self::createTimeZone($zone)->getName();
  }

  /**
   * Returns the given time as a UNIX timestamp
   * @param mixed|null $time
   * @param mixed|null $zone
   * @return int
   * @throws UTCException
   */
  static public function toUNIX($time = null, $zone = null) {
    return self::createDateTime($time, $zone)->getTimestamp();
  }

  /**
   * Creates a \DateTime object based on the given value, adjusted to the destination Timezone parameter
   * @param mixed|null $time
   * @param mixed|null $srcZone
   * @param mixed|string $dstZone
   * @return \DateTime
   */
  static public function toTimezone($time = null, $srcZone = null, $dstZone = 'UTC') {
    return self::createDateTime($time, $srcZone)->setTimezone(self::createTimeZone($dstZone));
  }

  /********************************************************************************************************************/

  /**
   * Returns the given time formatted according to the parameters
   * @param string $format
   * @param mixed|null $time
   * @param mixed|null $zone
   * @return string
   * @throws UTCException
   */
  static public function toFormat($format, $time = null, $zone = null) {
    return self::_format(self::createDateTime($time, $zone), $format);
  }

  /********************************************************************************************************************/

  /**
   * Returns the date portion of the given time in ISO 8601 format YYYY-MM-DD
   * @param mixed|null $time
   * @param mixed|null $zone
   * @return string
   * @throws UTCException
   */
  static public function toUTCDate($time = null, $zone = null) {
    return self::_format(self::toTimezone($time, $zone), self::FORMAT_DATE);
  }

  /**
   * Returns the date portion of the given time in ISO 8601 format hh:mm:ss
   * @param mixed|null $time
   * @param mixed|null $zone
   * @return string
   * @throws UTCException
   */
  static public function toUTCTime($time = null, $zone = null) {
    return self::_format(self::toTimezone($time, $zone), self::FORMAT_TIME);
  }

  /**
   * Returns the date portion of the given time in ISO 8601 format YYYY-MM-DDThh:mm:ss
   * @param mixed|null $time
   * @param mixed|null $zone
   * @return string
   * @throws UTCException
   */
  static public function toUTCTimestamp($time = null, $zone = null) {
    return self::_format(self::toTimezone($time, $zone), self::FORMAT_TIMESTAMP);
  }

  /********************************************************************************************************************/

}
