<?php
/**
 * StepXtenTest.php
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
class StepXtenTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
    }
    
    /**
     * @dataProvider stepXtenDataProvider
     */
    public function testStepX($chardata, $expected)
    {
        $stepxten = new \Com\Tecnick\Unicode\Bidi\StepXten($chardata, 0);
        //var_export($stepxten->getIsolatedLevelRunSequences());echo "\n\n";//DEBUG
        $this->assertEquals($expected, $stepxten->getIsolatedLevelRunSequences());
    }

    public function stepXtenDataProvider()
    {
        return array(
            array(
                // BD13 Example 1: text1·RLE·text2·PDF·RLE·text3·PDF·text4
                array(
                    array('char' => 33,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 34,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 38,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 39,   'level' => 0, 'type' => 'ON', 'otype' => 'ON')
                ),
                array()
            ),
            array(
                // BD13 Example 2: text1·RLI·text2·PDI·RLI·text3·PDI·text4
                array(
                    array('char' => 33,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8295, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 34,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8297, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 8295, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 38,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8297, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 39,   'level' => 0, 'type' => 'ON', 'otype' => 'ON')
                ),
                array()
            ),
            array(
                // BD13 Example 3: text1·RLI·text2·LRI·text3·RLE·text4·PDF·text5·PDI·text6·PDI·text7
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
                ),
                array()
            ),
            array(
                // X10 Example 1: text1·RLE·text2·LRE·text3·PDF·text4·PDF·RLE·text5·PDF·text6
                array(
                    array('char' => 33,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 34,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 38,   'level' => 2, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 39,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 40,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 41,   'level' => 0, 'type' => 'ON', 'otype' => 'ON')
                ),
                array()
            ),
            array(
                // X10 Example 2: text1·RLI·text2·LRI·text3·PDI·text4·PDI·RLI·text5·PDI·text6
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
                ),
                array()
            ),
            array(
                // X10 Example 3: text1·RLE·text2·LRI·text3·RLE·text4·PDI·text5·PDF·text6
                array(
                    array('char' => 33,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 34,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8294, 'level' => 1, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 38,   'level' => 2, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 39,   'level' => 3, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 8297, 'level' => 1, 'type' => 'NI', 'otype' => 'NI'),
                    array('char' => 40,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'),
                    array('char' => 41,   'level' => 0, 'type' => 'ON', 'otype' => 'ON')
                ),
                array()
            ),
        );
    }
}
