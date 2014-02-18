<?php

use Beats\Utils\UUID;

class UUIDTest extends \PHPUnit_Framework_TestCase {


  public function providerIsValid() {
    return array(
      array('ff6f8cb0-c57d-11e1-9b21-0800200c9a66', true),
      array('ff6f8cb0-c57d-11e1-0800200c9a66', false),
    );
  }

  public function providerNameNamespace() {
    return array(
      array('{9038cfc2-ba82-4a6f-8470-4a3a8ee997db}', '', true),
      array('9038cfc2-ba82-4a6f-8470-4a3a8ee997db', '', true),
      array('9038cfc2-ba82-4a6f-4a3a8ee997db', '', false),
      array('', '', false),
    );
  }

  public function assertUUID($actual, $expected = true) {
    if (empty($expected)) {
      $this->assertFalse($actual);
    } else {
      $this->assertNotEmpty($actual);
      $this->assertRegExp('#[a-z\d]{8}-[a-z\d]{4}-[a-z\d]{4}-[a-z\d]{4}-[a-z\d]{12}#', $actual);
      if (is_string($expected)) {
        $this->assertEquals($expected, $actual);
      }
    }
  }

  /**
   * @dataProvider providerIsValid
   */

  public function testIsValid($input, $expected) {
    $this->assertEquals(UUID::is_valid($input), $expected);
  }

  public function testV4() {
    $actual1 = UUID::v4();
    $this->assertUUID($actual1);

    $actual2 = UUID::v4();
    $this->assertUUID($actual2);

    $this->assertNotEquals($actual1, $actual2);
  }


  /**
   * @dataProvider providerNameNamespace
   */
  public function testV3($namespace, $name, $expected) {
    $actual1 = UUID::v3($namespace, $name);
    $this->assertUUID($actual1, $expected);

    $actual2 = UUID::v3($namespace, $name);
    $this->assertUUID($actual2, $expected);

    $this->assertEquals($actual1, $actual2);
  }

  /**
   * @dataProvider providerNameNamespace
   */
  public function testV5($namespace, $name, $expected) {
    $actual1 = UUID::v5($namespace, $name);
    $this->assertUUID($actual1, $expected);

    $actual2 = UUID::v5($namespace, $name);
    $this->assertUUID($actual2, $expected);

    $this->assertEquals($actual1, $actual2);
  }

  /**
   * @dataProvider providerNameNamespace
   */
  public function testV3V5($namespace, $name, $expected) {
    $actual1 = UUID::v3($namespace, $name);
    $this->assertUUID($actual1, $expected);

    $actual2 = UUID::v5($namespace, $name);
    $this->assertUUID($actual2, $expected);

    if (empty($expected)) {
      $this->assertSame($actual1, $actual2);
    } else {
      $this->assertNotEquals($actual1, $actual2);
    }
  }


}
