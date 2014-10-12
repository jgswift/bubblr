<?php
namespace bubblr\Spout {
    use bubblr\Bubbler\BubblerAwareInterface;
    use bubblr\Bubble\BubbleInterface;
    
    interface SpoutInterface extends BubblerAwareInterface {
        public function push(BubbleInterface $bubble);
        public function pop();
        public function invoke(BubbleInterface $bubble);
        public function suspend();
        public function resume();
    }
}