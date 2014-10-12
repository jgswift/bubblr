<?php
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    use observr\Event\EventInterface;
    
    interface BubbleInterface extends EventInterface {
        public function getResult();
        public function cancel();
        public function resume(SpoutInterface $spout);
        public function suspend(SpoutInterface $spout);
    }
}