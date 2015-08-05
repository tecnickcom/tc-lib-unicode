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
     * Sequence Level
     *
     * @var int
     */
    protected $level = 0;

    /**
     * Start Order Sequence
     *
     * @var string
     */
    protected $sos;

    /**
     * End Order Sequence
     *
     * @var string
     */
    protected $eos;

    /**
     * W Steps for Bidirectional algorithm
     *
     * 3.3.3 Resolving Weak Types
     * Weak types are now resolved one level run at a time.
     * At level run boundaries where the type of the character on the other side of the boundary is required,
     * the type assigned to sor or eor is used.
     * Nonspacing marks are now resolved based on the previous characters.
     *
     * @param array  $seq isolated Sequence array
     */
    public function __construct($seq)
    {
        $this->chardata = $seq['item'];
        $this->numchars = $seq['length'];
        $this->level = $seq['e'];
        $this->sos = $seq['sos'];
        $this->eos = $seq['eos'];
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
        for ($idx = 0; $idx < $this->numchars; ++$idx) {
            $this->$method($idx);
        }
    }
}
