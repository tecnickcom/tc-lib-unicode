<?php

/**
 * StepWTest.php
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
class StepWTest extends TestCase
{
    /**
     * @dataProvider stepWDataProvider
     */
    public function testStepW(array $seq, mixed $expected): void
    {
        $stepw = new \Com\Tecnick\Unicode\Bidi\StepW($seq);
        $this->assertEquals($expected, $stepw->getSequence());
    }

    public static function stepWDataProvider(): array
    {
        return [[[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1536,
                'level' => 0,
                'type' => 'AL',
                'otype' => 'AL',
            ], [
                'pos' => 1,
                'char' => 768,
                'level' => 0,
                'type' => 'NSM',
                'otype' => 'NSM',
            ], [
                'pos' => 2,
                'char' => 768,
                'level' => 0,
                'type' => 'NSM',
                'otype' => 'NSM',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1536,
                'level' => 0,
                'type' => 'R',
                'otype' => 'AL',
            ], [
                'pos' => 1,
                'char' => 768,
                'level' => 0,
                'type' => 'R',
                'otype' => 'NSM',
            ], [
                'pos' => 2,
                'char' => 768,
                'level' => 0,
                'type' => 'R',
                'otype' => 'NSM',
            ]],
        ]]];
    }

    /**
     * @dataProvider stepW1DataProvider
     */
    public function testStepW1(array $seq, mixed $expected): void
    {
        $stepw = new \Com\Tecnick\Unicode\Bidi\StepW($seq, false);
        $stepw->processStep('processW1');
        $this->assertEquals($expected, $stepw->getSequence());
    }

    public static function stepW1DataProvider(): array
    {
        return [[[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1536,
                'level' => 0,
                'type' => 'AL',
                'otype' => 'AL',
            ], [
                'pos' => 1,
                'char' => 768,
                'level' => 0,
                'type' => 'NSM',
                'otype' => 'NSM',
            ], [
                'pos' => 2,
                'char' => 768,
                'level' => 0,
                'type' => 'NSM',
                'otype' => 'NSM',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1536,
                'level' => 0,
                'type' => 'AL',
                'otype' => 'AL',
            ], [
                'pos' => 1,
                'char' => 768,
                'level' => 0,
                'type' => 'AL',
                'otype' => 'NSM',
            ], [
                'pos' => 2,
                'char' => 768,
                'level' => 0,
                'type' => 'AL',
                'otype' => 'NSM',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1470,
                'level' => 0,
                'type' => 'R',
                'otype' => 'R',
            ], [
                'pos' => 1,
                'char' => 768,
                'level' => 0,
                'type' => 'NSM',
                'otype' => 'NSM',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1470,
                'level' => 0,
                'type' => 'R',
                'otype' => 'R',
            ], [
                'pos' => 1,
                'char' => 768,
                'level' => 0,
                'type' => 'R',
                'otype' => 'NSM',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 8294,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 1,
                'char' => 768,
                'level' => 0,
                'type' => 'NSM',
                'otype' => 'NSM',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 8294,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 1,
                'char' => 768,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'NSM',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 8297,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 1,
                'char' => 768,
                'level' => 0,
                'type' => 'NSM',
                'otype' => 'NSM',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 8297,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 1,
                'char' => 768,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'NSM',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 768,
                'level' => 0,
                'type' => 'NSM',
                'otype' => 'NSM',
            ], [
                'pos' => 1,
                'char' => 768,
                'level' => 0,
                'type' => 'NSM',
                'otype' => 'NSM',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 768,
                'level' => 0,
                'type' => 'R',
                'otype' => 'NSM',
            ], [
                'pos' => 1,
                'char' => 768,
                'level' => 0,
                'type' => 'R',
                'otype' => 'NSM',
            ]],
        ]]];
    }

    /**
     * @dataProvider stepW2DataProvider
     */
    public function testStepW2(array $seq, mixed $expected): void
    {
        $stepw = new \Com\Tecnick\Unicode\Bidi\StepW($seq, false);
        $stepw->processStep('processW2');
        $this->assertEquals($expected, $stepw->getSequence());
    }

    public static function stepW2DataProvider(): array
    {
        return [[[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1536,
                'level' => 0,
                'type' => 'AL',
                'otype' => 'AL',
            ], [
                'pos' => 1,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1536,
                'level' => 0,
                'type' => 'AL',
                'otype' => 'AL',
            ], [
                'pos' => 1,
                'char' => 1776,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'EN',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1536,
                'level' => 0,
                'type' => 'AL',
                'otype' => 'AL',
            ], [
                'pos' => 1,
                'char' => 1769,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1536,
                'level' => 0,
                'type' => 'AL',
                'otype' => 'AL',
            ], [
                'pos' => 1,
                'char' => 1769,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'EN',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'L',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1470,
                'level' => 0,
                'type' => 'L',
                'otype' => 'L',
            ], [
                'pos' => 1,
                'char' => 1769,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'L',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1470,
                'level' => 0,
                'type' => 'L',
                'otype' => 'L',
            ], [
                'pos' => 1,
                'char' => 1769,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 65,
                'level' => 0,
                'type' => 'L',
                'otype' => 'L',
            ], [
                'pos' => 1,
                'char' => 1769,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 65,
                'level' => 0,
                'type' => 'L',
                'otype' => 'L',
            ], [
                'pos' => 1,
                'char' => 1769,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1470,
                'level' => 0,
                'type' => 'R',
                'otype' => 'R',
            ], [
                'pos' => 1,
                'char' => 1769,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1470,
                'level' => 0,
                'type' => 'R',
                'otype' => 'R',
            ], [
                'pos' => 1,
                'char' => 1769,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ]]];
    }

    /**
     * @dataProvider stepW3DataProvider
     */
    public function testStepW3(array $seq, mixed $expected): void
    {
        $stepw = new \Com\Tecnick\Unicode\Bidi\StepW($seq, false);
        $stepw->processStep('processW3');
        $this->assertEquals($expected, $stepw->getSequence());
    }

    public static function stepW3DataProvider(): array
    {
        return [[[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1536,
                'level' => 0,
                'type' => 'AL',
                'otype' => 'AL',
            ], [
                'pos' => 1,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1536,
                'level' => 0,
                'type' => 'R',
                'otype' => 'AL',
            ], [
                'pos' => 1,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ]]];
    }

    /**
     * @dataProvider stepW4DataProvider
     */
    public function testStepW4(array $seq, mixed $expected): void
    {
        $stepw = new \Com\Tecnick\Unicode\Bidi\StepW($seq, false);
        $stepw->processStep('processW4');
        $this->assertEquals($expected, $stepw->getSequence());
    }

    public static function stepW4DataProvider(): array
    {
        return [[[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ], [
                'pos' => 1,
                'char' => 43,
                'level' => 0,
                'type' => 'ES',
                'otype' => 'ES',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ], [
                'pos' => 1,
                'char' => 43,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'ES',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ], [
                'pos' => 1,
                'char' => 44,
                'level' => 0,
                'type' => 'CS',
                'otype' => 'CS',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ], [
                'pos' => 1,
                'char' => 44,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'CS',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1632,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'AN',
            ], [
                'pos' => 1,
                'char' => 44,
                'level' => 0,
                'type' => 'CS',
                'otype' => 'CS',
            ], [
                'pos' => 2,
                'char' => 1632,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'AN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1632,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'AN',
            ], [
                'pos' => 1,
                'char' => 44,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'CS',
            ], [
                'pos' => 2,
                'char' => 1632,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'AN',
            ]],
        ]]];
    }

    /**
     * @dataProvider stepW5DataProvider
     */
    public function testStepW5(array $seq, mixed $expected): void
    {
        $stepw = new \Com\Tecnick\Unicode\Bidi\StepW($seq, false);
        $stepw->processStep('processW5');
        $this->assertEquals($expected, $stepw->getSequence());
    }

    public static function stepW5DataProvider(): array
    {
        return [[[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1642,
                'level' => 0,
                'type' => 'ET',
                'otype' => 'ET',
            ], [
                'pos' => 1,
                'char' => 1642,
                'level' => 0,
                'type' => 'ET',
                'otype' => 'ET',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1642,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'ET',
            ], [
                'pos' => 1,
                'char' => 1642,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'ET',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ], [
                'pos' => 1,
                'char' => 1642,
                'level' => 0,
                'type' => 'ET',
                'otype' => 'ET',
            ], [
                'pos' => 2,
                'char' => 1642,
                'level' => 0,
                'type' => 'ET',
                'otype' => 'ET',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ], [
                'pos' => 1,
                'char' => 1642,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'ET',
            ], [
                'pos' => 2,
                'char' => 1642,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'ET',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1632,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'AN',
            ], [
                'pos' => 1,
                'char' => 1642,
                'level' => 0,
                'type' => 'ET',
                'otype' => 'ET',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1632,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'AN',
            ], [
                'pos' => 1,
                'char' => 1642,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'ET',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 4,
            'length' => 5,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1642,
                'level' => 0,
                'type' => 'ET',
                'otype' => 'ET',
            ], [
                'pos' => 1,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ], [
                'pos' => 2,
                'char' => 1642,
                'level' => 0,
                'type' => 'ET',
                'otype' => 'ET',
            ], [
                'pos' => 3,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ], [
                'pos' => 4,
                'char' => 38,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 4,
            'length' => 5,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1642,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'ET',
            ], [
                'pos' => 1,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ], [
                'pos' => 2,
                'char' => 1642,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'ET',
            ], [
                'pos' => 3,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ], [
                'pos' => 4,
                'char' => 38,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ]],
        ]]];
    }

    /**
     * @dataProvider stepW6DataProvider
     */
    public function testStepW6(array $seq, mixed $expected): void
    {
        $stepw = new \Com\Tecnick\Unicode\Bidi\StepW($seq, false);
        $stepw->processStep('processW6');
        $this->assertEquals($expected, $stepw->getSequence());
    }

    public static function stepW6DataProvider(): array
    {
        return [[[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1632,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'AN',
            ], [
                'pos' => 1,
                'char' => 1642,
                'level' => 0,
                'type' => 'ET',
                'otype' => 'ET',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1632,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'AN',
            ], [
                'pos' => 1,
                'char' => 1642,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ET',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 65,
                'level' => 0,
                'type' => 'L',
                'otype' => 'L',
            ], [
                'pos' => 1,
                'char' => 43,
                'level' => 0,
                'type' => 'ES',
                'otype' => 'ES',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 65,
                'level' => 0,
                'type' => 'L',
                'otype' => 'L',
            ], [
                'pos' => 1,
                'char' => 43,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ES',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ], [
                'pos' => 1,
                'char' => 44,
                'level' => 0,
                'type' => 'CS',
                'otype' => 'CS',
            ], [
                'pos' => 2,
                'char' => 1632,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'AN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ], [
                'pos' => 1,
                'char' => 44,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'CS',
            ], [
                'pos' => 2,
                'char' => 1632,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'AN',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1642,
                'level' => 0,
                'type' => 'ET',
                'otype' => 'ET',
            ], [
                'pos' => 1,
                'char' => 1632,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'AN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 1,
            'length' => 2,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1642,
                'level' => 0,
                'type' => 'ON',
                'otype' => 'ET',
            ], [
                'pos' => 1,
                'char' => 1632,
                'level' => 0,
                'type' => 'AN',
                'otype' => 'AN',
            ]],
        ]]];
    }

    /**
     * @dataProvider stepW7DataProvider
     */
    public function testStepW7(array $seq, mixed $expected): void
    {
        $stepw = new \Com\Tecnick\Unicode\Bidi\StepW($seq, false);
        $stepw->processStep('processW7');
        $this->assertEquals($expected, $stepw->getSequence());
    }

    public static function stepW7DataProvider(): array
    {
        return [[[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 65,
                'level' => 0,
                'type' => 'L',
                'otype' => 'L',
            ], [
                'pos' => 1,
                'char' => 8294,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 65,
                'level' => 0,
                'type' => 'L',
                'otype' => 'L',
            ], [
                'pos' => 1,
                'char' => 8294,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'L',
                'otype' => 'EN',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1470,
                'level' => 0,
                'type' => 'R',
                'otype' => 'R',
            ], [
                'pos' => 1,
                'char' => 8294,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'R',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'R',
            'eos' => 'R',
            'item' => [[
                'pos' => 0,
                'char' => 1470,
                'level' => 0,
                'type' => 'R',
                'otype' => 'R',
            ], [
                'pos' => 1,
                'char' => 8294,
                'level' => 0,
                'type' => 'NI',
                'otype' => 'NI',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ]], [[
            'e' => 0,
            'edir' => 'L',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'L',
            'eos' => 'L',
            'item' => [[
                'pos' => 0,
                'char' => 38,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 1,
                'char' => 38,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'EN',
                'otype' => 'EN',
            ]],
        ], [
            'e' => 0,
            'edir' => 'L',
            'start' => 0,
            'end' => 2,
            'length' => 3,
            'sos' => 'L',
            'eos' => 'L',
            'item' => [[
                'pos' => 0,
                'char' => 38,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 1,
                'char' => 38,
                'level' => 1,
                'type' => 'ON',
                'otype' => 'ON',
            ], [
                'pos' => 2,
                'char' => 1776,
                'level' => 0,
                'type' => 'L',
                'otype' => 'EN',
            ]],
        ]]];
    }
}
