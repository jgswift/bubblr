<?php
namespace bubblr\Spout\Exception {
    use bubblr\Exception;
    use bubblr\Spout\SpoutInterface;
    
    class SpoutDisabledException extends Exception {
        private $spout;
        
        public function __construct(SpoutInterface $spout) {
            parent::__construct('Spout cannot run while disabled (call resume)', 6);
            $this->spout = $spout;
        }
        
        public function getSpout() {
            return $this->spout;
        }
    }
}