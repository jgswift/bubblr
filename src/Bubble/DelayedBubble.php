<?php
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    use bubblr\Exception;
    
    class DelayedBubble extends CallableBubble {
        protected $bubble = null;
        
        protected $delay = null;
        
        private $enabled = false;
        
        private $start;
        
        public function __construct(BubbleInterface $bubble, $delay) {
            if($bubble === $this) {
                throw new Exception('Cannot get bubble to delay itself');
            }
            
            if($delay <= 0) {
                throw new \InvalidArgumentException('Delay must be greater than 0');
            }
            
            $this->bubble = $bubble;
            $this->delay = $delay;
        }
        
        public function isComplete() {
            return $this->bubble->isComplete();
        }
        
        public function isCanceled() {
            return $this->bubble->isCanceled();
        }
        
        public function isFailure() {
            return $this->bubble->isFailure();
        }
        
        public function isSuccess() {
            return $this->bubble->isSuccess();
        }
        
        public function resume(SpoutInterface $spout) {
            if(!$this->enabled) {
                if(!$this->start) {
                    $this->start = new \DateTime;
                    $spout->push($this);
                } else {
                    if($this->start->diff(new \DateTime)->s >= $this->delay) {
                        $spout->push($this->bubble);
                        $this->enabled = true;
                        return;
                    }
                }
            }
        }
    }
}