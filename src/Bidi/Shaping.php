<?php

declare(strict_types=1);

/**
 * Shaping.php
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

namespace Com\Tecnick\Unicode\Bidi;

use Com\Tecnick\Unicode\Data\Arabic as UniArabic;

/**
 * Com\Tecnick\Unicode\Bidi\Shaping
 *
 * @since     2015-07-13
 * @category  Library
 * @package   Unicode
 * @author    Nicola Asuni <info@tecnick.com>
 * @copyright 2011-2026 Nicola Asuni - Tecnick.com LTD
 * @license   https://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE)
 * @link      https://github.com/tecnickcom/tc-lib-unicode
 *
 * @phpstan-import-type SeqData from \Com\Tecnick\Unicode\Bidi\Shaping\Arabic
 * @phpstan-import-type CharData from \Com\Tecnick\Unicode\Bidi\Shaping\Arabic
 */
class Shaping extends \Com\Tecnick\Unicode\Bidi\Shaping\Arabic
{
    /**
     * Unicode code for ARABIC LETTER ALEF (U+0627)
     */
    protected const ALEF = 0x0627;

    /**
     * Shaping
     * Cursively connected scripts, such as Arabic or Syriac,
     * require the selection of positional character shapes that depend on adjacent characters.
     * Shaping is logically applied after the Bidirectional Algorithm is used and is limited to
     * characters within the same directional run.
     *
     * @param SeqData         $seq       Isolated run sequence array
     * @param array<int, int> $paragraph Codepoints of the paragraph, in logical order
     */
    public function __construct(array $seq, array $paragraph = [])
    {
        $this->seq = $seq;
        $this->newchardata = $seq['item'];
        $this->paragraph = $paragraph;
        $this->process();
    }

    /**
     * Returns the processed sequence
     *
     * @return SeqData
     */
    public function getSequence(): array
    {
        return $this->seq;
    }

    /**
     * Process
     */
    protected function process(): void
    {
        $this->setJoiningContext();

        foreach ($this->seq['item'] as $idx => $item) {
            $this->shapeChar($idx, $item);
        }

        $this->combineShadda();
        $this->removeDeletedChars();
        $this->seq['item'] = \array_values($this->newchardata);
        // Keep 'length' consistent with 'item': removeDeletedChars() may have dropped entries.
        $this->seq['length'] = \count($this->seq['item']);
        $this->newchardata = []; // reset
    }

    /**
     * Replace a character with its presentation form or with the ligature it belongs to.
     *
     * @param int      $idx  Index of the sequence item
     * @param CharData $item Sequence item
     */
    protected function shapeChar(int $idx, array $item): void
    {
        $char = $item['char'];
        $pos = $item['pos'];

        if ($this->setAllahLigature($idx, $char, $pos)) {
            return;
        }

        if ($this->setLamAlefLigature($idx, $char, $pos)) {
            return;
        }

        $forms = UniArabic::SUBSTITUTE[$char] ?? null;
        if ($forms === null) {
            return;
        }

        $shaped = $forms[$this->getForm($pos)] ?? null;
        if ($shaped !== null) {
            $this->setNewChar($idx, $shaped);
        }
    }

    /**
     * Replace an alef preceded by a lam with the corresponding lam-alef ligature and
     * delete the lam. The ligature takes the final form when the lam connects to the
     * character before it.
     *
     * @param int $idx  Index of the sequence item of the alef
     * @param int $char Codepoint of the alef
     * @param int $pos  Paragraph position of the alef
     */
    protected function setLamAlefLigature(int $idx, int $char, int $pos): bool
    {
        if (!\array_key_exists($char, UniArabic::LAA)) {
            return false;
        }

        $lam = $this->getPrevPosition($pos);
        if ($lam === null || $this->getParagraphChar($lam) !== UniArabic::LAM) {
            return false;
        }

        $lamIdx = $this->seqindex[$lam] ?? null;
        if ($lamIdx === null) {
            return false;
        }

        $form = $this->joinsPrev($lam) ? self::FORM_FINAL : self::FORM_ISOLATED;
        $ligature = UniArabic::LAA[$char][$form] ?? null;
        if ($ligature === null) {
            return false;
        }

        $this->setNewChar($lamIdx, -1);
        $this->setNewChar($idx, $ligature);

        return true;
    }

