<?php
namespace bubblr\Bubble {
    use bubblr\Exception;
    
    class EmptyBubble extends BubbleTask {
        
        public function resume(\bubblr\Spout\SpoutInterface $spout) {
            throw new Exception('Cannot run empty bubble');
        }

        public function stop(\bubblr\Spout\SpoutInterface $spout) {
            return true;
        }
    }
}