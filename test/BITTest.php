<?php
use Beats\Utils\BIT;

class BITTest extends \PHPUnit_Framework_TestCase {

  public function providerFlags() {
    return array(
      array("asdf", false),
      array(0, false),
      array(1, true),
      array(2, true),
      array(3, false),
    );

  }

  /**
   * @dataProvider providerFlags
   */
  public function testIsFlag($input, $expected) {
    $this->assertEquals(BIT::isFlag($input), $expected);
  }

  /**
   * @dataProvider providerFlags
   */
  public function testFlag($input, $expected) {
    if ($expected === false) {
      $this->setExpectedException('Beats\\Utils\\BITException');
    }
    $this->assertEquals(BIT::flag($input), $input);
  }

  /********************************************************************************************************************/

  public function providerMasks() {
    return array(
      array(0x00000000, 0x00000001, 0x00000000),
      array(0x00000001, 0x00000001, 0x00000001),
      array(0x00000003, 0x00000001, 0x00000001),
      array(0x00000003, 0x00000002, 0x00000002),
      array(0x00000073, 0x00000052, 0x00000052),
      array(0x00000073, 0x00000084, 0x00000000),
    );
  }


  /**
   * @dataProvider providerMasks
   */
  public function testMask($value, $mask, $expected) {
    $this->assertEquals(BIT::mask($value, $mask), $expected);
  }

  /**
   * @dataProvider providerMasks
   */
  public function testIsMasked($value, $mask, $expected) {
    $this->assertEquals(BIT::isMasked($value, $mask), !!$expected);
  }

  /********************************************************************************************************************/

  public function providerSet() {
    $t = true; $f = false; $n = null;
    return array(
      array(0x00000000, 0x00000003, $t, false),
      array(0x00000000, 0x00000003, $f, false),
      array(0x00000000, 0x00000003, $n, false),

      array(0x00000000, 0x00000001, $t, 0x00000001),
      array(0x00000000, 0x00000001, $f, 0x00000000),
      array(0x00000000, 0x00000001, $n, 0x00000001),

      array(0x00000001, 0x00000001, $t, 0x00000001),
      array(0x00000001, 0x00000001, $f, 0x00000000),
      array(0x00000001, 0x00000001, $n, 0x00000000),

      array(0x00000007, 0x00000002, $t, 0x00000007),
      array(0x00000007, 0x00000002, $f, 0x00000005),
      array(0x00000007, 0x00000002, $n, 0x00000005),

      array(0x00000005, 0x00000002, $t, 0x00000007),
      array(0x00000005, 0x00000002, $f, 0x00000005),
      array(0x00000005, 0x00000002, $n, 0x00000007),
    );
  }

  /**
   * @dataProvider providerSet
   */
  public function testSet($value, $flag, $on, $expected) {
    if ($expected === false) {
      $this->setExpectedException('Beats\\Utils\\BITException');
    }
    $this->assertEquals(BIT::set($value, $flag, $on), $expected);
  }

  /********************************************************************************************************************/

  public function providerAdd() {
    return array(
      array(0x00000000, 0x00000001, 1, 0x00000001),
      array(0x00000000, 0x00000001, 0, 0x00000000),

      array(0x00000001, 0x00000001, 1, 0x00000001),
      array(0x00000001, 0x00000001, 0, 0x00000000),

      array(0x00000007, 0x00000002, 1, 0x00000007),
      array(0x00000007, 0x00000002, 0, 0x00000005),

      array(0x00000007, 0x00000003, 1, 0x00000007),
      array(0x00000007, 0x00000003, 0, 0x00000004),

      array(0x00000022, 0x00000015, 1, 0x00000037),
      array(0x00000022, 0x00000015, 0, 0x00000022),

      array(0x00000052, 0x00000025, 1, 0x00000077),
      array(0x00000052, 0x00000025, 0, 0x00000052),

      array(0x00000077, 0x00000025, 1, 0x00000077),
      array(0x00000077, 0x00000025, 0, 0x00000052),
    );
  }

  /**
   * @dataProvider providerAdd
   */
  public function testAdd($value, $mask, $on, $expected) {
    $this->assertEquals(BIT::add($value, $mask, $on), $expected);
  }

  /********************************************************************************************************************/

  public function providerToggle() {
    return array(
      array(0x00000000, 0x00000001, 0x00000001),
      array(0x00000001, 0x00000001, 0x00000000),

      array(0x00000007, 0x00000002, 0x00000005),
      array(0x00000007, 0x00000005, 0x00000002),

      array(0x00000052, 0x00000042, 0x00000010),
      array(0x00000052, 0x00000000, 0x00000052),

      array(0xFFFFFFFF, 0x00000001, 0xFFFFFFFE),
      array(0xFFFFFFFF, 0x00000F00, 0xFFFFF0FF),
    );
  }

  /**
   * @dataProvider providerToggle
   */
  public function testToggle($value, $mask, $expected) {
    $this->assertEquals(BIT::toggle($value, $mask), $expected);
  }


}
