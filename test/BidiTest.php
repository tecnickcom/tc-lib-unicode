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
    /**
     * @dataProvider bidiStrDataProvider
     */
    /*public function testBidiStr($str, $expected, $forcertl = false)
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
        );
    }*/

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
                // text1·RLI·text2·LRI·text3·PDI·text4·PDI·RLI·text5·PDI·text6
                array(33,8295,34,8294,38,8297,39,8297,8295,40,8297,41),
                array(),
                'L'
            ),
            array(
                // text1·RLE·text2·LRI·text3·RLE·text4·PDI·text5·PDF·text6
                array(33,8235,34,8294,38,8235,39,8297,40,8236,41),
                array(),
                'L'
            ),
            array(
                // text1·RLE·text2·LRE·text3·PDF·text4·PDF·RLE·text5·PDF·text6
                array(33,8235,34,8234,38,8236,39,8236,8235,40,8236,41),
                array(),
                'L'
            ),
        );
    }
}
