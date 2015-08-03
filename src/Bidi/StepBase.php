<?php
/**
 * StepBase.php
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
 * Com\Tecnick\Unicode\Bidi\StepBase
 *
 * @since       2015-07-13
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 */
abstract class StepBase
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
     * W Steps for Bidirectional algorithm
     *
     * 3.3.3 Resolving Weak Types
     * Weak types are now resolved one level run at a time.
     * At level run boundaries where the type of the character on the other side of the boundary is required,
     * the type assigned to sor or eor is used.
     * Nonspacing marks are now resolved based on the previous characters.
     *
     * @param array $chardata Array of characters data
     */
    public function __construct($chardata)
    {
        $this->chardata = $chardata;
        $this->numchars = count($chardata);
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
     * Process the current step
     */
    abstract protected function process();

    /**
     * Generic step
     *
     * @param string $method Processing methos
     */
    protected function processStep($method)
    {
        $prevlevel = -1; // track level changes
        $levcount = 0; // counts consecutive chars at the same level
        for ($idx = 0; $idx < $this->numchars; ++$idx) {
            $this->$method($idx, $levcount, $prevlevel);
            if ($this->chardata[$idx]['level'] != $prevlevel) {
                $levcount = 0;
            } else {
                ++$levcount;
            }
            $prevlevel = $this->chardata[$idx]['level'];
        }
    }
}
