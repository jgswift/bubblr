<?php
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    use observr\Event;
    
    abstract class BubbleTask extends Event implements BubbleInterface {
        use BubbleTrait;
        
        public function resume(SpoutInterface $spout) {
            $spout->resume();
        }
        
        public function suspend(SpoutInterface $spout) {
            $spout->suspend();
        }
        
//        public function cancel() {
//            $this->canceled = true;
//        }
    }
}