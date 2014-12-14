<?php
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    use observr\Event\EventInterface;
    use observr\Event\EventAwareInterface;
    
    interface BubbleInterface extends EventInterface, EventAwareInterface {
        public function getResult();
        public function setContext($context);
        public function resume(SpoutInterface $spout);
        public function suspend(SpoutInterface $spout);
    }
}