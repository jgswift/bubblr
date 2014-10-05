<?php
namespace bubblr\Bubbler {
    interface BubblerAwareInterface {
        public function getBubbler();
        public function setBubbler(BubblerInterface $bubbler);
    }
}