<?php
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    use bubblr\Exception;
    
    class ThrottledBubble extends CallableBubble {
        protected $bubble = null;
        
        protected $throttle = null;
        
        private $start;
        
        public function __construct(BubbleInterface $bubble, $throttle) {
            if($bubble === $this) {
                throw new Exception('Cannot get bubble to throttle itself');
            }
            
            if($throttle <= 0) {
                throw new \InvalidArgumentException('Throttle must be greater than 0');
            }
            
            $this->bubble = $bubble;
            $this->throttle = $throttle;
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
            if(!$this->start) {
                $this->start = new \DateTime;
                $spout->push($this->bubble);
            } elseif($this->start->diff(new \DateTime)->s > $this->throttle) {
                $this->start = new \DateTime;
                $spout->push($this->bubble);
            }
        }
    }
}