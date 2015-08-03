<?php
/**
 * BidiTest.php
 *
 * @since       2011-05-23
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 *
 * This file is part of tc-lib-unicode software library.
 */

namespace Test;

/**
 * Bidi Test
 *
 * @since       2011-05-23
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 */
class BidiTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
    }
    
    public function testException()
    {
        $this->setExpectedException('\Com\Tecnick\Unicode\Exception');
        new \Com\Tecnick\Unicode\Bidi(null, null, null, false);
    }

    /**
     * @dataProvider inputDataProvider
     */
    public function testStr($str, $charr, $ordarr, $forcertl)
    {
        $bidi = new \Com\Tecnick\Unicode\Bidi($str, $charr, $ordarr, $forcertl);
        $this->assertEquals('test', $bidi->getString());
        $this->assertEquals(array('t', 'e', 's', 't'), $bidi->getChrArray());
        $this->assertEquals(array(116, 101, 115, 116), $bidi->getOrdArray());
        $this->assertEquals(array(116 => true, 101 => true, 115 => true), $bidi->getCharKeys());
    }

    public function inputDataProvider()
    {
        return array(
            array('test', null, null, false),
            array(null, array('t', 'e', 's', 't'), null, false),
            array(null, null, array(116, 101, 115, 116), false),
            array('test', array('t', 'e', 's', 't'), null, false),
            array('test', null, array(116, 101, 115, 116), false),
            array(null, array('t', 'e', 's', 't'), array(116, 101, 115, 116), false),
            array('test', array('t', 'e', 's', 't'), array(116, 101, 115, 116), false),
            array('test', null, null, 'L'),
            array('test', null, null, 'R'),
        );
    }

    /**
     * @dataProvider bidiStrDataProvider
     */
    public function testBidiStr($str, $expected, $forcertl = false)
    {
        $bidi = new \Com\Tecnick\Unicode\Bidi($str, null, null, $forcertl);
        $this->assertEquals($expected, $bidi->getString());
    }

    public function bidiStrDataProvider()
    {
        return array(
            array(
                json_decode('"The words \"\u202b\u05de\u05d6\u05dc [mazel] '
                    .'\u05d8\u05d5\u05d1 [tov]\u202c\" mean \"Congratulations!\""'),
                'The words "[tov] בוט [mazel] לזמ" mean "Congratulations!"'
            ),
            array(
                'اختبار بسيط',
                'طيسب رابتخا'
            ),
            array(
                json_decode('"\u0671AB\u0679\u0683"'),
                'ڃٹABٱ'
            ),
            array(
                json_decode('"\u067137\u0679\u0683"'),
                'ڃٹ37ٱ'
            ),
            array(
                json_decode('"AB\u0683"'),
                'ABڃ'
            ),
            array(
                json_decode('"AB\u0683"'),
                'ABڃ',
                'L'
            ),
            array(
                json_decode('"AB\u0683"'),
                'ڃAB',
                'R'
            ),
            array(
                json_decode('"he said \"\u0671\u0679! \u0683\" to her"'),
                'he said "ٹٱ! ڃ" to her'
            ),
            array(
                json_decode('"he said \"\u0671\u0679!\" to her"'),
                'he said "ٹٱ!" to her'
            ),
            array(
                json_decode('"he said \"\u0671\u0679! \u200F\" to her"'),
                'he said "ٹٱ! ‏" to her'
            ),
            array(
                json_decode('"START CODES \u202bRLE\u202a LRE \u202eRLO\u202d LRO \u202cPDF\u202c END"'),
                'START CODES RLE LRE FDP LRO OLR END'
            ),
            array(
                json_decode('"\u202EABC\u202C"'),
                'CBA'
            ),
            array(
                json_decode('"\u202D\u0671\u0679\u0683\u202C"'),
                'ٱٹڃ'
            ),
        );
    }

    /**
     * @dataProvider bidiOrdDataProvider
     */
    public function testBidiOrd($ordarr, $expected, $forcertl = false)
    {
        $bidi = new \Com\Tecnick\Unicode\Bidi(null, null, $ordarr, $forcertl);
        //var_export($bidi->getOrdArray());
        //echo "\n\n".$bidi->getString()."\n\n";
        $this->assertEquals($expected, $bidi->getOrdArray());
        
    }

    public function bidiOrdDataProvider()
    {
        return array(
            array(
                array(1649,65,66,1657,1667),
                array(1667,1657,65,66,1649)
            ),
            array(
                array(1667,1657,65,66,1649),
                array(1649,65,66,1657,1667)
            ),
            array(
                array(65,66,1667),
                array(65,66,1667)
            ),
            array(
                array(65,66,1667),
                array(65,66,1667),
                'L'
            ),
            array(
                array(65,66,1667),
                array(1667,65,66),
                'R'
            ),
        );
    }
}
