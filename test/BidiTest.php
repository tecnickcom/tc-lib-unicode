<?php

/**
 * BidiTest.php
 *
 * @since     2011-05-23
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
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Bidi Test
 *
 * @since     2011-05-23
 * @category  Library
 * @package   Unicode
 * @author    Nicola Asuni <info@tecnick.com>
 * @copyright 2011-2026 Nicola Asuni - Tecnick.com LTD
 * @license   https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link      https://github.com/tecnickcom/tc-lib-unicode
 */
class BidiTest extends TestUtil
{
    private static function decodeJsonString(string $json): string
    {
        /** @var string */
        return \json_decode($json);
    }

    /**
     * @throws \Com\Tecnick\Unicode\Exception
     */
    public function testException(): void
    {
        $this->bcExpectException(\Com\Tecnick\Unicode\Exception::class);
        new \Com\Tecnick\Unicode\Bidi();
    }

    /**
     * @param ?string $str      String to convert (if null it will be generated from $chrarr or $ordarr)
     * @param ?array<string>  $chrarr   Array of UTF-8 chars (if empty it will be generated from $str or $ordarr)
     * @param ?array<int>  $ordarr   Array of UTF-8 codepoints (if empty it will be generated from $str or $chrarr)
     * @param string $forcedir If 'R' forces RTL, if 'L' forces LTR
     * @param bool   $shaping  If true enable the shaping algorithm
     *
     * @throws \Com\Tecnick\Unicode\Exception
     */
    #[DataProvider('inputDataProvider')]
    public function testStr(
        ?string $str = null,
        ?array $chrarr = null,
        ?array $ordarr = null,
        string $forcedir = '',
        bool $shaping = true,
    ): void {
        $bidi = new Bidi($str, $chrarr, $ordarr, $forcedir, $shaping);
        $this->assertEquals('test', $bidi->getString());
        $this->assertEquals(['t', 'e', 's', 't'], $bidi->getChrArray());
        $this->assertEquals([116, 101, 115, 116], $bidi->getOrdArray());
        $this->assertEquals(
            [
                116 => true,
                101 => true,
                115 => true,
            ],
            $bidi->getCharKeys(),
        );
        $this->assertEquals(4, $bidi->getNumChars());
    }

    /**
     * @return array<int, array{?string, ?array<string>, ?array<int>, string, bool}>
     */
    public static function inputDataProvider(): array
    {
        return [
            ['test', null, null, '', true],
            [null, ['t', 'e', 's', 't'], null, '', true],
            [null, null, [116, 101, 115, 116], '', true],
            ['test', ['t', 'e', 's', 't'], null, '', true],
            ['test', null, [116, 101, 115, 116], '', true],
            [null, ['t', 'e', 's', 't'], [116, 101, 115, 116], '', true],
            ['test', ['t', 'e', 's', 't'], [116, 101, 115, 116], '', true],
            ['test', null, null, 'L', true],
            ['test', null, null, 'R', true],
        ];
    }

    /**
     * @throws \Com\Tecnick\Unicode\Exception
     */
    #[DataProvider('bidiStrDataProvider')]
    public function testBidiStr(string $str, mixed $expected, string $forcedir = ''): void
    {
        $bidi = new Bidi($str, null, null, $forcedir, true);
        $this->assertEquals($expected, $bidi->getString());
    }

