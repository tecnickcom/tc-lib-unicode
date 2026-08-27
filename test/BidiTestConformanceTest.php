<?php

/**
 * BidiTestConformanceTest.php
 *
 * @since     2026-08-27
 * @category  Library
 * @package   Unicode
 * @author    Nicola Asuni <info@tecnick.com>
 * @copyright 2011-2026 Nicola Asuni - Tecnick.com LTD
 * @license   https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link      https://github.com/tecnickcom/tc-lib-unicode
 *
 * This file is part of tc-lib-unicode software library.
 */

namespace Test;

use Com\Tecnick\Unicode\Bidi\StepI;
use Com\Tecnick\Unicode\Bidi\StepL;
use Com\Tecnick\Unicode\Bidi\StepN;
use Com\Tecnick\Unicode\Bidi\StepP;
use Com\Tecnick\Unicode\Bidi\StepW;
use Com\Tecnick\Unicode\Bidi\StepX;
use Com\Tecnick\Unicode\Bidi\StepXten;
use Com\Tecnick\Unicode\Data\Type as UniType;
use PHPUnit\Framework\TestCase;

/**
 * Runs the official BidiTest.txt conformance suite of the Unicode Character Database
 * against the bidirectional algorithm.
 *
 * The file lists bidi classes rather than codepoints and permutes every combination up
 * to length four, so unlike BidiCharacterTest.txt it covers the explicit embedding codes
 * (LRE, RLE, LRO, RLO, PDF) and the paragraph separator densely. Each case is run through
 * the Step pipeline directly rather than through Bidi, because the file treats the whole
 * input as one paragraph while Bidi applies rule P1 and splits it on the separator.
 *
 * The data file is downloaded by "make ucd" into target/ucd/<version>/; the test is
 * skipped when it is not available.
 *
 * @since     2026-08-27
 * @category  Library
 * @package   Unicode
 * @author    Nicola Asuni <info@tecnick.com>
 * @copyright 2011-2026 Nicola Asuni - Tecnick.com LTD
 * @license   https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link      https://github.com/tecnickcom/tc-lib-unicode
 */
class BidiTestConformanceTest extends TestCase
{
    /**
     * A representative codepoint for each bidi class of field 0 of the data file.
     */
    private const CHARMAP = [
        'L' => 0x0061,
        'R' => 0x05D0,
        'AL' => 0x0627,
        'EN' => 0x0030,
        'ES' => 0x002B,
        'ET' => 0x0023,
        'AN' => 0x0660,
        'CS' => 0x002C,
        'NSM' => 0x0300,
        'BN' => 0x00AD,
        'B' => 0x001C,
        'S' => 0x0009,
        'WS' => 0x0020,
        'ON' => 0x0021,
        'LRE' => 0x202A,
        'RLE' => 0x202B,
        'LRO' => 0x202D,
        'RLO' => 0x202E,
        'PDF' => 0x202C,
        'LRI' => 0x2066,
        'RLI' => 0x2067,
        'FSI' => 0x2068,
        'PDI' => 0x2069,
    ];

    /**
     * Paragraph direction of the bitset of field 1 of the data file.
     */
    private const DIRECTION = [
        1 => 'auto',
        2 => 'L',
        4 => 'R',
    ];

    /**
     * Maximum number of unexpected failures reported by the assertion message.
     */
    private const MAX_REPORTED = 10;

    /**
     * The representative codepoints have to carry the bidi class they stand for, or the
     * conformance run silently tests something else.
     */
    public function testCharMap(): void
    {
        foreach (self::CHARMAP as $class => $ord) {
            $this->assertSame($class, UniType::getType($ord), \sprintf('U+%04X is not %s', $ord, $class));
        }
    }

    /**
     * The library emits the paragraph separator at the end of the paragraph instead of
     * reordering it, so the separator positions are excluded from the comparison: the
     * resolved level and the relative order of every other character have to match.
     */
    public function testBidiTest(): void
    {
        $path = self::findDataFile();
        if ($path === null) {
            $this->markTestSkipped('BidiTest.txt not found: run "make ucd" to download it');
        }

        $handle = \fopen($path, 'r');
        $this->assertIsResource($handle);

        $levels = [];
        $reorder = [];
        $total = 0;
        $separator = 0;
        $failures = [];

        while (($line = \fgets($handle)) !== false) {
            $line = \trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }

            if ($line[0] === '@') {
                if (\str_starts_with($line, '@Levels:')) {
                    $levels = self::splitFields(\substr($line, 8));
                } elseif (\str_starts_with($line, '@Reorder:')) {
                    $reorder = \array_map('intval', self::splitFields(\substr($line, 9)));
                }

                continue;
            }

            $field = \explode(';', $line);
            if (\count($field) < 2) {
                continue;
            }

            $classes = self::splitFields($field[0]);
            $bitset = (int) \trim($field[1] ?? '');
            $skip = \array_keys($classes, 'B', true);

            foreach (self::DIRECTION as $bit => $direction) {
                if (($bitset & $bit) === 0) {
                    continue;
                }

                ++$total;
                if ($skip !== []) {
                    ++$separator;
                }

                if (self::runCase($classes, $direction, $levels, $reorder, $skip)) {
                    continue;
                }

                $failures[] = \sprintf('%s (%s)', \implode(' ', $classes), $direction);
            }
        }

