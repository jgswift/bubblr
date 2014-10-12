<?php
namespace bubblr\Bubbler {
    use bubblr\Spout\SpoutInterface;
    
    interface BubblerInterface {
        public function execute($bubble = null);
        public function attach(SpoutInterface $spout);
        public function detach(SpoutInterface $spout);
        public function resume();
        public function suspend();
        public function getSpout();
    }
}