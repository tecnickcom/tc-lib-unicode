<?php
/**
 * StepN.php
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

use \Com\Tecnick\Unicode\Data\Type as UniType;
use \Com\Tecnick\Unicode\Data\Constant as UniConstant;

/**
 * Com\Tecnick\Unicode\Bidi\StepN
 *
 * @since       2015-07-13
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 */
class StepN extends \Com\Tecnick\Unicode\Bidi\StepBase
{
    /**
     * Process N steps
     */
    protected function process()
    {
        $this->processStep('processN1');
    }

    /**
     * N1. A sequence of neutrals takes the direction of the surrounding strong text
     *     if the text on both sides has the same direction.
     *     European and Arabic numbers act as if they were R in terms of their influence on neutrals.
     *     Start-of-level-run (sor) and end-of-level-run (eor) are used at level run boundaries.
     *
     * @param int $idx       Current character position
     * @param int $levcount  Level count
     * @param int $prevlevel Previous level
     */
    protected function processN1($idx, $levcount, $prevlevel)
    {
        if ($this->isMiddleChar($idx, $levcount, $prevlevel)) {
            $this->setNCharType($idx, $this->chardata[($idx-1)]['type'], $this->chardata[($idx + 1)]['type']);
        } elseif ($this->isFirstChar($idx, $levcount, $prevlevel)) {
            $this->setNCharType($idx, $this->chardata[$idx]['sor'], $this->chardata[($idx + 1)]['type']);
        } elseif ($this->isLastChar($idx, $levcount, $prevlevel)) {
            $this->setNCharType($idx, $this->chardata[($idx - 1)]['type'], $this->chardata[$idx]['eor']);
        } elseif ($this->chardata[$idx]['type'] == 'N') {
            // N2. Any remaining neutrals take the embedding direction
            $this->chardata[$idx]['type'] = $this->chardata[$idx]['sor'];
        }
    }

    /**
     * Check if it is the first character
     *
     * @param int $idx       Current character position
     * @param int $levcount  Level count
     * @param int $prevlevel Previous level
     *
     * @return bool
     */
    protected function isMiddleChar($idx, $levcount, $prevlevel)
    {
        return (($levcount > 0)
            && (($idx + 1) < $this->numchars)
            && ($this->chardata[($idx + 1)]['level'] == $prevlevel));
    }

    /**
     * Check if it is the first character
     *
     * @param int $idx       Current character position
     * @param int $levcount  Level count
     * @param int $prevlevel Previous level
     *
     * @return bool
     */
    protected function isFirstChar($idx, $levcount, $prevlevel)
    {
        return (($levcount == 0)
            && (($idx + 1) < $this->numchars)
            && ($this->chardata[($idx + 1)]['level'] == $prevlevel));
    }

    /**
     * Check if it is the Last character
     *
     * @param int $idx       Current character position
     * @param int $levcount  Level count
     * @param int $prevlevel Previous level
     *
     * @return bool
     */
    protected function isLastChar($idx, $levcount, $prevlevel)
    {
        return (($levcount > 0) && ((($idx + 1) == $this->numchars)
            || ((($idx + 1) < $this->numchars) && ($this->chardata[($idx + 1)]['level'] != $prevlevel))));
    }

    /**
     * Set character type
     *
     * @param int $idx    Position of the current character
     * @param int $left   Left character
     * @param int $right  Right character
     */
    protected function setNCharType($idx, $left, $right)
    {
        if ($left.$this->chardata[$idx]['type'].$right == 'LNL') {
            $this->chardata[$idx]['type'] = 'L';
        }
        if ($this->chardata[$idx]['type'] == 'N') {
            $rarr = array('R', 'EN', 'AN');
            if (in_array($left, $rarr) && in_array($right, $rarr)) {
                $this->chardata[$idx]['type'] = 'R';
            } else {
                // N2. Any remaining neutrals take the embedding direction
                $this->chardata[$idx]['type'] = $this->chardata[$idx]['sor'];
            }
        }
    }
}
