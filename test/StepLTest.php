<?php
/**
 * StepLTest.php
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
class StepLTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
    }

    /**
     * @dataProvider stepLDataProvider
     */
    public function testStepL($chardata, $pel, $maxlevel, $expected)
    {
        $stepl = new \Com\Tecnick\Unicode\Bidi\StepL($chardata, $pel, $maxlevel);
        $this->assertEquals($expected, $stepl->getChrData());
    }

    public function stepLDataProvider()
    {
        return array(
            array(
                // car means CAR.
                // 00000000001110
                array(
                    array('char' => 99,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 97,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 114,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 109,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 101,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 97,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 110,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 115,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 67,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 82,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 46,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                ),
                0,
                1,
                // car means RAC.
                array(
                    array('char' => 99, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 97, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 114, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 109, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 101, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 97, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 110, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 115, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 82, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 65, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 67, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 46, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                )
            ),
            array(
                // <car MEANS CAR.=
                // 0222111111111110
                array(
                    array('char' => 60,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 99,   'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 97,   'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 114,  'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 77,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 69,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 78,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 83,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 67,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 82,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 46,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 61,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                ),
                0,
                2,
                // <.RAC SNAEM car=
                array(
                    array('char' => 60, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 46, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 82, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 65, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 67, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 83, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 78, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 65, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 69, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 77, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 99, 'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 97, 'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 114, 'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 61, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                )
            ),
            array(
                // he said "<car MEANS CAR=." "<IT DOES=," she agreed.
                // 000000000022211111111110000001111111000000000000000
                array(
                    array('char' => 104,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 101,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 115,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 97,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 105,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 100,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 34,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 60,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 99,   'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 97,   'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 114,  'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 77,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 69,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 78,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 83,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 67,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 82,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 61,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 46,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 34,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 34,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 60,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 73,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 84,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 68,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 79,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 69,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 83,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 61,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 44,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 34,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 115,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 104,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 101,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 97,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 103,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 114,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 101,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 101,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 100,  'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 46,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                ),
                0,
                2,
                // he said "<RAC SNAEM car=." "<SEOD TI=," she agreed.
                array(
                    array('char' => 104, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 101, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 115, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 97, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 105, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 100, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 34, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 60, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 82, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 65, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 67, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 83, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 78, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 65, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 69, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 77, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 99, 'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 97, 'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 114, 'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 61, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 46, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 34, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 34, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 60, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 83, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 69, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 79, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 68, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 84, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 73, 'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 61, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 44, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 34, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 115, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 104, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 101, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 97, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 103, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 114, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 101, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 101, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 100, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 46, 'level' => 0, 'type' => 'L', 'otype' => 'L'),
                )
            ),
            array(
                // DID YOU SAY '>he said "<car MEANS CAR="='?
                // 111111111111112222222222444333333333322111
                array(
                    array('char' => 68,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 73,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 68,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 89,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 79,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 85,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 83,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 65,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 89,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 39,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 62,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 104, 'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 101, 'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,  'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 115, 'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 97,  'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 105, 'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 100, 'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,  'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 34,  'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 60,  'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 99,  'level' => 4, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 97,  'level' => 4, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 114, 'level' => 4, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,  'level' => 3, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 77,  'level' => 3, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 69,  'level' => 3, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 65,  'level' => 3, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 78,  'level' => 3, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 83,  'level' => 3, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 32,  'level' => 3, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 67,  'level' => 3, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 65,  'level' => 3, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 82,  'level' => 3, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 61,  'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 34,  'level' => 2, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 61,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 39,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    array('char' => 63,  'level' => 1, 'type' => 'L',  'otype' => 'L'),
                ),
                1,
                4,
                // ?'=he said "<RAC SNAEM car="<' YAS UOY DID
                // Note that we have a mirrored char
                array(
                    array('char' => 63,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 39,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 61,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 104, 'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 101, 'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32,  'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 115, 'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 97,  'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 105, 'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 100, 'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32,  'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 34,  'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 60,  'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 82,  'level' => 3, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 65,  'level' => 3, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 67,  'level' => 3, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32,  'level' => 3, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 83,  'level' => 3, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 78,  'level' => 3, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 65,  'level' => 3, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 69,  'level' => 3, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 77,  'level' => 3, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32,  'level' => 3, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 99,  'level' => 4, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 97,  'level' => 4, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 114, 'level' => 4, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 61,  'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 34,  'level' => 2, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 60,  'level' => 1, 'type' => 'L', 'otype' => 'L'), // MIRRORED
                    array('char' => 39,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 89,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 65,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 83,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 85,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 79,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 89,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 32,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 68,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 73,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                    array('char' => 68,  'level' => 1, 'type' => 'L', 'otype' => 'L'),
                )
            ),
        );
    }
}
