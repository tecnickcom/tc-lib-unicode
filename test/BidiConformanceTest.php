<?php

/**
 * BidiConformanceTest.php
 *
 * @since     2026-08-06
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

use Com\Tecnick\Unicode\Bidi;
use Com\Tecnick\Unicode\Data\Mirror as UniMirror;
use Com\Tecnick\Unicode\Data\Type as UniType;
use PHPUnit\Framework\TestCase;

/**
 * Runs the official BidiCharacterTest.txt conformance suite of the Unicode Character
 * Database against the bidirectional algorithm, both on the whole paragraph and through
 * the logical output reordered with Bidi::reorderLine().
 *
 * The data file is downloaded by "make ucd" into target/ucd/<version>/; the test is
 * skipped when it is not available.
 *
 * @since     2026-08-06
 * @category  Library
 * @package   Unicode
 * @author    Nicola Asuni <info@tecnick.com>
 * @copyright 2011-2026 Nicola Asuni - Tecnick.com LTD
 * @license   https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link      https://github.com/tecnickcom/tc-lib-unicode
 */
class BidiConformanceTest extends TestCase
{
    /**
     * Paragraph direction of field 1 of the data file.
     */
    private const DIRECTION = [
        '0' => 'L',
        '1' => 'R',
        '2' => '',
    ];

    /**
     * Known failures, as "<field 0>;<field 1>" of the data file, with the reason.
     * A case listed here must fail: the test reports the entries that can be removed.
     *
     * @var array<string, string>
     */
    private const KNOWN_FAILURES = [];

    /**
     * Maximum number of unexpected failures reported by the assertion message.
     */
    private const MAX_REPORTED = 10;

    public function testBidiCharacterTest(): void
    {
        $path = self::findDataFile();
        if ($path === null) {
            $this->markTestSkipped('BidiCharacterTest.txt not found: run "make ucd" to download it');
        }

        $handle = \fopen($path, 'r');
        $this->assertIsResource($handle);

        $total = 0;
        $failures = [];
        $fixed = [];

        while (($line = \fgets($handle)) !== false) {
            $line = \trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }

            $field = \explode(';', $line);
            if (\count($field) < 5) {
                continue;
            }

            ++$total;
            $key = $field[0] . ';' . ($field[1] ?? '');
            $passed = self::runCase($field);

            if (isset(self::KNOWN_FAILURES[$key])) {
                if ($passed) {
                    $fixed[] = $key;
                }

                continue;
            }

            if (!$passed) {
                $failures[] = $line;
            }
        }

        \fclose($handle);

        $this->assertGreaterThan(90_000, $total, 'unexpected number of conformance cases');
        $this->assertSame([], $fixed, 'these cases now pass and must be removed from KNOWN_FAILURES');
        $this->assertSame(
            [],
            \array_slice($failures, 0, self::MAX_REPORTED),
            \sprintf('%d of %d conformance cases failed', \count($failures), $total),
        );
    }

    /**
     * Runs a single data file line: codepoints, paragraph direction, paragraph level,
     * resolved levels and visual order.
     *
     * @param array<int, string> $field Fields of the data file line
     */
    private static function runCase(array $field): bool
    {
        $codes = \trim($field[0] ?? '');
        $direction = \trim($field[1] ?? '');
        $levelStr = \trim($field[3] ?? '');
        $orderStr = \trim($field[4] ?? '');

        $ordarr = \array_map(static fn(string $hex): int => (int) \hexdec($hex), \explode(' ', $codes));
        $levels = \explode(' ', $levelStr);
        $order = $orderStr === '' ? [] : \array_map('intval', \explode(' ', $orderStr));

        // The data file lists the reordered indexes without applying rule L4, which the
        // library does apply, so the mirrored form is used for the odd levels.
        $expected = [];
        foreach ($order as $idx) {
            $ord = $ordarr[$idx] ?? 0;
            $expected[] = ((int) ($levels[$idx] ?? 0) % 2) === 1 ? UniMirror::UNI[$ord] ?? $ord : $ord;
        }

        try {
            $bidi = new Bidi(null, null, $ordarr, self::DIRECTION[$direction] ?? '', false);
            $result = \array_values($bidi->getOrdArray());
            $lines = self::reorderParagraphs($bidi);
        } catch (\Throwable) {
            return false;
        }

        return $result === $expected && $lines === $result;
    }

    /**
     * Reorders the logical output of each paragraph as a single line with Bidi::reorderLine().
     *
     * @return array<int, int>
     *
     * @throws \Com\Tecnick\Unicode\Exception
     */
    private static function reorderParagraphs(Bidi $bidi): array
    {
        $ordarr = $bidi->getLogicalOrdArray();
        $levels = $bidi->getLogicalLevels();
        $pels = $bidi->getLogicalParagraphLevels();
        $visual = [];
        $start = 0;
        $num = \count($ordarr);
        for ($idx = 0; $idx <= $num; ++$idx) {
            $ord = $ordarr[$idx] ?? null;
            if ($ord !== null && UniType::getType($ord) !== 'B') {
                continue;
            }

            $len = $idx - $start;
            if ($len > 0) {
                \array_push($visual, ...Bidi::reorderLine(
                    \array_slice($ordarr, $start, $len),
                    \array_slice($levels, $start, $len),
                    $pels[$start] ?? 0,
                ));
            }

            if ($ord !== null) {
                $visual[] = $ord;
            }

            $start = $idx + 1;
        }

        return $visual;
    }

    /**
     * Returns the path of the most recent BidiCharacterTest.txt available, or null.
     */
    private static function findDataFile(): ?string
    {
        $found = \glob(__DIR__ . '/../target/ucd/*/BidiCharacterTest.txt');
        if ($found === false || $found === []) {
            return null;
        }

        \sort($found);

        return \end($found);
    }
}
