<?php
/**
 * Shaping.php
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
use \Com\Tecnick\Unicode\Data\Arabic as UniArabic;

/**
 * Com\Tecnick\Unicode\Bidi\Shaping
 *
 * @since       2015-07-13
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 */
class Shaping extends \Com\Tecnick\Unicode\Bidi\Shaping\Arabic
{
    /**
     * Array of input characters data
     *
     * @var array
     */
    protected $chardata = array();

    /**
     * Number of characters in $chardata
     *
     * @var int
     */
    protected $numchars = 0;

    /**
     * Array of processed chars
     *
     * @var array
     */
    protected $newchardata = array();

    /**
     * Array of AL characters
     *
     * @var array
     */
    protected $alchars = array();

    /**
     * Number of AL characters
     *
     * @var int
     */
    protected $numalchars = 0;

    /**
     * Shaping
     * Cursively connected scripts, such as Arabic or Syriac,
     * require the selection of positional character shapes that depend on adjacent characters.
     * Shaping is logically applied after the Bidirectional Algorithm is used and is limited to
     * characters within the same directional run.
     *
     * @param array $chardata Array of characters data
     * @param int   $numchars Number of chars in $chardata
     */
    public function __construct($chardata, $numchars)
    {
        $this->chardata = $chardata;
        $this->numchars = $numchars;
        $this->newchardata = $chardata;
        $this->process();
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
    protected function process()
    {
        $this->setAlChars();
        for ($idx = 0; $idx < $this->numchars; ++$idx) {
            if ($this->chardata[$idx]['unitype'] == 'AL') {
                $thischar = $this->chardata[$idx];
                $pos = $thischar['x'];
                $prevchar = (($pos > 0) ? $this->alchars[($pos - 1)] : false);
                $nextchar = ((($pos + 1) < $this->numalchars) ? $this->alchars[($pos + 1)] : false);
                $this->processAlChar($idx, $pos, $prevchar, $thischar, $nextchar);
            }
        }
        $this->combineShadda();
        $this->removeDeletedChars();
        $this->chardata = array_values($this->newchardata);
        unset($this->newchardata);
    }

    /**
     * Set AL chars array
     */
    protected function setAlChars()
    {
        $this->numalchars = 0;
        for ($idx = 0; $idx < $this->numchars; ++$idx) {
            if (($this->chardata[$idx]['unitype'] == 'AL')
                || ($this->chardata[$idx]['char'] == UniConstant::SPACE)
                || ($this->chardata[$idx]['char'] == UniConstant::ZERO_WIDTH_NON_JOINER)
            ) {
                $this->alchars[$this->numalchars] = $this->chardata[$idx];
                $this->alchars[$this->numalchars]['i'] = $idx;
                $this->chardata[$idx]['x'] = $this->numalchars;
                ++$this->numalchars;
            }
        }
    }

    /**
     * Combine characters that can occur with Arabic Shadda (0651 HEX, 1617 DEC).
     * Putting the combining mark and shadda in the same glyph allows
     * to avoid the two marks overlapping each other in an illegible manner.
     */
    protected function combineShadda()
    {
        $last = ($this->numchars - 1);
        for ($idx = 0; $idx < $last; ++$idx) {
            if (($this->newchardata[$idx]['char'] == UniArabic::SHADDA)
                && (isset(UniArabic::$diacritic[($this->newchardata[($idx + 1)]['char'])]))
            ) {
                $this->newchardata[$idx]['char'] = false;
                $this->newchardata[($idx + 1)]['char'] = UniArabic::$diacritic[
                    ($this->newchardata[($idx + 1)]['char'])
                ];
            }
        }
    }

    /**
     * Remove marked characters
     */
    protected function removeDeletedChars()
    {
        foreach ($this->newchardata as $key => $value) {
            if ($value['char'] === false) {
                unset($this->newchardata[$key]);
            }
        }
    }
}
