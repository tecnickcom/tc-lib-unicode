<?php
/**
 * StepXTest.php
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
class StepXTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
    }
    
    /**
     * @dataProvider stepXDataProvider
     */
    public function testStepX($ordarr, $expected)
    {
        $stepx = new \Com\Tecnick\Unicode\Bidi\StepX($ordarr, 0);
        $this->assertEquals($expected, $stepx->getChrData());
    }

    public function stepXDataProvider()
    {
        return array(
            array(
                // BD13 Example 1: text1·RLE·text2·PDF·RLE·text3·PDF·text4
                array(33,8235,34,8236,8235,38,8236,39),
                array(
                    array('char' => 33,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 34,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 38,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 39,   'level' => 0, 'type' => 'ON', 'otype' => 'ON')
                )
            ),
            array(
                // BD13 Example 2: text1·RLI·text2·PDI·RLI·text3·PDI·text4
                array(33,8295,34,8297,8295,38,8297,39),
                array(
                    array('char' => 33,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8295, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 34,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8297, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 8295, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 38,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8297, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 39,   'level' => 0, 'type' => 'ON', 'otype' => 'ON')
                )
            ),
            array(
                // BD13 Example 3: text1·RLI·text2·LRI·text3·RLE·text4·PDF·text5·PDI·text6·PDI·text7
                array(33,8295,34,8294,38,8235,39,8236,40,8297,41,8297,42),
                array(
                    array('char' => 33,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8295, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 34,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8294, 'level' => 1, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 38,   'level' => 2, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 39,   'level' => 3, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 40,   'level' => 2, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8297, 'level' => 1, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 41,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8297, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 42,   'level' => 0, 'type' => 'ON', 'otype' => 'ON')
                )
            ),
            array(
                // X10 Example 1: text1·RLE·text2·LRE·text3·PDF·text4·PDF·RLE·text5·PDF·text6
                array(33,8235,34,8234,38,8236,39,8236,8235,40,8236,41),
                array(
                    array('char' => 33,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 34,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 38,   'level' => 2, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 39,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 40,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 41,   'level' => 0, 'type' => 'ON', 'otype' => 'ON')
                )
            ),
            array(
                // X10 Example 2: text1·RLI·text2·LRI·text3·PDI·text4·PDI·RLI·text5·PDI·text6
                array(33,8295,34,8294,38,8297,39,8297,8295,40,8297,41),
                array(
                    array('char' => 33,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8295, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 34,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8294, 'level' => 1, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 38,   'level' => 2, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8297, 'level' => 1, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 39,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8297, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 8295, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 40,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8297, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 41,   'level' => 0, 'type' => 'ON', 'otype' => 'ON')
                )
            ),
            array(
                // X10 Example 3: text1·RLE·text2·LRI·text3·RLE·text4·PDI·text5·PDF·text6
                array(33,8235,34,8294,38,8235,39,8297,40,8236,41),
                array(
                    array('char' => 33,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 34,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8294, 'level' => 1, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 38,   'level' => 2, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 39,   'level' => 3, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8297, 'level' => 1, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 40,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 41,   'level' => 0, 'type' => 'ON', 'otype' => 'ON')
                )
            ),
        );
    }
}
