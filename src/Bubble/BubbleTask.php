<?php
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    
    abstract class BubbleTask implements BubbleInterface {
        use BubbleTrait;
        
        public function resume(SpoutInterface $spout) {
            $spout->resume();
        }
        
        public function suspend(SpoutInterface $spout) {
            $spout->suspend();
        }
        
        public function cancel() {
            $this->canceled = true;
        }
    }
}