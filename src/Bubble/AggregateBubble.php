<?php
namespace bubblr\Bubble {
    use bubblr\Bubble\Exception\BubbleCanceledException;
    
    class AggregateBubble extends BubbleTask implements BubbleAwareInterface {
        protected $bubbles;
        
        protected $count;
        
        protected $exceptions = [];
        
        protected $results = [];
        
        public function __construct(array $bubbles = [], $count = null) {
            $this->bubbles = $bubbles;
            if(is_null($count)) {
                $this->count = count($bubbles);
            }
        }
        
        public function getException() {
            return $this->exceptions;
        }
        
        public function getResult() {
            return $this->results;
        }
        
        public function isCanceled() {
            return $this->isFailure();
        }
        
        public function isFailure() {
            return count($this->exceptions) > (count($this->bubbles) - $this->count);
        }
        
        public function isComplete() {
            return count($this->results) >= $this->count;
        }
        
        public function isSuccess() {
            return $this->isComplete();
        }
        
        public function resume(\bubblr\Spout\SpoutInterface $spout) {
            foreach($this->bubbles as $k => $bubble) {
                if(!($bubble instanceof BubbleInterface)) {
                    $bubble = $this->bubbles[$k] = $spout->getBubbler()->getAdapter()->execute($this,$bubble);
                }
                
                $bubble->resume($spout);
                if($bubble->isComplete()) {
                    if($bubble->isFailure()) {
                        $this->exceptions[] = $bubble->getException();
                    } elseif($bubble->isCanceled()) {
                        $this->exceptions[] = new BubbleCanceledException($bubble);
                    } else {
                        $this->results[] = $bubble->getResult();
                    }
                }
            }
        }

        public function getBubbles() {
            return $this->bubbles;
        }
    }
}