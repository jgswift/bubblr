<?php
namespace bubblr\Spout {
    use bubblr\Bubbler\BubblerAwareInterface;
    use bubblr\Bubble\BubbleInterface;
    
    interface SpoutInterface extends BubblerAwareInterface, \Iterator {
        public function push(BubbleInterface $bubble);
        public function pop();
        public function invoke(BubbleInterface $bubble);
        public function suspend();
        public function stop();
        public function resume();
    }
}