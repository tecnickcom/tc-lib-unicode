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
     * Paragraph embedding level
     *
     * @var int
     */
    protected $pel = 0;

    /**
     * L steps
     *
     * @param array $chardata Array of characters data
     * @param int   $pel      Paragraph embedding level
     */
    public function __construct($chardata, $pel)
    {
        $this->chardata = $chardata;
        $this->numchars = count($this->chardata);
        $this->pel = $pel;
        $this->processL1();
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
            if (($this->chardata[$idx]['otype'] == 'B') || ($this->chardata[$idx]['otype'] == 'S')) {
                $this->chardata[$idx]['level'] = $this->pel;
            } elseif ($this->chardata[$idx]['otype'] == 'WS') {
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
            if ((($this->chardata[$jdx]['otype'] == 'B') || ($this->chardata[$jdx]['otype'] == 'S'))
                || (($jdx == ($this->numchars - 1)) && ($this->chardata[$jdx]['otype'] == 'WS'))
            ) {
                $this->chardata[$idx]['level'] = $this->pel;
                break;
            } elseif ($this->chardata[$jdx]['otype'] != 'WS') {
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
}
