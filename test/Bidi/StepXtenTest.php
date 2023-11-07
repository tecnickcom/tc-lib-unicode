<?php

/**
 * StepXtenTest.php
 *
 * @since     2011-05-23
 * @category  Library
 * @package   Unicode
 * @author    Nicola Asuni <info@tecnick.com>
 * @copyright 2011-2023 Nicola Asuni - Tecnick.com LTD
 * @license   http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link      https://github.com/tecnickcom/tc-lib-unicode
 *
 * This file is part of tc-lib-unicode software library.
 */

namespace Test\Bidi;

use PHPUnit\Framework\TestCase;

/**
 * Bidi Test
 *
 * @since     2011-05-23
 * @category  Library
 * @package   Unicode
 * @author    Nicola Asuni <info@tecnick.com>
 * @copyright 2011-2023 Nicola Asuni - Tecnick.com LTD
 * @license   http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link      https://github.com/tecnickcom/tc-lib-unicode
 */
class StepXtenTest extends TestCase
{
    /**
     * @dataProvider stepXtenDataProvider
     */
    public function testStepXteN(array $chardata, mixed $expected): void
    {
        $stepxten = new \Com\Tecnick\Unicode\Bidi\StepXten($chardata, 0);
        $this->assertEquals($expected, $stepxten->getIsolatedLevelRunSequences());
    }

