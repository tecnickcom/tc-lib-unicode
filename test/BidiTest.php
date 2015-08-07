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
                "ABC\nEFG\n",
                "ABC\nEFG\n",
                true
            ),
            array(
                json_decode('"\u202EABC\u202C"'),
                'CBA'
            ),
            array(
                json_decode('"smith (fabrikam \u0600\u0601\u0602) \u05de\u05d6\u05dc"'),
                json_decode('"\u05dc\u05d6\u05de (\u0602\u0601\u0600 fabrikam) smith"'),
                'R'
            ),
            array(
                json_decode('"\u0600\u0601\u0602 book(s)"'),
                json_decode('"book(s) \u0602\u0601\u0600"'),
                'R'
            ),
            array(
                'تشكيل اختبار',
                'ﺭﺎﺒﺘﺧﺍ ﻞﻴﻜﺸﺗ'
            ),
            array(
                json_decode('"\u0600\u0601(\u0602\u0603[&ef]!)gh"'),
                json_decode('"gh(![ef&]\u0603\u0602)\u0601\u0600"'),
                'R'
            ),
        );
    }

    /**
     * @dataProvider bidiOrdDataProvider
     */
    public function testBidiOrd($ordarr, $expected, $forcertl = false)
    {
        $bidi = new \Com\Tecnick\Unicode\Bidi(null, null, $ordarr, $forcertl);
        $this->assertEquals($expected, $bidi->getOrdArray());
        
    }

    public function bidiOrdDataProvider()
    {
        return array(
            array(
                array(65),
                array(65),
            ),
        );
    }
}
