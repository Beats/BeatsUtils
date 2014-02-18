<?php
use Beats\Utils\UTC;

class UTCTest extends \PHPUnit_Framework_TestCase {

  const TZ = 'Europe/Berlin';

  public function providerZone() {
    return array(
      array(null, false),
      array('Vogsphere/Jeltzville', false),

      array(1, true),
      array('UTC', 'UTC'),
      array(self::TZ, self::TZ),
      array(new DateTimeZone(self::TZ), self::TZ),

    );
  }

  public function providerTime() {
    $times = array(
      array(null, true),
      array(0, 0),
      array('today', 'today'),
      array('years ago', false),
    );
    $sets  = array();
    foreach ($this->providerZone() as $zone) {
      foreach ($times as $time) {
        $args = array($time[0], $zone[0]);
        if ($zone[0] === null) {
          $args[] = $time[1]; // NULL zone is treated by default as UTC
        } else {
          $args[] = $zone[1] === false || $time[1] === false ? false : $time[1];
        }
        $sets[] = $args;
      }
    };
    return $sets;
  }

  public function providerFormat() {
    $formats = array(
      array(null, false),
      array(0, false),

      array('Y-m-d\Th:i:s', true),
      array('d. M Y', true),
    );
    $sets    = array();
    foreach ($this->providerTime() as $time) {
      foreach ($formats as $format) {
        $args   = array($format[0], $time[0], $time[1]);
        $args[] = $format[1] === false || $time[2] === false ? false : $format[1];
        $sets[] = $args;
      }
    };
    return $sets;
  }


  public function assertTimeZone($actual, $expected = true) {
    if ($expected === false) {
      $this->assertEmpty($actual);
    } else {
      $this->assertNotEmpty($actual);
      $this->assertInstanceOf('\DateTimeZone', $actual);
      if (is_string($expected) && $actual instanceof DateTimeZone) {
        $this->assertEquals($actual->getName(), $expected);
      }
    }
  }

  public function assertDateTime($actual, $expected = true) {
    if ($expected === false) {
      $this->assertEmpty($actual);
    } else {
      $this->assertNotEmpty($actual);
      $this->assertInstanceOf('\DateTime', $actual);
      if ($actual instanceof DateTime) {
        if (is_numeric($expected)) {
          $this->assertEquals($actual->getTimestamp(), $expected);
        } elseif (is_string($expected)) {
          $tz = date_default_timezone_get();
          date_default_timezone_set($actual->getTimezone()->getName());
          $this->assertEquals($actual->getTimestamp(), strtotime($expected));
          date_default_timezone_set($tz);
        }
      }
    }
  }


  /**
   * @dataProvider providerZone
   */
  public function testCreateTimeZone($zone, $expected) {
    if ($expected === false) {
      $this->setExpectedException('Beats\Utils\UTCException');
    }
    $this->assertTimeZone(UTC::createTimeZone($zone), $expected);
  }

  /**
   * @dataProvider providerZone
   */
  public function testNormalizeTimeZone($zone, $expected) {
    if ($expected === false) {
      $this->setExpectedException('Beats\Utils\UTCException');
    }
    $actual = UTC::normalizeTimeZone($zone);
    $this->assertRegExp('#\w+(\/\w+(\s\w+)*)?#', $actual);
    if (is_string($expected)) {
      $this->assertEquals($actual, $expected);
    }
  }

  /********************************************************************************************************************/

  /**
   * @dataProvider providerTime
   */
  public function testCreateDateTime($time, $zone, $expected) {
    if ($expected === false) {
      $this->setExpectedException('Beats\Utils\UTCException');
    }
    $this->assertDateTime(UTC::createDateTime($time, $zone), $expected);
  }

  /**
   * @dataProvider providerTime
   */
  public function testToUNIX($time, $zone, $expected) {
    if ($expected === false) {
      $this->setExpectedException('Beats\Utils\UTCException');
    }
    $actual = UTC::toUNIX($time, $zone);
    $this->assertInternalType('integer', $actual);
    if (is_int($expected)) {
      $this->assertEquals($actual, $expected);
    }
  }

  /**
   * @dataProvider providerTime
   */
  public function testToTimezone($time, $zone, $expected) {
    if ($expected === false) {
      $this->setExpectedException('Beats\Utils\UTCException');
    }
    $actual = UTC::toTimezone($time, $zone, 'UTC');
    $this->assertDateTime($actual);
    $this->assertEquals($actual->getTimezone()->getName(), 'UTC');
  }

  /********************************************************************************************************************/

  /**
   * @dataProvider providerFormat
   */
  public function testToFormat($format, $time, $zone, $expected) {
    if ($expected === false) {
      $this->setExpectedException('Beats\Utils\UTCException');
    }
    $time = UTC::toFormat($format, $time, $zone);
    $this->assertInternalType('string', $time);
    if (is_string($expected)) {
      $this->assertEquals($time, $expected);
    }
  }

  /********************************************************************************************************************/

  /**
   * @dataProvider providerTime
   */
  public function testToUTCDate($time, $zone, $expected) {
    if ($expected === false) {
      $this->setExpectedException('Beats\Utils\UTCException');
    }
    $actual = UTC::toUTCDate($time, $zone);
    $this->assertRegExp('#\d{4}-\d{2}-\d{2}#', $actual);
  }

  /**
   * @dataProvider providerTime
   */
  public function testToUTCTime($time, $zone, $expected) {
    if ($expected === false) {
      $this->setExpectedException('Beats\Utils\UTCException');
    }
    $actual = UTC::toUTCTime($time, $zone);
    $this->assertRegExp('#\d{2}:\d{2}:\d{2}#', $actual);
  }

  /**
   * @dataProvider providerTime
   */
  public function testToUTCDateTime($time, $zone, $expected) {
    if ($expected === false) {
      $this->setExpectedException('Beats\Utils\UTCException');
    }
    $actual = UTC::toUTCTimestamp($time, $zone);
    $this->assertRegExp('#\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}#', $actual);
  }

}
