<?php
/**
 * StepW.php
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

namespace Com\Tecnick\Unicode\Bidi;

use \Com\Tecnick\Unicode\Data\Constant as UniConstant;

/**
 * Com\Tecnick\Unicode\Bidi\StepW
 *
 * @since       2015-07-13
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 */
class StepW extends \Com\Tecnick\Unicode\Bidi\StepBase
{
    /**
     * Process W steps
     */
    protected function process()
    {
        for ($step = 1; $step <= 7; ++$step) {
            $this->processStep('processW'.$step);
        }
    }

    /**
     * Gent Next Valid Char
     *
     * @param int $idx Current char index
     * 
     * @return int
     */
    protected function getNextValidChar($idx)
    {
        if ($idx >= ($this->numchars - 1)) {
            return -1;
        }
        ++$idx;
        while (($idx < $this->numchars) && ($this->chardata[$idx]['type'] == 'BN')) {
            ++$idx;
        }
        if ($idx == $this->numchars) {
            return -1;
        }
        return $idx;
    }

    /**
     * Gent Previous Valid Char
     *
     * @param int $idx Current char index
     * 
     * @return int
     */
    protected function getPreviousValidChar($idx)
    {
        if ($idx <= 0) {
            return -1;
        }
        --$idx;
        while (($idx > -1) && ($this->chardata[$idx]['type'] == 'BN')) {
            --$idx;
        }
        return $idx;
    }

    /**
     * W1. Examine each nonspacing mark (NSM) in the isolating run sequence, and
     *     change the type of the NSM to Other Neutral if the previous character is an isolate initiator or PDI, and
     *     to the type of the previous character otherwise.
     *     If the NSM is at the start of the isolating run sequence, it will get the type of sos.
     *     (Note that in an isolating run sequence, an isolate initiator followed by an NSM or any type
     *     other than PDI must be an overflow isolate initiator.)
     *
     * @param int $idx Current character position
     */
    protected function processW1($idx)
    {
        if ($this->chardata[$idx]['type'] == 'NSM') {
            $jdx = $this->getPreviousValidChar($idx);
            if ($jdx == -1) {
                $this->chardata[$idx]['type'] = $this->sos;
            } elseif (($this->chardata[$jdx]['char'] >= UniConstant::LRI)
                && ($this->chardata[$jdx]['char'] <= UniConstant::PDI)
            ) {
                $this->chardata[$idx]['type'] = 'ON';
            } else {
                $this->chardata[$idx]['type'] = $this->chardata[$jdx]['type'];
            }
        }
    }

    /**
     * W2. Search backward from each instance of a European number until the first strong type (R, L, AL, or sos)
     *     is found. If an AL is found, change the type of the European number to Arabic number.
     *
     * @param int $idx Current character position
     */
    protected function processW2($idx)
    {
        if ($this->chardata[$idx]['char'] == 'EN') {
            $jdx = $this->getPreviousValidChar($idx);
            while ($jdx > -1) {
                if (($this->chardata[$jdx]['type'] == 'R')
                    || ($this->chardata[$jdx]['type'] == 'L')
                    || ($this->chardata[$jdx]['type'] == 'AL')
                ) {
                    if ($this->chardata[$jdx]['type'] == 'AL') {
                        $this->chardata[$idx]['type'] = 'AN';
                    }
                    break;
                }
                $jdx = $this->getPreviousValidChar($jdx);
            }
        }
    }

    /**
     * W3. Change all ALs to R.
     *
     * @param int $idx Current character position
     */
    protected function processW3($idx)
    {
        if ($this->chardata[$idx]['type'] == 'AL') {
            $this->chardata[$idx]['type'] = 'R';
        }
    }

    /**
     * W4. A single European separator between two European numbers changes to a European number.
     *     A single common separator between two numbers of the same type changes to that type.
     *
     * @param int $idx Current character position
     */
    protected function processW4($idx)
    {
        if (($this->chardata[$idx]['type'] == 'ES') || ($this->chardata[$idx]['type'] == 'CS')) {
            $bdx = $this->getPreviousValidChar($idx);
            $fdx = $this->getNextValidChar($idx);
            if (($bdx >= 0) && ($fdx >= 0) && ($this->chardata[$bdx]['type'] == $this->chardata[$fdx]['type'])) {
                if (($this->chardata[$bdx]['type'] == 'EN') || ($this->chardata[$bdx]['type'] == 'AN')) {
                    $this->chardata[$idx]['type'] = $this->chardata[$bdx]['type'];
                }
            }
        }
    }

    /**
     * W5. A sequence of European terminators adjacent to European numbers changes to all European numbers.
     *
     * @param int $idx Current character position
     */
    protected function processW5($idx)
    {
        if ($this->chardata[$idx]['type'] == 'ET') {
            if (($levcount > 0) && ($this->chardata[($idx - 1)]['type'] == 'EN')) {
                $this->chardata[$idx]['type'] = 'EN';
            } else {
                $jdx = ($idx + 1);
                while (($jdx < $this->numchars) && ($this->chardata[$jdx]['level'] == $prevlevel)) {
                    if ($this->chardata[$jdx]['type'] == 'EN') {
                        $this->chardata[$idx]['type'] = 'EN';
                        break;
                    } elseif ($this->chardata[$jdx]['type'] != 'ET') {
                        break;
                    }
                    ++$jdx;
                }
            }
        }
    }

    /**
     * W6. Otherwise, separators and terminators change to Other Neutral.
     *
     * @param int $idx       Current character position
     */
    protected function processW6($idx)
    {
        if (($this->chardata[$idx]['type'] == 'ET')
            || ($this->chardata[$idx]['type'] == 'ES')
            || ($this->chardata[$idx]['type'] == 'CS')
        ) {
            $this->chardata[$idx]['type'] = 'ON';
        }
    }

    /**
     * W7. Search backward from each instance of a European number until the first strong type (R, L, or sor) is found.
     *     If an L is found, then change the type of the European number to L.
     *
     * @param int $idx       Current character position
     * @param int $levcount  Level count
     */
    protected function processW7($idx, $levcount)
    {
        if ($this->chardata[$idx]['char'] == 'EN') {
            for ($jdx = $levcount; $jdx >= 0; --$jdx) {
                if ($this->chardata[$jdx]['type'] == 'L') {
                    $this->chardata[$idx]['type'] = 'L';
                } elseif ($this->chardata[$jdx]['type'] == 'R') {
                    break;
                }
            }
        }
    }
}