    public static function stepXtenDataProvider(): array
    {
        return [[
            // BD13 Example 1: text1·RLE·text2·PDF·RLE·text3·PDF·text4
            [[
                'pos' => 0,
                'char' => 33,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 2,
                'char' => 34,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 5,
                'char' => 38,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 7,
                'char' => 39,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ON',
            ]],
            [[
                'e' => 0,
                'edir' => 'L',
                'start' => 0,
                'end' => 0,
                'length' => 1,
                'sos' => 'L',
                'eos' => 'R',
                'item' => [[
                    'pos' => 0,
                    'char' => 33,
                    'level' => 0,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 1,
                'edir' => 'R',
                'start' => 1,
                'end' => 2,
                'length' => 2,
                'sos' => 'R',
                'eos' => 'R',
                'item' => [[
                    'pos' => 2,
                    'char' => 34,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ], [
                    'pos' => 5,
                    'char' => 38,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 0,
                'edir' => 'L',
                'start' => 3,
                'end' => 3,
                'length' => 1,
                'sos' => 'R',
                'eos' => 'L',
                'item' => [[
                    'pos' => 7,
                    'char' => 39,
                    'level' => 0,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ]],
        ], [
            // BD13 Example 2: text1·RLI·text2·PDI·RLI·text3·PDI·text4
            [[
                'pos' => 0,
                'char' => 33,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 1,
                'char' => 8295,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 34,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 3,
                'char' => 8297,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 4,
                'char' => 8295,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 5,
                'char' => 38,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 6,
                'char' => 8297,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 7,
                'char' => 39,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ON',
            ]],
            [[
                'e' => 0,
                'edir' => 'L',
                'start' => 0,
                'end' => 12,
                'length' => 6,
                'sos' => 'L',
                'eos' => 'L',
                'item' => [[
                    'pos' => 0,
                    'char' => 33,
                    'level' => 0,
                    'type' => 'ON',
                    'otype' => 'ON',
                ], [
                    'pos' => 1,
                    'char' => 8295,
                    'level' => 0,
                    'type' => 'NI',
                    'otype' => 'NI',
                ], [
                    'pos' => 3,
                    'char' => 8297,
                    'level' => 0,
                    'type' => 'NI',
                    'otype' => 'NI',
                    'pdimatch' => 0,
                ], [
                    'pos' => 4,
                    'char' => 8295,
                    'level' => 0,
                    'type' => 'NI',
                    'otype' => 'NI',
                ], [
                    'pos' => 6,
                    'char' => 8297,
                    'level' => 0,
                    'type' => 'NI',
                    'otype' => 'NI',
                    'pdimatch' => 0,
                ], [
                    'pos' => 7,
                    'char' => 39,
                    'level' => 0,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 1,
                'edir' => 'R',
                'start' => 2,
                'end' => 2,
                'length' => 1,
                'sos' => 'R',
                'eos' => 'R',
                'item' => [[
                    'pos' => 2,
                    'char' => 34,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 1,
                'edir' => 'R',
                'start' => 5,
                'end' => 5,
                'length' => 1,
                'sos' => 'R',
                'eos' => 'R',
                'item' => [[
                    'pos' => 5,
                    'char' => 38,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ]],
        ], [
            // BD13 Example 3: text1·RLI·text2·LRI·text3·RLE·text4·PDF·text5·PDI·text6·PDI·text7
            [[
                'pos' => 0,
                'char' => 33,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 1,
                'char' => 8295,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 34,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 3,
                'char' => 8294,
                'level' => 1,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 4,
                'char' => 38,
                'level' => 2,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 6,
                'char' => 39,
                'level' => 3,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 8,
                'char' => 40,
                'level' => 2,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 9,
                'char' => 8297,
                'level' => 1,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 10,
                'char' => 41,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 11,
                'char' => 8297,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 12,
                'char' => 42,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ON',
            ]],
            [[
                'e' => 0,
                'edir' => 'L',
                'start' => 0,
                'end' => 11,
                'length' => 4,
                'sos' => 'L',
                'eos' => 'L',
                'item' => [[
                    'pos' => 0,
                    'char' => 33,
                    'level' => 0,
                    'type' => 'ON',
                    'otype' => 'ON',
                ], [
                    'pos' => 1,
                    'char' => 8295,
                    'level' => 0,
                    'type' => 'NI',
                    'otype' => 'NI',
                ], [
                    'pos' => 11,
                    'char' => 8297,
                    'level' => 0,
                    'type' => 'NI',
                    'otype' => 'NI',
                    'pdimatch' => 0,
                ], [
                    'pos' => 12,
                    'char' => 42,
                    'level' => 0,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 1,
                'edir' => 'R',
                'start' => 2,
                'end' => 11,
                'length' => 4,
                'sos' => 'R',
                'eos' => 'R',
                'item' => [[
                    'pos' => 2,
                    'char' => 34,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ], [
                    'pos' => 3,
                    'char' => 8294,
                    'level' => 1,
                    'type' => 'NI',
                    'otype' => 'NI',
                ], [
                    'pos' => 9,
                    'char' => 8297,
                    'level' => 1,
                    'type' => 'NI',
                    'otype' => 'NI',
                    'pdimatch' => 1,
                ], [
                    'pos' => 10,
                    'char' => 41,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 2,
                'edir' => 'L',
                'start' => 4,
                'end' => 4,
                'length' => 1,
                'sos' => 'L',
                'eos' => 'R',
                'item' => [[
                    'pos' => 4,
                    'char' => 38,
                    'level' => 2,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 3,
                'edir' => 'R',
                'start' => 5,
                'end' => 5,
                'length' => 1,
                'sos' => 'R',
                'eos' => 'R',
                'item' => [[
                    'pos' => 6,
                    'char' => 39,
                    'level' => 3,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 2,
                'edir' => 'L',
                'start' => 6,
                'end' => 6,
                'length' => 1,
                'sos' => 'R',
                'eos' => 'L',
                'item' => [[
                    'pos' => 8,
                    'char' => 40,
                    'level' => 2,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ]],
        ], [
            // X10 Example 1: text1·RLE·text2·LRE·text3·PDF·text4·PDF·RLE·text5·PDF·text6
            [[
                'pos' => 0,
                'char' => 33,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 2,
                'char' => 34,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 4,
                'char' => 38,
                'level' => 2,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 6,
                'char' => 39,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 9,
                'char' => 40,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 11,
                'char' => 41,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ON',
            ]],
            [[
                'e' => 0,
                'edir' => 'L',
                'start' => 0,
                'end' => 0,
                'length' => 1,
                'sos' => 'L',
                'eos' => 'R',
                'item' => [[
                    'pos' => 0,
                    'char' => 33,
                    'level' => 0,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 1,
                'edir' => 'R',
                'start' => 1,
                'end' => 1,
                'length' => 1,
                'sos' => 'R',
                'eos' => 'L',
                'item' => [[
                    'pos' => 2,
                    'char' => 34,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 2,
                'edir' => 'L',
                'start' => 2,
                'end' => 2,
                'length' => 1,
                'sos' => 'L',
                'eos' => 'L',
                'item' => [[
                    'pos' => 4,
                    'char' => 38,
                    'level' => 2,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 1,
                'edir' => 'R',
                'start' => 3,
                'end' => 4,
                'length' => 2,
                'sos' => 'L',
                'eos' => 'R',
                'item' => [[
                    'pos' => 6,
                    'char' => 39,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ], [
                    'pos' => 9,
                    'char' => 40,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 0,
                'edir' => 'L',
                'start' => 5,
                'end' => 5,
                'length' => 1,
                'sos' => 'R',
                'eos' => 'L',
                'item' => [[
                    'pos' => 11,
                    'char' => 41,
                    'level' => 0,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ]],
        ], [
            // X10 Example 2: text1·RLI·text2·LRI·text3·PDI·text4·PDI·RLI·text5·PDI·text6
            [[
                'pos' => 0,
                'char' => 33,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 1,
                'char' => 8295,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 34,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 3,
                'char' => 8294,
                'level' => 1,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 4,
                'char' => 38,
                'level' => 2,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 5,
                'char' => 8297,
                'level' => 1,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 6,
                'char' => 39,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 7,
                'char' => 8297,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 8,
                'char' => 8295,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 9,
                'char' => 40,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 10,
                'char' => 8297,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 11,
                'char' => 41,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ON',
            ]],
            [[
                'e' => 0,
                'edir' => 'L',
                'start' => 0,
                'end' => 20,
                'length' => 6,
                'sos' => 'L',
                'eos' => 'L',
                'item' => [[
                    'pos' => 0,
                    'char' => 33,
                    'level' => 0,
                    'type' => 'ON',
                    'otype' => 'ON',
                ], [
                    'pos' => 1,
                    'char' => 8295,
                    'level' => 0,
                    'type' => 'NI',
                    'otype' => 'NI',
                ], [
                    'pos' => 7,
                    'char' => 8297,
                    'level' => 0,
                    'type' => 'NI',
                    'otype' => 'NI',
                    'pdimatch' => 0,
                ], [
                    'pos' => 8,
                    'char' => 8295,
                    'level' => 0,
                    'type' => 'NI',
                    'otype' => 'NI',
                ], [
                    'pos' => 10,
                    'char' => 8297,
                    'level' => 0,
                    'type' => 'NI',
                    'otype' => 'NI',
                    'pdimatch' => 0,
                ], [
                    'pos' => 11,
                    'char' => 41,
                    'level' => 0,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 1,
                'edir' => 'R',
                'start' => 2,
                'end' => 9,
                'length' => 4,
                'sos' => 'R',
                'eos' => 'R',
                'item' => [[
                    'pos' => 2,
                    'char' => 34,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ], [
                    'pos' => 3,
                    'char' => 8294,
                    'level' => 1,
                    'type' => 'NI',
                    'otype' => 'NI',
                ], [
                    'pos' => 5,
                    'char' => 8297,
                    'level' => 1,
                    'type' => 'NI',
                    'otype' => 'NI',
                    'pdimatch' => 1,
                ], [
                    'pos' => 6,
                    'char' => 39,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 2,
                'edir' => 'L',
                'start' => 4,
                'end' => 4,
                'length' => 1,
                'sos' => 'L',
                'eos' => 'L',
                'item' => [[
                    'pos' => 4,
                    'char' => 38,
                    'level' => 2,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 1,
                'edir' => 'R',
                'start' => 9,
                'end' => 9,
                'length' => 1,
                'sos' => 'R',
                'eos' => 'R',
                'item' => [[
                    'pos' => 9,
                    'char' => 40,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ]],
        ], [
            // X10 Example 3: text1·RLE·text2·LRI·text3·RLE·text4·PDI·text5·PDF·text6
            [[
                'pos' => 0,
                'char' => 33,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 2,
                'char' => 34,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 3,
                'char' => 8294,
                'level' => 1,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 4,
                'char' => 38,
                'level' => 2,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 6,
                'char' => 39,
                'level' => 3,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 7,
                'char' => 8297,
                'level' => 1,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 8,
                'char' => 40,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 10,
                'char' => 41,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ON',
            ]],
            [[
                'e' => 0,
                'edir' => 'L',
                'start' => 0,
                'end' => 0,
                'length' => 1,
                'sos' => 'L',
                'eos' => 'R',
                'item' => [[
                    'pos' => 0,
                    'char' => 33,
                    'level' => 0,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 1,
                'edir' => 'R',
                'start' => 1,
                'end' => 8,
                'length' => 4,
                'sos' => 'R',
                'eos' => 'R',
                'item' => [[
                    'pos' => 2,
                    'char' => 34,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ], [
                    'pos' => 3,
                    'char' => 8294,
                    'level' => 1,
                    'type' => 'NI',
                    'otype' => 'NI',
                ], [
                    'pos' => 7,
                    'char' => 8297,
                    'level' => 1,
                    'type' => 'NI',
                    'otype' => 'NI',
                    'pdimatch' => 1,
                ], [
                    'pos' => 8,
                    'char' => 40,
                    'level' => 1,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 2,
                'edir' => 'L',
                'start' => 3,
                'end' => 3,
                'length' => 1,
                'sos' => 'L',
                'eos' => 'R',
                'item' => [[
                    'pos' => 4,
                    'char' => 38,
                    'level' => 2,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 3,
                'edir' => 'R',
                'start' => 4,
                'end' => 4,
                'length' => 1,
                'sos' => 'R',
                'eos' => 'R',
                'item' => [[
                    'pos' => 6,
                    'char' => 39,
                    'level' => 3,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ], [
                'e' => 0,
                'edir' => 'L',
                'start' => 7,
                'end' => 7,
                'length' => 1,
                'sos' => 'R',
                'eos' => 'L',
                'item' => [[
                    'pos' => 10,
                    'char' => 41,
                    'level' => 0,
                    'type' => 'ON',
                    'otype' => 'ON',
                ]],
            ]],
        ]];
    }
}