    /**
     * @return array<int, array{string, string, string}>
     */
    public static function bidiStrDataProvider(): array
    {
        return [
            [
                "\n\nABC\nEFG\n\nHIJ\n\n",
                "\n\nABC\nEFG\n\nHIJ\n\n",
                'L',
            ],
            [
                self::decodeJsonString('"\u202EABC\u202C"'),
                'CBA',
                '',
            ],
            [
                // All-L text in a forced-RTL paragraph: N1 resolves the spaces to L,
                // so the whole phrase is a single LTR run and keeps its word order.
                'left to right',
                'left to right',
                'R',
            ],
            [
                // Same, but L1.4 resets the trailing space to the paragraph level,
                // moving it to the visual left.
                'left to right ',
                ' left to right',
                'R',
            ],
            [
                // U+0600..U+0602 are Arabic numbers (AN): I1 puts them on an even level,
                // so they keep their logical order inside the right-to-left paragraph.
                self::decodeJsonString('"smith (fabrikam \u0600\u0601\u0602) \u05de\u05d6\u05dc"'),
                self::decodeJsonString('"\u05dc\u05d6\u05de (\u0600\u0601\u0602 fabrikam) smith"'),
                'R',
            ],
            [
                self::decodeJsonString('"\u0600\u0601\u0602 book(s)"'),
                self::decodeJsonString('"book(s) \u0600\u0601\u0602"'),
                'R',
            ],
            [
                self::decodeJsonString('"\u0600\u0601(\u0602\u0603[&ef]!)gh"'),
                self::decodeJsonString('"gh(![ef&]\u0602\u0603)\u0600\u0601"'),
                'R',
            ],
            [
                'تشكيل اختبار',
                'ﺭﺎﺒﺘﺧﺍ ﻞﻴﻜﺸﺗ',
                '',
            ],
            [
                self::decodeJsonString('"\u05de\u05d6\u05dc \u05d8\u05d5\u05d1"'),
                self::decodeJsonString('"\u05d1\u05d5\u05d8 \u05dc\u05d6\u05de"'),
                '',
            ],
            [
                self::decodeJsonString(
                    '"\u0644\u0644\u0647 \u0600\u0601\u0602 \uFB50'
                    . ' \u0651\u064c\u0651\u064d\u0651\u064e\u0651\u064f\u0651\u0650'
                    . ' \u0644\u0622"',
                ),
                // "\u0644\u0644\u0647" is not the word Allah: without the alef it shapes as
                // lam initial, lam medial and heh final instead of the U+FDF2 ligature.
                self::decodeJsonString(
                    '"\ufef5 \ufc62\ufc61\ufc60\ufc5f\ufc5e \ufb50 \u0600\u0601\u0602 \ufeea\ufee0\ufedf"',
                ),
                '',
            ],
            [
                self::decodeJsonString('"A\u2067\u05d8\u2069B"'),
                self::decodeJsonString('"A\u2067\u05d8\u2069B"'),
                '',
            ],
            [
                // Unterminated isolate: RLI with no matching PDI. Exercises StepXten's
                // findMatchingPdiStart() returning -1 and the eos-from-paragraph-level fallback.
                self::decodeJsonString('"\u05d0\u2067\u05d1"'),
                self::decodeJsonString('"\u05d1\u2067\u05d0"'),
                '',
            ],
            [
                // Unterminated FSI wrapping LTR text: covers FSI auto-direction with no matching PDI.
                self::decodeJsonString('"\u05d0\u2068ab\u05d1"'),
                self::decodeJsonString('"ab\u05d1\u2068\u05d0"'),
                '',
            ],
            [
                // RLI + PDI
                self::decodeJsonString('"The words \"\u2067\u05de\u05d6\u05dc [mazel] \u05d8\u05d5\u05d1 [tov]\u2069\"'
                . ' mean \"Congratulations!\""'),
                'The words "⁧[tov] בוט [mazel] לזמ⁩" mean "Congratulations!"',
                '',
            ],
            [
                // RLE + PDF. The all-L phrase inside the embedding stays one LTR run,
                // and the legacy embedding spills over: sos of the run after PDF is R, so N1
                // resolves '" - ' between it and the number to R, pulling '" - $19.95' into
                // the RTL context (the spillover problem that isolates were made to solve).
                self::decodeJsonString('"it is called \"\u202bAN INTRODUCTION TO java\u202c\" - $19.95 in hardcover."'),
                'it is called "$19.95 - "AN INTRODUCTION TO java in hardcover.',
                '',
            ],
            [
                // RLI + PDI: the isolate shields the surrounding text, so unlike the RLE
                // case above the quote and price stay in place and the phrase keeps its
                // word order.
                self::decodeJsonString('"it is called \"\u2067AN INTRODUCTION TO java\u2069\" - $19.95 in hardcover."'),
                'it is called "⁧AN INTRODUCTION TO java⁩" - $19.95 in hardcover.',
                '',
            ],
            [
                // Hebrew with embedded paragraph separator (covers getParagraphs() splitting and re-insertion)
                self::decodeJsonString('"\u05de\u05d6\u05dc \u05d8\u05d5\u05d1"')
                    . "\n"
                    . self::decodeJsonString('"\u05de\u05d6\u05dc \u05d8\u05d5\u05d1"'),
                self::decodeJsonString('"\u05d1\u05d5\u05d8 \u05dc\u05d6\u05de"')
                    . "\n"
                    . self::decodeJsonString('"\u05d1\u05d5\u05d8 \u05dc\u05d6\u05de"'),
                '',
            ],
            [
                // Hebrew ending with paragraph separator (covers empty last paragraph handling)
                self::decodeJsonString('"\u05de\u05d6\u05dc \u05d8\u05d5\u05d1"') . "\n",
                self::decodeJsonString('"\u05d1\u05d5\u05d8 \u05dc\u05d6\u05de"') . "\n",
                '',
            ],
            [
                // Arabic with forced LTR direction (covers getPel() returning 0 for forcedir='L').
                // N1 resolves the space between the two R words, so the phrase is a single RTL
                // run inside the LTR paragraph and is reversed as a unit (words swap places).
                'تشكيل اختبار',
                self::decodeJsonString('"\ufead\ufe8e\ufe92\ufe98\ufea7\ufe8d\u0020\ufede\ufef4\ufedc\ufeb8\ufe97"'),
                'L',
            ],
        ];
    }

