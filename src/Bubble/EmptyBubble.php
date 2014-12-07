<?php
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    use bubblr\Exception;
    
    class EmptyBubble extends BubbleTask {
        
        public function resume(SpoutInterface $spout) {
            throw new Exception('Cannot run empty bubble');
        }

        public function stop(SpoutInterface $spout) {
            return true;
        }
    }
}