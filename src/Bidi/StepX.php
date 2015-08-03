<?php
/**
 * StepX.php
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
 * Com\Tecnick\Unicode\Bidi\StepX
 *
 * @since       2015-07-13
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 */
class StepX
{
    /**
     * Maximum embedding level
     */
    const MAXEL = 62;
    
    /**
     * Current Embedding Level
     *
     * @var int
     */
    protected $cel = 0;
    
    /**
     * Directional override status
     *
     * @var string
     */
    protected $dos = 'N';

    /**
     * Start-of-level-run
     *
     * @var int
     */
    protected $sor = 'L';

    /**
     * End-of-level-run
     *
     * @var int
     */
    protected $eor = 'L';

    /**
     * Temporary array of characters
     *
     * @var array
     */
    protected $tmpchrdata = array();

    /**
     * Array of characters data to return
     *
     * @var array
     */
    protected $chardata = array();

    /**
     * Array of UTF-8 codepoints
     *
     * @var array
     */
    protected $ordarr = array();

    /**
     * Array of UTF-8 codepoints for the X7 step check (in)
     *
     * @var array
     */
    protected $checkX7In = array(
        UniConstant::RLE,
        UniConstant::LRE,
        UniConstant::RLO,
        UniConstant::LRO
    );

    /**
     * Array of UTF-8 codepoints for the X7 step check (out)
     *
     * @var array
     */
    protected $checkX7Out = array(
        UniConstant::RLE,
        UniConstant::LRE,
        UniConstant::RLO,
        UniConstant::LRO,
        UniConstant::PDF
    );

    /**
     * X Steps for Bidirectional algorithm
     *
     * @param array  $ordarr   Array of UTF-8 codepoints
     * @param int    $pel      Paragraph embedding level
     */
    public function __construct($ordarr, $pel)
    {
        $this->ordarr = $ordarr;
        $this->cel = $pel;
        $this->dos = 'N';
        $this->sor = (($pel === 0) ? 'L' : 'R');
        $this->eor = $this->sor;
        $this->tmpchrdata = array();
        $this->chardata = array();
        $this->processX();
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
     * Process
     */
    protected function processX()
    {
        // X1. Begin by setting the current embedding level to the paragraph embedding level.
        //     Set the directional override status to neutral.
        //     Process each character iteratively, applying rules X2 through X9.
        //     Only embedding levels from 0 to 61 are valid in this phase.
        //     In the resolution of levels in rules I1 and I2, the maximum embedding level of 62 can be reached.
        foreach ($this->ordarr as $ord) {
            switch ($ord) {
                case UniConstant::RLE:
                    // X2. With each RLE, compute the least greater odd embedding level.
                    //     a. If this new level would be valid, then this embedding code is valid.
                    //        Remember (push) the current embedding level and override status.
                    //        Reset the current level to this new level, and reset the override status to neutral.
                    //     b. If the new level would not be valid, then this code is invalid.
                    //        Do not change the current level or override status.
                    $this->setTmpData(($this->cel + ($this->cel % 2) + 1), UniConstant::RLE, 'N');
                    break;
                case UniConstant::LRE:
                    // X3. With each LRE, compute the least greater even embedding level.
                    //     a. If this new level would be valid, then this embedding code is valid.
                    //        Remember (push) the current embedding level and override status.
                    //        Reset the current level to this new level, and reset the override status to neutral.
                    //     b. If the new level would not be valid, then this code is invalid.
                    //        Do not change the current level or override status.
                    $this->setTmpData(($this->cel + 2 - ($this->cel % 2)), UniConstant::LRE, 'N');
                    break;
                case UniConstant::RLO:
                    // X4. With each RLO, compute the least greater odd embedding level.
                    //     a. If this new level would be valid, then this embedding code is valid.
                    //        Remember (push) the current embedding level and override status.
                    //        Reset the current level to this new level, and reset the override status to right-to-left.
                    //     b. If the new level would not be valid, then this code is invalid.
                    //        Do not change the current level or override status.
                    $this->setTmpData(($this->cel + ($this->cel % 2) + 1), UniConstant::RLO, 'R');
                    break;
                case UniConstant::LRO:
                    // X5. With each LRO, compute the least greater even embedding level.
                    //     a. If this new level would be valid, then this embedding code is valid.
                    //        Remember (push) the current embedding level and override status.
                    //        Reset the current level to this new level, and reset the override status to left-to-right.
                    //     b. If the new level would not be valid, then this code is invalid.
                    //        Do not change the current level or override status.
                    $this->setTmpData(($this->cel + 2 - ($this->cel % 2)), UniConstant::LRO, 'L');
                    break;
                case UniConstant::PDF:
                    // X7. With each PDF, determine the matching embedding or override code.
                    //     If there was a valid matching code,
                    //     restore (pop) the last remembered (pushed) embedding level and directional override.
                    $this->processPdfCase();
                    break;
                default:
                    $this->processChar($ord);
                    break;
            }
        } // end for each char

        // X8. All explicit directional embeddings and overrides are completely terminated at the end of each paragraph.
        //     Paragraph separators are not included in the embedding.
        // X9. Remove all RLE, LRE, RLO, LRO, PDF, and BN codes.
        // X10. The remaining rules are applied to each run of characters at the same level.
        //      For each run, determine the start-of-level-run (sor) and end-of-level-run (eor) type, either L or R.
        //      This depends on the higher of the two levels on either side of the boundary
        //      (at the start or end of the paragraph, the level of the 'other' run is the base embedding level).
        //      If the higher level is odd, the type is R; otherwise, it is L.
    }

    /**
     * Set temporary data
     *
     * @param int    $nextlevel Next level
     * @param int    $num       Char code
     * @param string $dos       Directional override status
     */
    protected function setTmpData($nextlevel, $num, $dos)
    {
        if ($nextlevel >= self::MAXEL) {
            return;
        }
        $this->tmpchrdata[] = array('num' => $num, 'cel' => $this->cel, 'dos' => $this->dos);
        $this->cel = $nextlevel;
        $this->dos = $dos;
        $this->sor = $this->eor;
        $this->eor = (($this->cel === 0) ? 'L' : 'R');
    }

    /**
     * Process the PDF type character
     */
    protected function processPdfCase()
    {
        if (!empty($this->tmpchrdata)) {
            $lastel = end($this->tmpchrdata);
            if (in_array($lastel['num'], $this->checkX7In)) {
                $match = array_pop($this->tmpchrdata);
                $this->cel = $match['cel'];
                $this->dos = $match['dos'];
                $this->sor = $this->eor;
                $this->eor = (((($this->cel>$match['cel']) ? $this->cel : $match['cel']) === 0) ? 'L' : 'R');
            }
        }
    }

    /**
     * Process normal char
     *
     * @param int $ord Unicode value
     */
    protected function processChar($ord)
    {
        if (!in_array($ord, $this->checkX7Out)) {
            // X6. For all types besides RLE, LRE, RLO, LRO, and PDF:
            //    a. Set the level of the current character to the current embedding level.
            //    b. Whenever the directional override status is not neutral,
            //       reset the current character type to the directional override status.
            if ($this->dos !== 'N') {
                $chardir = $this->dos;
            } else {
                $chardir = (isset(UniType::$uni[$ord]) ? UniType::$uni[$ord] : 'L');
            }
            // stores string characters and other information
            $this->chardata[] = array(
                'char'  => $ord,
                'level' => $this->cel,
                'type'  => $chardir,
                'sor'   => $this->sor,
                'eor'   => $this->eor
            );
        }
    }
}