    /**
     * Regression test for https://github.com/tecnickcom/tc-lib-unicode/issues/12
     * Arabic shaping must replace a lam-alef pair with a single ligature glyph
     * without deleting any other character of the run.
     *
     * @param array<int> $expected Shaped codepoints in visual order
     *
     * @throws \Com\Tecnick\Unicode\Exception
     */
    #[DataProvider('lamAlefShapingDataProvider')]
    public function testLamAlefShaping(string $str, array $expected): void
    {
        $bidi = new Bidi($str);
        $this->assertSame($expected, $bidi->getOrdArray());
    }

    /**
     * @return array<int, array{string, array<int>}>
     */
    public static function lamAlefShapingDataProvider(): array
    {
        return [
            // khah + lam + alef + lam: the pair merges and the khah keeps its initial form
            ['خلال', [0xFEDD, 0xFEFC, 0xFEA7]],
            // nine letters with one lam-alef pair: one glyph less, leading alef preserved
            ['الاستخدام', [0xFEE1, 0xFE8D, 0xFEAA, 0xFEA8, 0xFE98, 0xFEB3, 0xFEFB, 0xFE8D]],
            // two adjacent lam-alef pairs merge independently
            ['لالا', [0xFEFB, 0xFEFB]],
            // both words shape identically (the second merge must not delete the first char)
            ['خلال خلال', [0xFEDD, 0xFEFC, 0xFEA7, 0x0020, 0xFEDD, 0xFEFC, 0xFEA7]],
            // NSM between the pair: the ligature still forms around the shadda
            ['لّا', [0xFEFB, 0x0651]],
        ];
    }

    /**
     * The Arabic shaper picks the presentation form from the Joining_Type of the
     * surrounding characters: transparent marks are skipped, ZWJ joins, ZWNJ and every
     * non-joining character break the connection.
     *
     * @param array<int> $expected Shaped codepoints in visual order
     *
     * @throws \Com\Tecnick\Unicode\Exception
     */
    #[DataProvider('joiningShapingDataProvider')]
    public function testJoiningShaping(string $str, array $expected): void
    {
        $bidi = new Bidi($str);
        $this->assertSame($expected, $bidi->getOrdArray());
    }

