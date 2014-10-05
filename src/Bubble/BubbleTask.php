<?php
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    
    abstract class BubbleTask implements BubbleInterface {
        use BubbleTrait;
        
        abstract public function resume(SpoutInterface $spout);
        
        public function stop(SpoutInterface $spout) {
            $spout->stop();
        }
        
        public function cancel() {
            $this->canceled = true;
        }
    }
}