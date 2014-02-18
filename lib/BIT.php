<?php
namespace Beats\Utils;

class BIT {

  /**
   * Returns true only if the given value is a flag (a single bit)
   * @param int $value
   * @return bool
   */
  static public function isFlag($value) {
    return (is_int($value) && $value && (($value & (~$value + 1)) == $value));
  }

  /**
   * Throws an exception if the given value is not a flat
   * @see {BIT::isFlag}
   * @param int $value
   * @return int
   * @throws BITException
   */
  static public function flag($value) {
    if (self::isFlag($value)) {
      return $value;
    }
    throw new BITException("The given flag is invalid: $value");
  }


  /**
   * Returns a masked valued
   * @param int $value
   * @param int $mask
   * @return int
   */
  static public function mask($value, $mask) {
    return $mask & $value;
  }

  /**
   * Returns TRUE only if the value's ON flags are matching the mask's flags
   * @param int $value
   * @param int $mask
   * @return bool
   */
  static public function isMasked($value, $mask) {
    return self::mask($value, $mask) == $mask;
  }


  /**
   * Turns ON or OFF a given mask on the given value
   * @param int $value
   * @param int $mask
   * @param bool $on
   */
  static public function add(&$value, $mask, $on = true) {
    return $on
      ? $value |= $mask
      : $value &= ~$mask;
  }

  /**
   * Toggles the given mask flags on the given value ON or OFF
   * @param int $value
   * @param int $mask
   * @return int
   * @throws BITException
   */
  static public function toggle(&$value, $mask) {
    return $value ^= $mask;
  }

  
  /**
   * Turns ON or OFF a given flag on the given value
   * If the $on parameter is set to null the flag is toggled
   * @param int $value
   * @param int $flag
   * @param null|bool $on
   * @return int
   * @throws BITException
   */
  static public function set(&$value, $flag, $on = null) {
    return is_null($on)
      ? self::toggle($value, self::flag($flag))
      : self::add($value, self::flag($flag), $on);
  }

}
