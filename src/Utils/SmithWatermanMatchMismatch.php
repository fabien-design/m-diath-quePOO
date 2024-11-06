<?php 

namespace App\Utils;

use Exception;

class SmithWatermanMatchMismatch {
    private $matchValue;
    private $mismatchValue;

    public function __construct($matchValue, $mismatchValue) {
        if ($matchValue <= $mismatchValue) {
            throw new Exception("matchValue doit être strictement supérieur à mismatchValue");
        }
        $this->matchValue = $matchValue;
        $this->mismatchValue = $mismatchValue;
    }

    public function compare($a, $aIndex, $b, $bIndex) {
        return ($a[$aIndex] === $b[$bIndex]) ? $this->matchValue : $this->mismatchValue;
    }

    public function max() {
        return $this->matchValue;
    }

    public function min() {
        return $this->mismatchValue;
    }
}
