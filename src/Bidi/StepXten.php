<?php
/**
 * StepXten.php
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

/**
 * Com\Tecnick\Unicode\Bidi\StepXten
 *
 * @since       2015-07-13
 * @category    Library
 * @package     Unicode
 * @author      Nicola Asuni <info@tecnick.com>
 * @copyright   2011-2015 Nicola Asuni - Tecnick.com LTD
 * @license     http://www.gnu.org/copyleft/lesser.html GNU-LGPL v3 (see LICENSE.TXT)
 * @link        https://github.com/tecnickcom/tc-lib-unicode
 */
class StepXten
{
    /**
     * Array of characters data to return
     *
     * @var array
     */
    protected $chardata = array();

    /**
     * Paragraph Embedding Level
     *
     * @var int
     */
    protected $pel = 0;

    /**
     * Number of characters
     *
     * @var int
     */
    protected $numchars = 0;

    /**
     * Array of Level Run sequences
     *
     * @var array
     */
    protected $runseq = array();

    /**
     * Number of Level Run sequences
     *
     * @var int
     */
    protected $numrunseq = 0;

    /**
     * Array of Isolated Level Run sequences
     *
     * @var array
     */
    protected $ilrs = array();

    /**
     * X Steps for Bidirectional algorithm
     *
     * @param array  $chardata  Array of UTF-8 codepoints
     * @param int    $pel       Paragraph Embedding Level
     */
    public function __construct($chardata, $pel)
    {
        $this->chardata = $chardata;
        $this->numchars = count($chardata);
        $this->pel = $pel;
        $this->setIsolatedLevelRunSequences();
    }

    /**
     * Get the Isolated Run Sequences
     *
     * @return array
     */
    public function getIsolatedLevelRunSequences()
    {
        return $this->ilrs;
    }

    /**
     * Get the previous valid char
     *
     * @param int  $idx    Current char position
     * @param int  $fence  First entry to process
     *
     * @return int
     */
    protected function getPreviousValidChar($idx, $fence)
    {
        if (($idx == -1) || ($idx == $fence)) {
            return $idx;
        }
        --$idx;
        while (($idx > $fence) && ($this->chardata[$idx]['type'] == 'BN')) {
            --$idx;
        }
        return $idx;
    }

    /**
     * Get the next valid char
     *
     * @param int  $idx    Current char position
     * @param int  $fence  Last entry to process
     *
     * @return int
     */
    protected function getNextValidChar($idx, $fence)
    {
        if ($idx == $fence) {
            return $idx;
        }
        ++$idx;
        while (($idx < $fence) && ($this->chardata[$idx]['type'] == 'BN')) {
            ++$idx;
        }
        return $idx;
    }

    /**
     * Get the embedded direction (L or R)
     *
     * @param int $level
     *
     * @return string
     */
    protected function getEmbeddedDirection($level)
    {
        return ((($level % 2) == 0) ? 'L' : 'R');
    }

    /**
     * Set Level Run Sequences
     */
    protected function setLevelRunSequences()
    {
        $start = 0;
        while ($start < $this->numchars) {
            $end = $this->getNextValidChar($start, $this->numchars);
            while (($end < $this->numchars) && ($this->chardata[$end]['level'] == $this->chardata[$start]['level'])) {
                $end = $this->getNextValidChar($end, $this->numchars);
            }
            --$end;
            $this->runseq[] = array(
                'start' => $start,
                'end'   => $end,
                'e'     => $this->chardata[$start]['level']
            );
            ++$this->numrunseq;
            $start = $this->getNextValidChar($end, $this->numchars);
        }
    }

    /**
     * Set level Isolated Level Run Sequences
     *
     * @return array
     */
    protected function setIsolatedLevelRunSequences()
    {
        $this->setLevelRunSequences();
        foreach ($this->runseq as $idx => $seq) {
            if ($seq['start'] >= 0) {
                $start = $seq['start'];
                $isorun = array(
                    'e'      => $seq['e'],
                    'edir'   => $this->getEmbeddedDirection($seq['e']), // embedded direction
                    'length' => ($seq['end'] - $seq['start'] + 1),
                    'item'   => array()
                );
                for ($jdx = 0; $jdx < $isorun['length']; ++$jdx) {
                    $isorun['item'][$jdx] = $this->chardata[($start + $jdx)];
                }
                $end = $seq['end'];
                if ($this->chardata[$end]['type'] == 'BN') {
                    $end = $this->getPreviousValidChar($end, $start);
                }
            }

            $kdx = $idx;
            $endchar = $this->chardata[$end]['char'];
            
            while (($end < $this->numchars)
                && (($endchar == UniConstant::RLI) || ($endchar == UniConstant::LRI) || ($endchar == UniConstant::FSI))
            ) {
                $jdx = $this->getUpdatedJdx(($kdx + 1), $idx);
                if ($jdx != $this->numrunseq) {
                    $len = $isorun['length'];
                    $isorun['length'] += ($this->runseq[$jdx]['end'] - $this->runseq[$jdx]['start'] + 1);
                    for ($mdx = 0; $len < $isorun['length']; ++$len, ++$mdx) {
                        $isorun['item'][$len] = $this->chardata[($this->runseq[$jdx]['start'] + $mdx)];
                    }

                    $end = $this->runseq[$jdx]['end'];
                    if ($this->chardata[$end]['type'] == 'BN') {
                        $end = $this->getPreviousValidChar($end, $this->runseq[$idx]['start']);
                    }
                    $this->runseq[$jdx]['start'] = -1;
                    $kdx = $jdx;
                } else {
                    $end = $this->numchars;
                    break;
                }
            }

            $fence = $this->getPreviousValidChar($start, -1);
            $start_level = $this->chardata[$start]['level'];
            if ($fence == -1) {
                $isorun['sos'] = ($this->pel > $start_level) ? $this->pel : $start_level;
            } else {
                $fence_level = $this->chardata[$fence]['level'];
                $isorun['sos'] = ($fence_level > $start_level) ? $fence_level : $start_level;
            }

            $isorun['sos'] = $this->getEmbeddedDirection($isorun['sos']);

            if ($end == $this->numchars) {
                $isorun['eos'] = $isorun['sos'];
            } else {
                // eos could be an BN
                if ($this->chardata[$end]['type'] == 'BN') {
                    $real_end = $this->getPreviousValidChar($end, ($start - 1));
                    if ($real_end < $start) {
                        $real_end = $start;
                    }
                } else {
                    $real_end = $end;
                }

                $fence = $this->getNextValidChar($end, $this->numchars);
                $end_level = $this->chardata[$real_end]['level'];
                if ($fence == $this->numchars) {
                    $isorun['eos'] = ($this->pel > $end_level) ? $this->pel : $end_level;
                } else {
                    $fence_level = $this->chardata[$fence]['level'];
                    $isorun['eos'] = ($fence_level > $end_level) ? $fence_level : $end_level;
                }
                $isorun['eos'] = $this->getEmbeddedDirection($isorun['eos']);
            }

            $this->ilrs[] = $isorun;
        }
    }

    /**
     * Get updated $jdx index
     *
     * @return int
     */
    protected function getUpdatedJdx($jdx, $idx)
    {
        while (($jdx < $this->numrunseq)
            && ($this->chardata[$this->runseq[$jdx]['start']]['char'] != UniConstant::PDI)
        ) {
            ++$jdx;
        }
        if (($jdx < $this->numrunseq) && ($this->runseq[$idx]['e'] != $this->runseq[$jdx]['e'])) {
            $jdx = $this->updateJdx(++$jdx, $idx);
        }
        return $jdx;
    }
}
