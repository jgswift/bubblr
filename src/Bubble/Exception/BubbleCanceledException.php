<?php
namespace bubblr\Bubble\Exception {
    use bubblr\Exception;
    use bubblr\Bubble\BubbleInterface;
    
    class BubbleCanceledException extends Exception {
        private $bubble;
        
        public function __construct(BubbleInterface $bubble) {
            parent::__construct('Bubble cancelled', 6);
            $this->bubble = $bubble;
        }
        
        public function getBubble() {
            return $this->bubble;
        }
    }
}