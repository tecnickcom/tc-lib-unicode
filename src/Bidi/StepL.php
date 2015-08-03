<?php
/**
 * StepL.php
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

use \Com\Tecnick\Unicode\Bidi\Shaping;
use \Com\Tecnick\Unicode\Data\Type as UniType;
use \Com\Tecnick\Unicode\Data\Constant as UniConstant;
use \Com\Tecnick\Unicode\Data\Mirror as UniMirror;

/**
 * Com\Tecnick\Unicode\Bidi\StepL
 *
 * @since       2015-07-13
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 */
class StepL
{
    /**
     * Array of characters data to return
     *
     * @var array
     */
    protected $chardata = array();

    /**
     * Number of characters in $this->chardata
     *
     * @var int
     */
    protected $numchars = 0;

    /**
     * Max level
     *
     * @var int
     */
    protected $maxlevel = 0;

    /**
     * Paragraph embedding level
     *
     * @var int
     */
    protected $pel = 0;

    /**
     * L steps
     *
     * @param array $chardata Array of characters data
     * @param int   $maxlevel Maximum level
     * @param int   $pel      Paragraph embedding level
     * @param bool  $shaping  If true process character shaping (i.e. Arabic)
     */
    public function __construct($chardata, $maxlevel, $pel, $shaping = false)
    {
        $this->chardata = $chardata;
        $this->numchars = count($this->chardata);
        $this->maxlevel = $maxlevel;
        $this->pel = $pel;
        $this->processL1();
        if ($shaping) {
            $this->processShaping();
        }
        $this->processL2();
    }

    /**
     * Returns the processed array
     *
     * @return array
     */
    public function getChrData()
    {
        return $this->chardata;
    }

    /**
     * L1. On each line, reset the embedding level of the following characters to the paragraph embedding level:
     *     1. Segment separators,
     *     2. Paragraph separators,
     *     3. Any sequence of whitespace characters preceding a segment separator or paragraph separator, and
     *     4. Any sequence of white space characters at the end of the line.
     */
    protected function processL1()
    {
        for ($idx = 0; $idx < $this->numchars; ++$idx) {
            if (($this->chardata[$idx]['type'] == 'B') || ($this->chardata[$idx]['type'] == 'S')) {
                $this->chardata[$idx]['level'] = $this->pel;
            } elseif ($this->chardata[$idx]['type'] == 'WS') {
                $this->processL1b($idx);
            }
        }
    }

    /**
     * Internal L1 step
     *
     * @param int $idx Main character index
     */
    protected function processL1b($idx)
    {
        $jdx = ($idx + 1);
        while ($jdx < $this->numchars) {
            if ((($this->chardata[$jdx]['type'] == 'B') || ($this->chardata[$jdx]['type'] == 'S'))
                || (($jdx == ($this->numchars - 1)) && ($this->chardata[$jdx]['type'] == 'WS'))
            ) {
                $this->chardata[$idx]['level'] = $this->pel;
                break;
            } elseif ($this->chardata[$jdx]['type'] != 'WS') {
                break;
            }
            ++$jdx;
        }
    }

    /**
     * L2. From the highest level found in the text to the lowest odd level on each line,
     *     including intermediate levels not actually present in the text,
     *     reverse any contiguous sequence of characters that are at that level or higher.
     */
    protected function processL2()
    {
        for ($jdx = $this->maxlevel; $jdx > 0; --$jdx) {
            $ordarray = array();
            $revarr = array();
            $onlevel = false;
            for ($idx = 0; $idx < $this->numchars; ++$idx) {
                if ($this->chardata[$idx]['level'] >= $jdx) {
                    $onlevel = true;
                    if (isset(UniMirror::$uni[$this->chardata[$idx]['char']])) {
                        // L4. A character is depicted by a mirrored glyph if and only if
                        //     (a) the resolved directionality of that character is R, and
                        //     (b) the Bidi_Mirrored property value of that character is true.
                        $this->chardata[$idx]['char'] = UniMirror::$uni[$this->chardata[$idx]['char']];
                    }
                    $revarr[] = $this->chardata[$idx];
                } else {
                    if ($onlevel) {
                        $revarr = array_reverse($revarr);
                        $ordarray = array_merge($ordarray, $revarr);
                        $revarr = array();
                        $onlevel = false;
                    }
                    $ordarray[] = $this->chardata[$idx];
                }
            }
            if ($onlevel) {
                $revarr = array_reverse($revarr);
                $ordarray = array_merge($ordarray, $revarr);
            }
            $this->chardata = $ordarray;
        }
    }

    /**
     * Shaping
     * Cursively connected scripts, such as Arabic or Syriac,
     * require the selection of positional character shapes that depend on adjacent characters.
     * Shaping is logically applied after the Bidirectional Algorithm is used and is limited to
     * characters within the same directional run.
     */
    protected function processShaping()
    {
        $shaping = new Shaping($this->chardata, $this->numchars);
        $this->chardata = $shaping->getChrData();
        $this->numchars = count($this->chardata);
    }
}