    /**
     * Replace the word alef + lam + lam + heh with the ligature U+FDF2, whose
     * compatibility decomposition covers the four characters; combining marks between
     * them are kept. The ligature has an isolated form only, so it is used just when the
     * alef does not connect to the character before it and the heh does not connect to
     * the one after it.
     *
     * @param int $idx  Index of the sequence item of the heh
     * @param int $char Codepoint of the heh
     * @param int $pos  Paragraph position of the heh
     */
    protected function setAllahLigature(int $idx, int $char, int $pos): bool
    {
        if ($char !== UniArabic::HEH || $this->joinsNext($pos)) {
            return false;
        }

        $second = $this->getPrevPosition($pos);
        if ($second === null || $this->getParagraphChar($second) !== UniArabic::LAM) {
            return false;
        }

        $first = $this->getPrevPosition($second);
        if ($first === null || $this->getParagraphChar($first) !== UniArabic::LAM) {
            return false;
        }

        $alef = $this->getPrevPosition($first);
        if ($alef === null || $this->getParagraphChar($alef) !== self::ALEF || $this->joinsPrev($alef)) {
            return false;
        }

        $deleted = [];
        foreach ([$alef, $first, $second] as $position) {
            $deletedIdx = $this->seqindex[$position] ?? null;
            if ($deletedIdx === null) {
                // The word is split between two runs: shape the characters individually.
                return false;
            }

            $deleted[] = $deletedIdx;
        }

        foreach ($deleted as $deletedIdx) {
            $this->setNewChar($deletedIdx, -1);
        }

        $this->setNewChar($idx, UniArabic::LIGATURE_ALLAH_ISOLATED_FORM);

        return true;
    }

    /**
     * Combine characters that can occur with Arabic Shadda (U+0651).
     * Putting the combining mark and shadda in the same glyph allows
     * to avoid the two marks overlapping each other in an illegible manner.
     * Both orders are combined: the canonical combining class of shadda (33) is higher
     * than the one of the vowels it merges with, so normalized text has the vowel first.
     */
    protected function combineShadda(): void
    {
        $last = $this->seq['length'] - 1;
        for ($idx = 0; $idx < $last; ++$idx) {
            $currentItem = $this->newchardata[$idx] ?? null;
            $nextItem = $this->newchardata[$idx + 1] ?? null;
            assert(
                $currentItem !== null && $nextItem !== null,
                'Expected adjacent chars while combining Arabic shadda',
            );

            $cur = $currentItem['char'];
            $nxt = $nextItem['char'];

            if ($cur === UniArabic::SHADDA && $nxt >= 0) {
                $diacritic = UniArabic::DIACRITIC[$nxt] ?? null;
                if ($diacritic !== null) {
                    $this->setNewChar($idx, -1);
                    $this->setNewChar($idx + 1, $diacritic);
                }

                continue;
            }

            if ($nxt === UniArabic::SHADDA && $cur >= 0) {
                $diacritic = UniArabic::DIACRITIC[$cur] ?? null;
                if ($diacritic !== null) {
                    $this->setNewChar($idx, $diacritic);
                    $this->setNewChar($idx + 1, -1);
                }
            }
        }
    }

    /**
     * Remove marked characters
     */
    protected function removeDeletedChars(): void
    {
        foreach ($this->newchardata as $key => $value) {
            if ($value['char'] >= 0) {
                continue;
            }

            unset($this->newchardata[$key]);
        }
    }
}