        \fclose($handle);

        $this->assertGreaterThan(700_000, $total, 'unexpected number of conformance cases');
        $this->assertGreaterThan(30_000, $separator, 'unexpected number of paragraph separator cases');
        $this->assertSame(
            [],
            \array_slice($failures, 0, self::MAX_REPORTED),
            \sprintf('%d of %d conformance cases failed', \count($failures), $total),
        );
    }

    /**
     * Runs a single data file case and returns true when the resolved levels and the
     * reordering match, ignoring the positions listed in $skip.
     *
     * @param array<int, string> $classes   Bidi class of each character
     * @param string             $direction Paragraph direction: 'auto', 'L' or 'R'
     * @param array<int, string> $levels    Expected resolved level of each character, 'x' when removed
     * @param array<int, int>    $reorder   Expected visual order, as indexes of the input
     * @param array<int, int>    $skip      Input indexes excluded from the comparison
     */
    private static function runCase(array $classes, string $direction, array $levels, array $reorder, array $skip): bool
    {
        $par = [];
        foreach ($classes as $class) {
            $ord = self::CHARMAP[$class] ?? null;
            if ($ord === null) {
                // An unknown bidi class means CHARMAP is out of date with the data file.
                return false;
            }

            $par[] = $ord;
        }

        $pel = match ($direction) {
            'L' => 0,
            'R' => 1,
            default => (new StepP($par))->getPel(),
        };

        $chardata = self::runPipeline($par, $pel);

        $gotLevels = [];
        foreach ($chardata as $chardatum) {
            $gotLevels[$chardatum['pos']] = $chardatum['level'];
        }

        foreach ($levels as $idx => $level) {
            if (\in_array($idx, $skip, true)) {
                continue;
            }

            $got = $gotLevels[$idx] ?? null;
            if ($level === 'x' ? $got !== null : $got !== (int) $level) {
                return false;
            }
        }

        $keep = static fn(int $idx): bool => !\in_array($idx, $skip, true);
        $gotOrder = \array_filter(\array_map(static fn(array $c): int => $c['pos'], $chardata), $keep);

        return \array_values($gotOrder) === \array_values(\array_filter($reorder, $keep));
    }

    /**
     * Runs the Step pipeline on one paragraph and returns the reordered characters.
     *
     * @param array<int, int> $par Codepoints of the paragraph
     * @param int             $pel Paragraph embedding level
     *
     * @return array<int, array{pos: int, level: int}>
     */
    private static function runPipeline(array $par, int $pel): array
    {
        $stepx = new StepX($par, $pel);
        $stepx10 = new StepXten($stepx->getChrData(), $pel);

        $chardata = [];
        $maxlevel = 0;
        foreach ($stepx10->getIsolatedLevelRunSequences() as $ilr) {
            $stepw = new StepW($ilr);
            $stepn = new StepN($stepw->getSequence());
            $stepi = new StepI($stepn->getSequence());
            $ilr = $stepi->getSequence();
            \array_push($chardata, ...$ilr['item']);
            if ($ilr['maxlevel'] > $maxlevel) {
                $maxlevel = $ilr['maxlevel'];
            }
        }

        $stepl = new StepL($chardata, $pel, $maxlevel);

        return \array_map(static fn(array $c): array => [
            'pos' => $c['pos'],
            'level' => $c['level'],
        ], $stepl->getChrData());
    }

    /**
     * Splits a whitespace separated data file field.
     *
     * @return array<int, string>
     */
    private static function splitFields(string $field): array
    {
        $field = \trim($field);
        if ($field === '') {
            return [];
        }

        $parts = \preg_split('/\s+/', $field);

        return $parts === false ? [] : $parts;
    }

    /**
     * Returns the path of the most recent BidiTest.txt available, or null.
     */
    private static function findDataFile(): ?string
    {
        $found = \glob(__DIR__ . '/../target/ucd/*/BidiTest.txt');
        if ($found === false || $found === []) {
            return null;
        }

        \sort($found);

        return \end($found);
    }
}
