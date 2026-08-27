<?php

/**
 * HangulTest.php
 *
 * @since     2026-04-30
 * @category  Library
 * @package   Unicode
 * @author    Nicola Asuni <info@tecnick.com>
 * @copyright 2011-2026 Nicola Asuni - Tecnick.com LTD
 * @license   https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link      https://github.com/tecnickcom/tc-lib-unicode
 *
 * This file is part of tc-lib-unicode software library.
 */

namespace Test\Substitution;

use Com\Tecnick\Unicode\Substitution\Hangul;
use PHPUnit\Framework\Attributes\DataProvider;
use Test\TestUtil;

/**
 * Hangul substitution test
 *
 * @since     2026-04-30
 * @category  Library
 * @package   Unicode
 * @author    Nicola Asuni <info@tecnick.com>
 * @copyright 2011-2026 Nicola Asuni - Tecnick.com LTD
 * @license   https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link      https://github.com/tecnickcom/tc-lib-unicode
 */
class HangulTest extends TestUtil
{
    /**
     * @param array<int, int> $input
     * @param array<int, int> $expected
     */
    #[DataProvider('hangulDataProvider')]
    public function testGetOrdarr(array $input, array $expected): void
    {
        $obj = new Hangul($input);
        $this->assertSame($expected, $obj->getOrdarr());
    }

