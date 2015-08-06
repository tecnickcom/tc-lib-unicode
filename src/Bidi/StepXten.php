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
     * returns true if the input char is an Isolate Initiator
     *
     * @return bool
     */
    protected function isIsolateInitiator($ord)
    {
        return (($ord == UniConstant::RLI) || ($ord == UniConstant::LRI) || ($ord == UniConstant::FSI));
    }

    /**
     * Set level Isolated Level Run Sequences
     *
     * @return array
     */
    protected function setIsolatedLevelRunSequences()
    {
        $this->setLevelRunSequences();
        $numiso = 0;
        foreach ($this->runseq as $idx => $seq) {

            // Create a new level run sequence, and initialize it to contain just that level run
            $isorun = array(
                'e'      => $seq['e'],
                'edir'   => $this->getEmbeddedDirection($seq['e']), // embedded direction
                'length' => ($seq['end'] - $seq['start'] + 1),
                'item'   => array()
            );
            for ($jdx = 0; $jdx < $isorun['length']; ++$jdx) {
                $isorun['item'][$jdx] = $this->chardata[($seq['start'] + $jdx)];
            }
            $endchar = $isorun['item'][($jdx - 1)]['char'];

            // While the level run currently last in the sequence ends with an isolate initiator that has a
            // matching PDI, append the level run containing the matching PDI to the sequence.
            // (Note that this matching PDI must be the first character of its level run.)
            $pdimatch = -1;
            if ($this->isIsolateInitiator($endchar)) {
                // find the next sequence with the same level that starts with a PDI
                for ($kdx = ($idx + 1); $kdx < $this->numrunseq; ++$kdx) {
                    if (($this->runseq[$kdx]['e'] == $isorun['e'])
                        && ($this->chardata[$this->runseq[$kdx]['start']]['char'] == UniConstant::PDI)
                    ) {
                        $pdimatch = $this->runseq[$kdx]['start'];
                        $this->chardata[$pdimatch]['pdimatch'] = $numiso;
                        break;
                    }
                }
            }

            // For each level run in the paragraph whose first character is not a PDI,
            // or is a PDI that does not match any isolate initiator
            if (isset($this->chardata[$seq['start']]['pdimatch'])) {
                $parent = $this->chardata[$seq['start']]['pdimatch'];
                $this->ilrs[$parent]['item'] = array_merge($this->ilrs[$parent]['item'], $isorun['item']);
                $this->ilrs[$parent]['length'] += $isorun['length'];
                if ($pdimatch >= 0) {
                    $this->chardata[$pdimatch]['pdimatch'] = $parent;
                }
            } else {
                $this->ilrs[$numiso] = $isorun;
                ++$numiso;
            }
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
            $jdx = $this->getUpdatedJdx(++$jdx, $idx);
        }
        return $jdx;
    }
}