    /**
     * @return array<string, array{string, array<int>}>
     */
    public static function joiningShapingDataProvider(): array
    {
        return [
            // ZWNJ (U+200C) breaks the connection between two dual-joining letters
            'beh ZWNJ teh' => ["\u{0628}\u{200C}\u{062A}", [0xFE95, 0xFE8F]],
            // and it also blocks the lam-alef ligature
            'lam ZWNJ alef' => ["\u{0644}\u{200C}\u{0627}", [0xFE8D, 0xFEDD]],
            // ZWJ (U+200D) is join causing: the beh takes its initial form
            'beh ZWJ' => ["\u{0628}\u{200D}", [0xFE91]],
            // Persian needs ZWNJ inside a word
            'persian mikhaham' => [
                'می‌خواهم',
                [0xFEE2, 0xFEEB, 0xFE8D, 0xFEEE, 0xFEA7, 0xFBFD, 0xFEE3],
            ],
            // a non-joining character between two letters isolates both
            'beh comma teh' => ['ب،ت', [0xFE95, 0x060C, 0xFE8F]],
            'beh digit teh' => ['ب١ت', [0xFE95, 0x0661, 0xFE8F]],
            // letters with an isolated and a final form only
            'waw with hamza' => ['سؤال', [0xFEDD, 0xFE8D, 0xFE86, 0xFEB3]],
            'teh marbuta' => ['بةب', [0xFE8F, 0xFE94, 0xFE91]],
            // right joining letters do not connect to the following letter
            'urdu rreh' => ['بڑا', [0xFE8D, 0xFB8D, 0xFE91]],
            'alef wasla' => ['ٱلحمد', [0xFEAA, 0xFEE4, 0xFEA4, 0xFEDF, 0xFB50]],
            'uyghur' => ['ئۇيغۇر', [0xFEAD, 0xFBD8, 0xFED0, 0xFEF3, 0xFBD8, 0xFE8B]],
            // non-joining letters and punctuation are not a joining context
            'hamza' => ['شيء', [0xFE80, 0xFEF2, 0xFEB7]],
            'arabic semicolon' => ['ب؛', [0x061B, 0xFE8F]],
            'urdu full stop' => ['اب۔', [0x06D4, 0xFE8F, 0xFE8D]],
            'arabic question mark' => ['ب؟', [0x061F, 0xFE8F]],
            'two beh and question mark' => ['بب؟', [0x061F, 0xFE90, 0xFE91]],
        ];
    }

    /**
     * Shadda (U+0651) and a second mark are merged into a single glyph in both orders:
     * canonical ordering puts the vowel first, as its combining class is lower.
     *
     * @throws \Com\Tecnick\Unicode\Exception
     */
    public function testCombineShadda(): void
    {
        // BEH + SHADDA + FATHA and BEH + FATHA + SHADDA
        $bidi = new Bidi(null, null, [0x0628, 0x0651, 0x064E]);
        $this->assertSame([0xFC60, 0xFE8F], $bidi->getOrdArray());

        $bidi = new Bidi(null, null, [0x0628, 0x064E, 0x0651]);
        $this->assertSame([0xFC60, 0xFE8F], $bidi->getOrdArray());

        // BEH + SHADDA + SUPERSCRIPT ALEF
        $bidi = new Bidi(null, null, [0x0628, 0x0651, 0x0670]);
        $this->assertSame([0xFC63, 0xFE8F], $bidi->getOrdArray());
    }

    /**
     * The U+FDF2 ligature covers alef + lam + lam + heh and has an isolated form only.
     *
     * @param array<int> $expected Shaped codepoints in visual order
     *
     * @throws \Com\Tecnick\Unicode\Exception
     */
    #[DataProvider('allahShapingDataProvider')]
    public function testAllahShaping(string $str, array $expected): void
    {
        $bidi = new Bidi($str);
        $this->assertSame($expected, $bidi->getOrdArray());
    }

