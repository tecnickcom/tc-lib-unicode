<?php
/**
 * StepNTest.php
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
class StepNTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
    }

    /**
     * @dataProvider stepN0DataProvider
     */
    public function testStepN0($seq, $expected)
    {
        $stepn = new \Com\Tecnick\Unicode\Bidi\StepN($seq, false);
        $stepn->processStep('getBracketPairs');
        $stepn->processStep('processN0');
        $this->assertEquals($expected, $stepn->getSequence());
    }

    public function stepN0DataProvider()
    {
        return array(
            array(
                array(
                    'e' => 0,
                    'edir' => 'L',
                    'start' => 0,
                    'end' => 4,
                    'length' => 5,
                    'sos' => 'L',
                    'eos' => 'L',
                    'item' => array(
                        array('char' => 8207, 'level' => 0, 'type' => 'R',  'otype' => 'R'),
                        array('char' => 91,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'), // [
                        array('char' => 65,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                        array('char' => 93,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'), // ]
                        array('char' => 8207, 'level' => 0, 'type' => 'R',  'otype' => 'R'),
                    ),
                ),
array (
'e' => 0,
'edir' => 'L',
'start' => 0,
'end' => 4,
'length' => 5,
'sos' => 'L',
'eos' => 'L',
'item' => 
array (
0 => 
array (
'char' => 8207,
'level' => 0,
'type' => 'R',
'otype' => 'R',
),
1 => 
array (
'char' => 91,
'level' => 0,
'type' => 'L',
'otype' => 'ON',
),
2 => 
array (
'char' => 65,
'level' => 0,
'type' => 'L',
'otype' => 'L',
),
3 => 
array (
'char' => 93,
'level' => 0,
'type' => 'L',
'otype' => 'ON',
),
4 => 
array (
'char' => 8207,
'level' => 0,
'type' => 'R',
'otype' => 'R',
),
),
)
            ),
            array(
                array(
                    'e' => 1,
                    'edir' => 'R',
                    'start' => 0,
                    'end' => 4,
                    'length' => 5,
                    'sos' => 'R',
                    'eos' => 'R',
                    'item' => array(
                        array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                        array('char' => 91,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'), // [
                        array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                        array('char' => 8207, 'level' => 1, 'type' => 'R',  'otype' => 'R'),
                        array('char' => 93,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'), // ]
                    ),
                ),
array (
'e' => 1,
'edir' => 'R',
'start' => 0,
'end' => 4,
'length' => 5,
'sos' => 'R',
'eos' => 'R',
'item' => 
array (
0 => 
array (
'char' => 65,
'level' => 1,
'type' => 'L',
'otype' => 'L',
),
1 => 
array (
'char' => 91,
'level' => 1,
'type' => 'R',
'otype' => 'ON',
),
2 => 
array (
'char' => 65,
'level' => 1,
'type' => 'L',
'otype' => 'L',
),
3 => 
array (
'char' => 8207,
'level' => 1,
'type' => 'R',
'otype' => 'R',
),
4 => 
array (
'char' => 93,
'level' => 1,
'type' => 'R',
'otype' => 'ON',
),
),
)
            ),
            array(
                array(
                    'e' => 1,
                    'edir' => 'R',
                    'start' => 0,
                    'end' => 4,
                    'length' => 5,
                    'sos' => 'R',
                    'eos' => 'R',
                    'item' => array(
                        array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                        array('char' => 91,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'), // [
                        array('char' => 5760, 'level' => 1, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 8207, 'level' => 1, 'type' => 'R',  'otype' => 'R'),
                        array('char' => 93,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'), // ]
                    ),
                ),
array (
'e' => 1,
'edir' => 'R',
'start' => 0,
'end' => 4,
'length' => 5,
'sos' => 'R',
'eos' => 'R',
'item' => 
array (
0 => 
array (
'char' => 65,
'level' => 1,
'type' => 'L',
'otype' => 'L',
),
1 => 
array (
'char' => 91,
'level' => 1,
'type' => 'R',
'otype' => 'ON',
),
2 => 
array (
'char' => 5760,
'level' => 1,
'type' => 'NI',
'otype' => 'NI',
),
3 => 
array (
'char' => 8207,
'level' => 1,
'type' => 'R',
'otype' => 'R',
),
4 => 
array (
'char' => 93,
'level' => 1,
'type' => 'R',
'otype' => 'ON',
),
),
)
            ),
            array(
                array(
                    'e' => 0,
                    'edir' => 'L',
                    'start' => 0,
                    'end' => 4,
                    'length' => 5,
                    'sos' => 'L',
                    'eos' => 'L',
                    'item' => array(
                        array('char' => 8207, 'level' => 0, 'type' => 'R',  'otype' => 'R'),
                        array('char' => 91,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'), // [
                        array('char' => 8207, 'level' => 0, 'type' => 'R',  'otype' => 'R'),
                        array('char' => 93,   'level' => 0, 'type' => 'ON', 'otype' => 'ON'), // ]
                        array('char' => 65,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    ),
                ),
array (
'e' => 0,
'edir' => 'L',
'start' => 0,
'end' => 4,
'length' => 5,
'sos' => 'L',
'eos' => 'L',
'item' => 
array (
0 => 
array (
'char' => 8207,
'level' => 0,
'type' => 'R',
'otype' => 'R',
),
1 => 
array (
'char' => 91,
'level' => 0,
'type' => 'R',
'otype' => 'ON',
),
2 => 
array (
'char' => 8207,
'level' => 0,
'type' => 'R',
'otype' => 'R',
),
3 => 
array (
'char' => 93,
'level' => 0,
'type' => 'R',
'otype' => 'ON',
),
4 => 
array (
'char' => 65,
'level' => 0,
'type' => 'L',
'otype' => 'L',
),
),
)
            ),
            array(
                array(
                    'e' => 1,
                    'edir' => 'R',
                    'start' => 0,
                    'end' => 5,
                    'length' => 6,
                    'sos' => 'R',
                    'eos' => 'R',
                    'item' => array(
                        array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                        array('char' => 91,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'), // [
                        array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                        array('char' => 5760, 'level' => 1, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 93,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'), // ]
                        array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    ),
                ),
array (
'e' => 1,
'edir' => 'R',
'start' => 0,
'end' => 5,
'length' => 6,
'sos' => 'R',
'eos' => 'R',
'item' => 
array (
0 => 
array (
'char' => 65,
'level' => 1,
'type' => 'L',
'otype' => 'L',
),
1 => 
array (
'char' => 91,
'level' => 1,
'type' => 'L',
'otype' => 'ON',
),
2 => 
array (
'char' => 65,
'level' => 1,
'type' => 'L',
'otype' => 'L',
),
3 => 
array (
'char' => 5760,
'level' => 1,
'type' => 'NI',
'otype' => 'NI',
),
4 => 
array (
'char' => 93,
'level' => 1,
'type' => 'L',
'otype' => 'ON',
),
5 => 
array (
'char' => 65,
'level' => 1,
'type' => 'L',
'otype' => 'L',
),
),
)
            ),
            array(
                array(
                    'e' => 1,
                    'edir' => 'R',
                    'start' => 0,
                    'end' => 4,
                    'length' => 5,
                    'sos' => 'R',
                    'eos' => 'R',
                    'item' => array(
                        array('char' => 8207, 'level' => 1, 'type' => 'R',  'otype' => 'R'),
                        array('char' => 91,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'), // [
                        array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                        array('char' => 93,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'), // ]
                        array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    ),
                ),
array (
'e' => 1,
'edir' => 'R',
'start' => 0,
'end' => 4,
'length' => 5,
'sos' => 'R',
'eos' => 'R',
'item' => 
array (
0 => 
array (
'char' => 8207,
'level' => 1,
'type' => 'R',
'otype' => 'R',
),
1 => 
array (
'char' => 91,
'level' => 1,
'type' => 'R',
'otype' => 'ON',
),
2 => 
array (
'char' => 65,
'level' => 1,
'type' => 'L',
'otype' => 'L',
),
3 => 
array (
'char' => 93,
'level' => 1,
'type' => 'R',
'otype' => 'ON',
),
4 => 
array (
'char' => 65,
'level' => 1,
'type' => 'L',
'otype' => 'L',
),
),
)
            ),
            array(
                array(
                    'e' => 1,
                    'edir' => 'R',
                    'start' => 0,
                    'end' => 4,
                    'length' => 5,
                    'sos' => 'R',
                    'eos' => 'R',
                    'item' => array(
                        array('char' => 8207, 'level' => 1, 'type' => 'R',  'otype' => 'R'),
                        array('char' => 91,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'), // [
                        array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                        array('char' => 93,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'), // ]
                        array('char' => 8207, 'level' => 1, 'type' => 'R',  'otype' => 'R'),
                    ),
                ),
array (
'e' => 1,
'edir' => 'R',
'start' => 0,
'end' => 4,
'length' => 5,
'sos' => 'R',
'eos' => 'R',
'item' => 
array (
0 => 
array (
'char' => 8207,
'level' => 1,
'type' => 'R',
'otype' => 'R',
),
1 => 
array (
'char' => 91,
'level' => 1,
'type' => 'R',
'otype' => 'ON',
),
2 => 
array (
'char' => 65,
'level' => 1,
'type' => 'L',
'otype' => 'L',
),
3 => 
array (
'char' => 93,
'level' => 1,
'type' => 'R',
'otype' => 'ON',
),
4 => 
array (
'char' => 8207,
'level' => 1,
'type' => 'R',
'otype' => 'R',
),
),
)
            ),
            array(
                array(
                    'e' => 1,
                    'edir' => 'R',
                    'start' => 0,
                    'end' => 4,
                    'length' => 5,
                    'sos' => 'R',
                    'eos' => 'R',
                    'item' => array(
                        array('char' => 8207, 'level' => 1, 'type' => 'R',  'otype' => 'R'),
                        array('char' => 91,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'), // [
                        array('char' => 5760, 'level' => 1, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 93,   'level' => 1, 'type' => 'ON', 'otype' => 'ON'), // ]
                        array('char' => 65,   'level' => 1, 'type' => 'L',  'otype' => 'L'),
                    ),
                ),
array (
'e' => 1,
'edir' => 'R',
'start' => 0,
'end' => 4,
'length' => 5,
'sos' => 'R',
'eos' => 'R',
'item' => 
array (
0 => 
array (
'char' => 8207,
'level' => 1,
'type' => 'R',
'otype' => 'R',
),
1 => 
array (
'char' => 91,
'level' => 1,
'type' => 'ON',
'otype' => 'ON',
),
2 => 
array (
'char' => 5760,
'level' => 1,
'type' => 'NI',
'otype' => 'NI',
),
3 => 
array (
'char' => 93,
'level' => 1,
'type' => 'ON',
'otype' => 'ON',
),
4 => 
array (
'char' => 65,
'level' => 1,
'type' => 'L',
'otype' => 'L',
),
),
)
            ),
        );
    }

    /**
     * @dataProvider stepN1DataProvider
     */
    public function testStepN1($seq, $expected)
    {
        $stepn = new \Com\Tecnick\Unicode\Bidi\StepN($seq, false);
        $stepn->processStep('processN1');
        //var_export($stepn->getSequence()); // DEBUG
        $this->assertEquals($expected, $stepn->getSequence());
    }

    public function stepN1DataProvider()
    {
        return array(
            array(
                array(
                    'e' => 0,
                    'edir' => 'L',
                    'start' => 0,
                    'end' => 2,
                    'length' => 3,
                    'sos' => 'L',
                    'eos' => 'L',
                    'item' => array(
                        array('char' => 65,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                        array('char' => 5760, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 65,   'level' => 0, 'type' => 'L',  'otype' => 'L'),
                    ),
                ),
array (
'e' => 0,
'edir' => 'L',
'start' => 0,
'end' => 2,
'length' => 3,
'sos' => 'L',
'eos' => 'L',
'item' => 
array (
0 => 
array (
'char' => 65,
'level' => 0,
'type' => 'L',
'otype' => 'L',
),
1 => 
array (
'char' => 5760,
'level' => 0,
'type' => 'L',
'otype' => 'NI',
),
2 => 
array (
'char' => 65,
'level' => 0,
'type' => 'L',
'otype' => 'L',
),
),
)
            ),
            array(
                array(
                    'e' => 0,
                    'edir' => 'L',
                    'start' => 0,
                    'end' => 2,
                    'length' => 3,
                    'sos' => 'L',
                    'eos' => 'L',
                    'item' => array(
                        array('char' => 8207, 'level' => 0, 'type' => 'R',  'otype' => 'R'),
                        array('char' => 5760, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 8207, 'level' => 0, 'type' => 'R',  'otype' => 'R'),
                    ),
                ),
array (
'e' => 0,
'edir' => 'L',
'start' => 0,
'end' => 2,
'length' => 3,
'sos' => 'L',
'eos' => 'L',
'item' => 
array (
0 => 
array (
'char' => 8207,
'level' => 0,
'type' => 'R',
'otype' => 'R',
),
1 => 
array (
'char' => 5760,
'level' => 0,
'type' => 'R',
'otype' => 'NI',
),
2 => 
array (
'char' => 8207,
'level' => 0,
'type' => 'R',
'otype' => 'R',
),
),
)
            ),
            array(
                array(
                    'e' => 0,
                    'edir' => 'L',
                    'start' => 0,
                    'end' => 2,
                    'length' => 3,
                    'sos' => 'L',
                    'eos' => 'L',
                    'item' => array(
                        array('char' => 8207, 'level' => 0, 'type' => 'R',  'otype' => 'R'),
                        array('char' => 5760, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 1632, 'level' => 0, 'type' => 'AN', 'otype' => 'AN'),
                    ),
                ),
array (
'e' => 0,
'edir' => 'L',
'start' => 0,
'end' => 2,
'length' => 3,
'sos' => 'L',
'eos' => 'L',
'item' => 
array (
0 => 
array (
'char' => 8207,
'level' => 0,
'type' => 'R',
'otype' => 'R',
),
1 => 
array (
'char' => 5760,
'level' => 0,
'type' => 'R',
'otype' => 'NI',
),
2 => 
array (
'char' => 1632,
'level' => 0,
'type' => 'AN',
'otype' => 'AN',
),
),
)
            ),
            array(
                array(
                    'e' => 0,
                    'edir' => 'L',
                    'start' => 0,
                    'end' => 2,
                    'length' => 3,
                    'sos' => 'L',
                    'eos' => 'L',
                    'item' => array(
                        array('char' => 8207, 'level' => 0, 'type' => 'R',  'otype' => 'R'),
                        array('char' => 5760, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 1776, 'level' => 0, 'type' => 'EN', 'otype' => 'EN'),
                    ),
                ),
array (
'e' => 0,
'edir' => 'L',
'start' => 0,
'end' => 2,
'length' => 3,
'sos' => 'L',
'eos' => 'L',
'item' => 
array (
0 => 
array (
'char' => 8207,
'level' => 0,
'type' => 'R',
'otype' => 'R',
),
1 => 
array (
'char' => 5760,
'level' => 0,
'type' => 'R',
'otype' => 'NI',
),
2 => 
array (
'char' => 1776,
'level' => 0,
'type' => 'EN',
'otype' => 'EN',
),
),
)
            ),
            array(
                array(
                    'e' => 0,
                    'edir' => 'L',
                    'start' => 0,
                    'end' => 2,
                    'length' => 3,
                    'sos' => 'L',
                    'eos' => 'L',
                    'item' => array(
                        array('char' => 1632, 'level' => 0, 'type' => 'AN', 'otype' => 'AN'),
                        array('char' => 5760, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 8207, 'level' => 0, 'type' => 'R',  'otype' => 'R'),
                    ),
                ),
array (
'e' => 0,
'edir' => 'L',
'start' => 0,
'end' => 2,
'length' => 3,
'sos' => 'L',
'eos' => 'L',
'item' => 
array (
0 => 
array (
'char' => 1632,
'level' => 0,
'type' => 'AN',
'otype' => 'AN',
),
1 => 
array (
'char' => 5760,
'level' => 0,
'type' => 'R',
'otype' => 'NI',
),
2 => 
array (
'char' => 8207,
'level' => 0,
'type' => 'R',
'otype' => 'R',
),
),
)
            ),
            array(
                array(
                    'e' => 0,
                    'edir' => 'L',
                    'start' => 0,
                    'end' => 2,
                    'length' => 3,
                    'sos' => 'L',
                    'eos' => 'L',
                    'item' => array(
                        array('char' => 1632, 'level' => 0, 'type' => 'AN', 'otype' => 'AN'),
                        array('char' => 5760, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 1632, 'level' => 0, 'type' => 'AN', 'otype' => 'AN'),
                    ),
                ),
array (
'e' => 0,
'edir' => 'L',
'start' => 0,
'end' => 2,
'length' => 3,
'sos' => 'L',
'eos' => 'L',
'item' => 
array (
0 => 
array (
'char' => 1632,
'level' => 0,
'type' => 'AN',
'otype' => 'AN',
),
1 => 
array (
'char' => 5760,
'level' => 0,
'type' => 'R',
'otype' => 'NI',
),
2 => 
array (
'char' => 1632,
'level' => 0,
'type' => 'AN',
'otype' => 'AN',
),
),
)
            ),
            array(
                array(
                    'e' => 0,
                    'edir' => 'L',
                    'start' => 0,
                    'end' => 2,
                    'length' => 3,
                    'sos' => 'L',
                    'eos' => 'L',
                    'item' => array(
                        array('char' => 1632, 'level' => 0, 'type' => 'AN', 'otype' => 'AN'),
                        array('char' => 5760, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 1776, 'level' => 0, 'type' => 'EN', 'otype' => 'EN'),
                    ),
                ),
array (
'e' => 0,
'edir' => 'L',
'start' => 0,
'end' => 2,
'length' => 3,
'sos' => 'L',
'eos' => 'L',
'item' => 
array (
0 => 
array (
'char' => 1632,
'level' => 0,
'type' => 'AN',
'otype' => 'AN',
),
1 => 
array (
'char' => 5760,
'level' => 0,
'type' => 'R',
'otype' => 'NI',
),
2 => 
array (
'char' => 1776,
'level' => 0,
'type' => 'EN',
'otype' => 'EN',
),
),
)
            ),
            array(
                array(
                    'e' => 0,
                    'edir' => 'L',
                    'start' => 0,
                    'end' => 2,
                    'length' => 3,
                    'sos' => 'L',
                    'eos' => 'L',
                    'item' => array(
                        array('char' => 1776, 'level' => 0, 'type' => 'EN', 'otype' => 'EN'),
                        array('char' => 5760, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 8207, 'level' => 0, 'type' => 'R',  'otype' => 'R'),
                    ),
                ),
array (
'e' => 0,
'edir' => 'L',
'start' => 0,
'end' => 2,
'length' => 3,
'sos' => 'L',
'eos' => 'L',
'item' => 
array (
0 => 
array (
'char' => 1776,
'level' => 0,
'type' => 'EN',
'otype' => 'EN',
),
1 => 
array (
'char' => 5760,
'level' => 0,
'type' => 'R',
'otype' => 'NI',
),
2 => 
array (
'char' => 8207,
'level' => 0,
'type' => 'R',
'otype' => 'R',
),
),
)
            ),
            array(
                array(
                    'e' => 0,
                    'edir' => 'L',
                    'start' => 0,
                    'end' => 2,
                    'length' => 3,
                    'sos' => 'L',
                    'eos' => 'L',
                    'item' => array(
                        array('char' => 1776, 'level' => 0, 'type' => 'EN', 'otype' => 'EN'),
                        array('char' => 5760, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 1632, 'level' => 0, 'type' => 'AN', 'otype' => 'AN'),
                    ),
                ),
array (
'e' => 0,
'edir' => 'L',
'start' => 0,
'end' => 2,
'length' => 3,
'sos' => 'L',
'eos' => 'L',
'item' => 
array (
0 => 
array (
'char' => 1776,
'level' => 0,
'type' => 'EN',
'otype' => 'EN',
),
1 => 
array (
'char' => 5760,
'level' => 0,
'type' => 'R',
'otype' => 'NI',
),
2 => 
array (
'char' => 1632,
'level' => 0,
'type' => 'AN',
'otype' => 'AN',
),
),
)
            ),
            array(
                array(
                    'e' => 0,
                    'edir' => 'L',
                    'start' => 0,
                    'end' => 2,
                    'length' => 3,
                    'sos' => 'L',
                    'eos' => 'L',
                    'item' => array(
                        array('char' => 1776, 'level' => 0, 'type' => 'EN', 'otype' => 'EN'),
                        array('char' => 5760, 'level' => 0, 'type' => 'NI', 'otype' => 'NI'),
                        array('char' => 1776, 'level' => 0, 'type' => 'EN', 'otype' => 'EN'),
                    ),
                ),
array (
'e' => 0,
'edir' => 'L',
'start' => 0,
'end' => 2,
'length' => 3,
'sos' => 'L',
'eos' => 'L',
'item' => 
array (
0 => 
array (
'char' => 1776,
'level' => 0,
'type' => 'EN',
'otype' => 'EN',
),
1 => 
array (
'char' => 5760,
'level' => 0,
'type' => 'R',
'otype' => 'NI',
),
2 => 
array (
'char' => 1776,
'level' => 0,
'type' => 'EN',
'otype' => 'EN',
),
),
)
            ),
        );
    }
}
