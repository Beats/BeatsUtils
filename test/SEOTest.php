<?php
use Beats\Utils\SEO;

class SEOTest extends \PHPUnit_Framework_TestCase {

  /**
   * Non ascii chars:
   *  '"‘’‚“”„†‡‰‹›♠♣♥♦‾←↑→↓™!“#$%&‘()*+,-./:;<=>?@[\]]_`{|}~–—¡¢£¤¥¦§¨©ª«¬®;°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ'
   */
  public function providerCommon() {
    return array(
      array('', 'n-a'),
      array('simple', 'simple'),
      array('white space characters', 'white-space-characters'),
      array('special "chars"', 'special-chars'),
      array('  trimmed ', 'trimmed'),
      array('trànslitërated šžćčǉǌ', 'transliterated-szccljnj'),
      array('transliteration excluded đ', 'transliteration-excluded-'),

      array(
        'simbols \'"‘’‚“”„†‡‰‹›♠♣♥♦‾←↑→↓™!“#$%&‘()*+,-./:;<=>?@[\]]_`{|}~–—¡¢£¤¥¦§¨©«¬®;°±²³´¶·¸¹»¼½¾¿×÷',
        'simbols',
      ),
      array(
        'excluded ÐđðØøªºÞ',
        'excluded-',
      ),
      array(
        'non-ascii ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝŸ àáâãäåçèéêëìíîïñòóôõöùúûüýÿ µ',
        'non-ascii-aaaaaaceeeeiiiinooooouuuuyy-aaaaaaceeeeiiiinooooouuuuyy-u'
      ),
      array(
        'serbian ŠŽĆČ šžćč ǇǊ ǉǌ',
        'serbian-szcc-szcc-ljnj-ljnj'
      ),
      array(
        'expanded Æ  æ  ß  ǇǊ  ǉǌ',
        'expanded-ae-ae-ss-ljnj-ljnj'
      ),
    );
  }

  /**
   * @dataProvider providerCommon
   */
  public function testSlugify($input, $expected) {
    $actual = SEO::slugify($input);
    $this->assertEquals($actual, $expected);
  }

}
