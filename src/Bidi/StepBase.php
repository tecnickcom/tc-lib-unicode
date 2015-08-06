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
     * Sequence to process and return
     *
     * @var array
     */
    protected $seq = array();

    /**
     * Initialize Sequence to process
     *
     * @param array $seq isolated Sequence array
     */
    public function __construct($seq)
    {
        $this->seq = $seq;
        $this->process();
    }

    /**
     * Returns the processed array
     *
     * @return array
     */
    public function getSequence()
    {
        return $this->seq;
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
        for ($idx = 0; $idx < $this->seq['length']; ++$idx) {
            $this->$method($idx);
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
        if ($idx >= ($this->seq['length'] - 1)) {
            return -1;
        }
        ++$idx;
        while (($idx < $this->seq['length']) && ($this->seq['item'][$idx]['type'] == 'BN')) {
            ++$idx;
        }
        if ($idx == $this->seq['length']) {
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
        while (($idx > -1) && ($this->seq['item'][$idx]['type'] == 'BN')) {
            --$idx;
        }
        return $idx;
    }
}
