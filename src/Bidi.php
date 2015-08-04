<?php
/**
 * Bidi.php
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

namespace Com\Tecnick\Unicode;

use \Com\Tecnick\Unicode\Exception as UnicodeException;

use \Com\Tecnick\Unicode\Convert;
use \Com\Tecnick\Unicode\Bidi\StepX;
use \Com\Tecnick\Unicode\Bidi\StepW;
use \Com\Tecnick\Unicode\Bidi\StepN;
use \Com\Tecnick\Unicode\Bidi\StepI;
use \Com\Tecnick\Unicode\Bidi\StepL;
use \Com\Tecnick\Unicode\Data\Pattern as UniPattern;
use \Com\Tecnick\Unicode\Data\Type as UniType;
use \Com\Tecnick\Unicode\Data\Constant as UniConstant;

/**
 * Com\Tecnick\Unicode\Bidi
 *
 * @since       2015-07-13
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 */
class Bidi
{
    /**
     * String to process
     *
     * @var string
     */
    protected $str = '';

    /**
     * Array of UTF-8 chars
     *
     * @var array
     */
    protected $chrarr = array();

    /**
     * Array of UTF-8 codepoints
     *
     * @var array
     */
    protected $ordarr = array();

    /**
     * Processed string
     *
     * @var string
     */
    protected $bidistr = '';

    /**
     * Array of processed UTF-8 chars
     *
     * @var array
     */
    protected $bidichrarr = array();

    /**
     * Array of processed UTF-8 codepoints
     *
     * @var array
     */
    protected $bidiordarr = array();

    /**
     * If true force processign the string in RTL mode
     *
     * @var bool
     */
    protected $forcertl = false;

    /**
     * True if the string contains arabic characters
     *
     * @var bool
     */
    protected $arabic = false;

    /**
     * Number of characters
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
     * Array of character data
     *
     * @var array
     */
    protected $chardata = array();

    /**
     * Convert object
     *
     * @var Convert
     */
    protected $conv;

    /**
     * Reverse the RLT substrings using the Bidirectional Algorithm
     * http://unicode.org/reports/tr9/
     *
     * @param string $str      String to convert (if null it will be generated from $chrarr or $ordarr)
     * @param array  $chrarr   Array of UTF-8 chars (if empty it will be generated from $str or $ordarr)
     * @param array  $ordarr   Array of UTF-8 codepoints (if empty it will be generated from $str or $chrarr)
     * @param mixed  $forcertl If 'R' forces RTL, if 'L' forces LTR
     */
    public function __construct($str = null, $chrarr = null, $ordarr = null, $forcertl = false)
    {
        if (($str === null) && empty($chrarr) && empty($ordarr)) {
            throw new UnicodeException('empty input');
        }

        $this->conv = new Convert();
        if ($str === null) {
            if (empty($chrarr)) {
                $chrarr = $this->conv->ordArrToChrArr($ordarr);
            }
            $str = implode($chrarr);
        } elseif (empty($chrarr)) {
            $chrarr = $this->conv->strToChrArr($str);
        }
        if (empty($ordarr)) {
            $ordarr = $this->conv->chrArrToOrdArr($chrarr);
        }

        $this->str = $str;
        $this->chrarr = $chrarr;
        $this->ordarr = $ordarr;
        $this->forcertl = (($forcertl === false) ? false : strtoupper($forcertl[0]));
        $this->numchars = count($ordarr);

        // P1. Split the text into separate paragraphs.
        // A paragraph separator is kept with the previous paragraph.
        // Within each paragraph, apply all the other rules of this algorithm.
        // NOTE: we assume that the strings are individual paragraphs, so we skip P1.
        $this->process();
    }

    /**
     * Returns the processed array of UTF-8 codepoints
     *
     * @return array
     */
    public function getOrdArray()
    {
        return $this->bidiordarr;
    }

    /**
     * Returns the processed array of UTF-8 chars
     *
     * @return array
     */
    public function getChrArray()
    {
        if (empty($this->bidichrarr)) {
            $this->bidichrarr = $this->conv->ordArrToChrArr($this->bidiordarr);
        }
        return $this->bidichrarr;
    }

    /**
     * Returns the processed string
     *
     * @return string
     */
    public function getString()
    {
        if (empty($this->bidistr)) {
            $this->bidistr = implode($this->getChrArray());
        }
        return $this->bidistr;
    }

    /**
     * Returns an array with processed chars as keys
     *
     * @return array
     */
    public function getCharKeys()
    {
        return array_fill_keys(array_values($this->bidiordarr), true);
    }

    /**
     * Process the string
     */
    protected function process()
    {
        if (!$this->isRtlMode()) {
            $this->bidistr = $this->str;
            $this->bidichrarr = $this->chrarr;
            $this->bidiordarr = $this->ordarr;
            return;
        }

        // process data
        $this->pel = $this->getPel();
        $stepx = new StepX($this->ordarr, $this->pel);
        $stepw = new StepW($stepx->getChrData());
        $stepn = new StepN($stepw->getChrData());
        $stepi = new StepI($stepn->getChrData());
        $stepl = new StepL($stepi->getChrData(), $stepi->getMaxLevel(), $this->pel, $this->arabic);
        $this->chardata = $stepl->getChrData();
        foreach ($this->chardata as $chd) {
            $this->bidiordarr[] = $chd['char'];
        }
    }

    /**
     * Check if the input string contains RTL characters to process
     *
     * @return boolean
     */
    protected function isRtlMode()
    {
        $this->arabic = preg_match(UniPattern::ARABIC, $this->str);
        return (($this->forcertl !== false) || $this->arabic || preg_match(UniPattern::RTL, $this->str));
    }

    /**
     * Update the level of explicit directional isolates
     *
     * @return int
     */
    protected function getIsolateLevel($ord, $isolate)
    {
        if (($ord == UniConstant::LRI) || ($ord == UniConstant::RLI) || ($ord == UniConstant::FSI)) {
            ++$isolate;
        } elseif ($ord == UniConstant::PDI) {
            --$isolate;
        }
        return max(0, $isolate);
    }

    /**
     * Get the Paragraph embedding level
     *
     * @return int
     */
    protected function getPel()
    {
        if ($this->forcertl === 'R') {
            return 1;
        }
        if ($this->forcertl === 'L') {
            return 0;
        }
        // P2. In each paragraph, find the first character of type L, AL, or R
        //     while skipping over any characters between an isolate initiator and its matching PDI or,
        //     if it has no matching PDI, the end of the paragraph.
        // P3. If a character is found in P2 and it is of type AL or R,
        //     then set the paragraph embedding level to one; otherwise, set it to zero.
        $isolate = 0;
        foreach ($this->ordarr as $ord) {
            $isolate = $this->getIsolateLevel($ord, $isolate);
            if (($isolate == 0) && isset(UniType::$uni[$ord])) {
                $type = UniType::$uni[$ord];
                if ($type === 'L') {
                    return 0;
                }
                if (($type === 'R') || ($type === 'AL')) {
                    return 1;
                }
            }
        }
        return 0;
    }
}
