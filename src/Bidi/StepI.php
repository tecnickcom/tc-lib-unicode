<?php
/**
 * StepI.php
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
 * Com\Tecnick\Unicode\Bidi\StepI
 *
 * @since       2015-07-13
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 */
class StepI extends \Com\Tecnick\Unicode\Bidi\StepBase
{
    /**
     * Max level
     *
     * @var int
     */
    protected $maxlevel = 0;

    /**
     * Process I steps
     */
    protected function process()
    {
        // I1. For all characters with an even (left-to-right) embedding direction,
        //     those of type R go up one level and those of type AN or EN go up two levels.
        // I2. For all characters with an odd (right-to-left) embedding direction,
        //     those of type L, EN or AN go up one level.
        for ($idx = 0; $idx < $this->numchars; ++$idx) {
            $odd = ($this->chardata[$idx]['level'] % 2);
            if ($odd) {
                if (($this->chardata[$idx]['type'] == 'L')
                    || ($this->chardata[$idx]['type'] == 'AN')
                    || ($this->chardata[$idx]['type'] == 'EN')
                ) {
                    $this->chardata[$idx]['level'] += 1;
                }
            } else {
                if ($this->chardata[$idx]['type'] == 'R') {
                    $this->chardata[$idx]['level'] += 1;
                } elseif (($this->chardata[$idx]['type'] == 'AN')
                    || ($this->chardata[$idx]['type'] == 'EN')
                ) {
                    $this->chardata[$idx]['level'] += 2;
                }
            }
            $this->maxlevel = max($this->chardata[$idx]['level'], $this->maxlevel);
        }
    }

    /**
     * Returns the maximum level
     *
     * @return int
     */
    public function getMaxLevel()
    {
        return $this->maxlevel;
    }
}