    /**
     * @return array<string, array{0: array<int, int>, 1: array<int, int>}>
     *
     * The expected values follow the formula of section 3.12 of the Unicode standard,
     * with LBase=1100, VBase=1161, TBase=11A7, NCount=588 and TCount=28:
     *   S = SBase + (L-LBase)*NCount + (V-VBase)*TCount + (T-TBase)
     *
     *   U+AC00 (가) = AC00 + (1100-1100)*588 + (1161-1161)*28
     *   U+AC01 (각) = AC00 + (1100-1100)*588 + (1161-1161)*28 + (11A8-11A7)
     *   U+AC08 (갈) = AC00 + (1100-1100)*588 + (1161-1161)*28 + (11AF-11A7)
     *   U+B098 (나) = AC00 + (1102-1100)*588 + (1161-1161)*28
     */
    public static function hangulDataProvider(): array
    {
        return [
            // Empty input returns empty output
            'empty' => [
                [],
                [],
            ],

            // Rule 2 with a precomposed LV syllable already in the input:
            // U+AC00 (GA) + U+11A8 (KIYEOK) -> U+AC01 (GAG)
            'precomposed_lv_plus_t' => [
                [0xAC00, 0x11A8],
                [0xAC01],
            ],

            // A syllable that already has a trailing consonant is not composed again
            'lvt_plus_t' => [
                [0xAC01, 0x11A8],
                [0xAC01, 0x11A8],
            ],

            // A precomposed LV syllable followed by anything else is left alone
            'precomposed_lv_plus_ascii' => [
                [0xAC00, 0x41],
                [0xAC00, 0x41],
            ],

            // Pure ASCII: no Hangul, pass through unchanged
            'ascii_only' => [
                [0x41, 0x42, 0x43],
                [0x41, 0x42, 0x43],
            ],

            // Leading consonant at end of array (no following vowel): unchanged
            // U+1100 KIYEOK alone
            'lone_leading_consonant' => [
                [0x1100],
                [0x1100],
            ],

            // First leading consonant, last leading consonant: boundary check
            // U+1100, U+1112: no vowels follow; both pass through
            'leading_consonant_boundaries' => [
                [0x1100, 0x1112],
                [0x1100, 0x1112],
            ],

            // Codepoint just above leading consonant range (U+1113): not L, pass through
            'above_leading_consonant_range' => [
                [0x1113],
                [0x1113],
            ],

            // Vowel alone: not a leading consonant, pass through
            // U+1161 JUNGSEONG A
            'lone_vowel' => [
                [0x1161],
                [0x1161],
            ],

            // Trailing consonant alone: not a leading consonant, pass through
            // U+11A8 JONGSEONG KIYEOK
            'lone_trailing_consonant' => [
                [0x11A8],
                [0x11A8],
            ],

            // L + V → LV syllable (no trailing consonant)
            // U+1100 + U+1161 → U+AC00 가 (GA)
            'l_plus_v_ga' => [
                [0x1100, 0x1161],
                [0xAC00],
            ],

            // L + V boundary: last L (U+1112) + last V (U+1175) → syllable
            // AC00 + 18*588 + 20*28 = 44032 + 10584 + 560 = 55176 = 0xD788
            'l_plus_v_boundary' => [
                [0x1112, 0x1175],
                [0xD788],
            ],

            // L + V + T → LVT syllable
            // U+1100 + U+1161 + U+11A8 → U+AC01 각 (GAK)
            // LV = AC00, T = 11A8 - 11A7 = 1 → AC00 + 1 = AC01
            'l_plus_v_plus_t_gak' => [
                [0x1100, 0x1161, 0x11A8],
                [0xAC01],
            ],

            // L + V + T with T = last valid trailing consonant (U+11C2)
            // U+1100 + U+1161 + U+11C2 → AC00 + (11C2 - 11A7) = AC00 + 27 = AC1B
            'l_plus_v_plus_t_last_trailing' => [
                [0x1100, 0x1161, 0x11C2],
                [0xAC1B],
            ],

            // L + V + TBase (U+11A7): TBase itself is NOT a valid trailing
            // consonant; treated as next non-T codepoint. LV emitted, then
            // U+11A7 passed through unchanged.
            'l_plus_v_plus_tbase_not_trailing' => [
                [0x1100, 0x1161, 0x11A7],
                [0xAC00, 0x11A7],
            ],

            // L + V + codepoint above T range (U+11C3): not a trailing consonant,
            // LV emitted then U+11C3 passed through
            'l_plus_v_then_above_t_range' => [
                [0x1100, 0x1161, 0x11C3],
                [0xAC00, 0x11C3],
            ],

            // L followed by non-vowel (ASCII): L emitted unchanged, then ASCII
            'leading_consonant_then_ascii' => [
                [0x1100, 0x41],
                [0x1100, 0x41],
            ],

            // Two separate LV syllables in sequence
            // U+1100+U+1161, U+1102+U+1161 → U+AC00, U+B098
            // B098: AC00 + 2*588 = AC00 + 1176 = 0xB098
            'two_lv_syllables' => [
                [0x1100, 0x1161, 0x1102, 0x1161],
                [0xAC00, 0xB098],
            ],

            // Mixed: ASCII + Jamo cluster + ASCII
            // 0x41, U+1100, U+1161, 0x42 → 0x41, U+AC00, 0x42
            'mixed_ascii_hangul' => [
                [0x41, 0x1100, 0x1161, 0x42],
                [0x41, 0xAC00, 0x42],
            ],

            // L + V + T + next L + V: two clusters in series
            // U+1100, U+1161, U+11A8, U+1102, U+1161 → U+AC01, U+B098
            'two_clusters_with_trailing' => [
                [0x1100, 0x1161, 0x11A8, 0x1102, 0x1161],
                [0xAC01, 0xB098],
            ],

            // L + first vowel out-of-range: U+1160 is just below VBASE: not a vowel
            'leading_consonant_then_below_vbase' => [
                [0x1100, 0x1160],
                [0x1100, 0x1160],
            ],

            // L + first codepoint above vowel range: U+1176: not a vowel
            'leading_consonant_then_above_vrange' => [
                [0x1100, 0x1176],
                [0x1100, 0x1176],
            ],
        ];
    }

    public function testNormalizesSparseIndexes(): void
    {
        $obj = new Hangul([10 => 0x1100, 20 => 0x1161]);
        $this->assertSame([0xAC00], $obj->getOrdarr());
    }
}
