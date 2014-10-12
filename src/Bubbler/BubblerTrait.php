<?php
namespace bubblr\Bubbler {
    use bubblr\Spout\SpoutInterface;
    
    trait BubblerTrait {
        
        /**
         *
         * @var bubblr\Spout\SpoutInterface
         */
        private $spout;
        
        public function attach(SpoutInterface $spout) {
            $this->spout = $spout;
        }

        public function detach(SpoutInterface $spout) {
            $key = array_search($spout,$this->spouts);
            if($key) {
                unset($this->spouts[$key]);
            }
        }

        public function execute($bubble=null) {
            if(empty($this->spout)) {
                $this->spout = new BubblerSpout($this);
            }
            
            return $this->spout->execute($bubble);
        }

        public function suspend() {
            return $this->spout->suspend();
        }

        public function getSpout() {
            return $this->getSpout();
        }
    }
}