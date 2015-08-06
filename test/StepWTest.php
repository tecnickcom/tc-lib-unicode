<?php
/**
 * StepWTest.php
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
class StepWTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        //$this->markTestSkipped(); // skip this test
    }
    
    /**
     * @dataProvider stepWDataProvider
     */
    public function testStepW($seq, $expected)
    {
        $stepw = new \Com\Tecnick\Unicode\Bidi\StepW($seq);
        var_export($stepw->getSequence());
        $this->assertEquals($expected, $stepw->getSequence());
    }

// 1536=>'AL',
// 1776=>'EN',
// 65=>'L',
// 1470=>'R',
// 8295 NI
// 1632=>'AN',
// 1642=>'ET',
// 1769=>'ON',
// 768=>'NSM',

    public function stepWDataProvider()
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
                        array('char' => 34, 'level' => 0, 'type' => 'AL', 'otype' => 'AL'),
                        array('char' => 38, 'level' => 0, 'type' => 'NSM', 'otype' => 'NSM'),
                        array('char' => 38, 'level' => 0, 'type' => 'NSM', 'otype' => 'NSM'),
                    ),
                ),
                array()
            ),
        );
    }
}
