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
     * W1. Examine each nonspacing mark (NSM) in the level run,
     *     and change the type of the NSM to the type of the previous character.
     *     If the NSM is at the start of the level run, it will get the type of sor.
     *
     * @param int $idx       Current character position
     * @param int $levcount  Level count
     */
    protected function processW1($idx, $levcount)
    {
        if ($this->chardata[$idx]['type'] == 'NSM') {
            if ($levcount > 0) {
                $this->chardata[$idx]['type'] = $this->chardata[$idx]['sor'];
            } elseif ($idx > 0) {
                $this->chardata[$idx]['type'] = $this->chardata[($idx - 1)]['type'];
            }
        }
    }

    /**
     * W2. Search backward from each instance of a European number until the
     *     first strong type (R, L, AL, or sor) is found. If an AL is found,
     *     change the type of the European number to Arabic number.
     *
     * @param int $idx       Current character position
     * @param int $levcount  Level count
     */
    protected function processW2($idx, $levcount)
    {
        if ($this->chardata[$idx]['char'] == 'EN') {
            for ($jdx = $levcount; $jdx >= 0; --$jdx) {
                if ($this->chardata[$jdx]['type'] == 'AL') {
                    $this->chardata[$idx]['type'] = 'AN';
                } elseif (($this->chardata[$jdx]['type'] == 'L') || ($this->chardata[$jdx]['type'] == 'R')) {
                    break;
                }
            }
        }
    }

    /**
     * W3. Change all ALs to R.
     *
     * @param int $idx       Current character position
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
     * @param int $idx       Current character position
     * @param int $levcount  Level count
     * @param int $prevlevel Previous level
     */
    protected function processW4($idx, $levcount, $prevlevel)
    {
        if (($levcount > 0)
            && (($idx + 1) < $this->numchars)
            && ($this->chardata[($idx + 1)]['level'] == $prevlevel)
        ) {
            $tmp = $this->chardata[($idx-1)]['type'].$this->chardata[$idx]['type'].$this->chardata[($idx+1)]['type'];
            if (($tmp == 'ENESEN') || ($tmp == 'ENCSEN')) {
                $this->chardata[$idx]['type'] = 'EN';
            } elseif ($tmp == 'ANCSAN') {
                $this->chardata[$idx]['type'] = 'AN';
            }
        }
    }

    /**
     * W5. A sequence of European terminators adjacent to European numbers changes to all European numbers.
     *
     * @param int $idx       Current character position
     * @param int $levcount  Level count
     * @param int $prevlevel Previous level
     */
    protected function processW5($idx, $levcount, $prevlevel)
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