    /**
     * @return array<string, array{string, array<int>}>
     */
    public static function allahShapingDataProvider(): array
    {
        return [
            // the four characters become one glyph
            'allah' => ['الله', [0xFDF2]],
            // the combining marks are transparent and are kept after the ligature
            'vocalized allah' => ['اللّٰه', [0xFDF2, 0xFC63]],
            // without the alef the word is shaped letter by letter
            'lillah' => ['لله', [0xFEEA, 0xFEE0, 0xFEDF]],
            'three lam and heh' => ['للله', [0xFEEA, 0xFEE0, 0xFEE0, 0xFEDF]],
            // the alef joins the beh, so the isolated ligature cannot be used
            'billah' => ['بالله', [0xFEEA, 0xFEE0, 0xFEDF, 0xFE8E, 0xFE91]],
        ];
    }

    /**
     * Regression test for https://github.com/tecnickcom/tc-lib-unicode/issues/13
     * Rules N1/N2 must resolve ordinary neutrals (WS, ON, S), not only the literal
     * 'NI'-typed isolate formatting characters: a neutral between two strong characters
     * of the same direction takes that direction, keeping multi-word runs together.
     *
     * @throws \Com\Tecnick\Unicode\Exception
     */
    #[DataProvider('neutralResolutionDataProvider')]
    public function testNeutralResolution(string $str, string $expected, string $forcedir): void
    {
        $bidi = new Bidi($str, null, null, $forcedir, false);
        $this->assertSame($expected, $bidi->getString());
    }

    /**
     * @return array<int, array{string, string, string}>
     */
    public static function neutralResolutionDataProvider(): array
    {
        // The issue's third case (all-Latin text forced RTL) is covered by
        // bidiStrDataProvider: shaping is a no-op for ASCII input.
        return [
            // RTL paragraph with a two-word Latin phrase: the phrase keeps its word order
            ['تجربة - John Doe',      'John Doe - ةبرجت',      ''],
            // LTR paragraph with a two-word Arabic phrase: reversed as a single run
            ['John Doe - تجربة خاصة', 'John Doe - ةصاخ ةبرجت', ''],
        ];
    }

    /**
     * Test Bidi with edge-case ordarr inputs: a negative codepoint and a Private Use Area
     * codepoint as the last character of the paragraph.
     *
     * @throws \Com\Tecnick\Unicode\Exception
     */
    public function testBidiWithSpecialOrdarr(): void
    {
        // Negative codepoint as last char: covers the $lastchar < 0 branch
        $bidi1 = new \Com\Tecnick\Unicode\Bidi(null, null, [0x05D0, -1], 'R', false);
        $this->assertEquals([-1, 1488], $bidi1->getOrdArray());

        // Codepoint 0xE001 (Private Use Area): type L, so it forms its own left-to-right run
        $bidi2 = new \Com\Tecnick\Unicode\Bidi(null, null, [0x05D0, 0xE001], 'R', false);
        $this->assertEquals([57345, 1488], $bidi2->getOrdArray());
    }

    /**
     * X9 removes the explicit formatting characters also when the text holds no
     * right-to-left character, so the left-to-right fast path cannot return them.
     *
     * @throws \Com\Tecnick\Unicode\Exception
     */
    public function testFormattingCharactersAreRemoved(): void
    {
        $bidi = new Bidi(self::decodeJsonString('"a\u202Ab\u202Cc"'));
        $this->assertSame('abc', $bidi->getString());

        $bidi = new Bidi(self::decodeJsonString('"a\u2066b\u2069c"'));
        $this->assertSame(
            self::decodeJsonString('"a\u2066b\u2069c"'),
            $bidi->getString(),
            'the isolate formatting characters are retained',
        );
    }

    /**
     * The string, char array and codepoint array forms of the input must describe the
     * same text: a different number of characters is an input error.
     *
     * @throws \Com\Tecnick\Unicode\Exception
     */
    public function testMismatchedInputForms(): void
    {
        $this->bcExpectException(\Com\Tecnick\Unicode\Exception::class);
        new Bidi('test', null, [0x05D0]);
    }

    /**
     * Conformance repros taken from the official BidiCharacterTest.txt suite, checked
     * against the codepoint sequence the UBA produces (shaping disabled).
     *
     * @param array<int> $ordarr   Input codepoints
     * @param array<int> $expected Expected codepoints in visual order
     *
     * @throws \Com\Tecnick\Unicode\Exception
     */
    #[DataProvider('ubaOrdArrDataProvider')]
    public function testUbaOrdArr(array $ordarr, string $forcedir, array $expected): void
    {
        $bidi = new Bidi(null, null, $ordarr, $forcedir, false);
        $this->assertSame($expected, \array_values($bidi->getOrdArray()));
    }

    /**
     * @return array<string, array{array<int>, string, array<int>}>
     */
    public static function ubaOrdArrDataProvider(): array
    {
        return [
            // X5c: the direction of an FSI comes from the content between it and its
            // matching PDI, so an FSI wrapping Hebrew text acts as an RLI.
            'FSI with RTL content' => [
                [0x2068, 0x05D0, 0x0021, 0x2069, 0x0061, 0x0062],
                '',
                [0x2068, 0x0021, 0x05D0, 0x2069, 0x0061, 0x0062],
            ],
            // X5c with no matching PDI: the scan runs to the end of the paragraph.
            'unterminated FSI with RTL content' => [
                [0x2068, 0x05D0, 0x0061],
                '',
                [0x2068, 0x0061, 0x05D0],
            ],
            // X6a: a PDI matching no isolate initiator keeps the level of the current stack
            // entry and is retained by X9 instead of being deleted.
            'unmatched PDI is retained' => [
                [0x05D0, 0x2069, 0x05D1],
                '',
                [0x05D1, 0x2069, 0x05D0],
            ],
            // W4: a European separator only joins two European numbers, so the plus sign
            // between two Arabic-Indic digits stays a separator and N1 resolves it as R.
            'ES does not join two Arabic numbers' => [
                [0x0660, 0x002B, 0x0661],
                'R',
                [0x0661, 0x002B, 0x0660],
            ],
            // Same rule with a hyphen between two European numbers resolved to AN by W2.
            'ES after an Arabic letter' => [
                [0x0028, 0x0627, 0x0029, 0x0020, 0x0031, 0x002D, 0x0032],
                'L',
                [0x0028, 0x0627, 0x0029, 0x0020, 0x0032, 0x002D, 0x0031],
            ],
            // BD14/BD15: a bracket whose current type is not ON (here retyped by an
            // override) is not part of a bracket pair.
            'brackets retyped by an override do not pair' => [
                [0x202E, 0x0028, 0x202C, 0x202B, 0x05D0, 0x0062, 0x0029, 0x0063, 0x202C],
                'R',
                [0x0062, 0x0029, 0x0063, 0x05D0, 0x0029],
            ],
        ];
    }

    /**
     * BD16 uses a fixed 63 element stack: when a 64th opening bracket is found, bracket
     * pairing stops for the remainder of the isolating run sequence and the pairs found
     * so far are kept.
     *
     * @throws \Com\Tecnick\Unicode\Exception
     */
    public function testBracketStackLimit(): void
    {
        $ordarr = \array_merge([0x0061], \array_fill(0, 64, 0x0028), [0x0062], \array_fill(0, 64, 0x0029));

        // None of the 64 pairs is resolved to L by N0, so N1 leaves the brackets at the
        // paragraph level: the whole line is reversed and every bracket is mirrored.
        $expected = \array_merge(\array_fill(0, 64, 0x0028), [0x0061], \array_fill(0, 64, 0x0028), [0x0062]);

        $bidi = new Bidi(null, null, $ordarr, 'R', false);
        $this->assertSame($expected, \array_values($bidi->getOrdArray()));
    }
}
