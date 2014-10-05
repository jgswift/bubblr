<?php
namespace bubblr\Bubbler {
    use bubblr\Spout\AggregateSpout;
    use bubblr\Bubble\BubbleAdapter;
    
    class AggregateBubbler implements BubblerInterface {
        private $spouts = [];
        private $adapter;
        
        public function attach(\bubblr\Spout\SpoutInterface $spout) {
            $this->spouts[] = $spout;
            $this->adapter = new BubbleAdapter;
        }
        
        public function getAdapter() {
            return $this->adapter;
        }

        public function detach(\bubblr\Spout\SpoutInterface $spout) {
            $key = array_search($spout,$this->spouts);
            if($key) {
                unset($this->spouts[$key]);
            }
        }

        public function execute($bubble = null) {
            if(empty($this->spouts)) {
                new BubblerSpout($this);
            }
            
            foreach($this->spouts as $spout) {
                $spout->execute($bubble);
            }
        }

        public function stop() {
            foreach($this->spouts as $spout) {
                $spout->stop();
            }
        }

        public function getSpout() {
            return new AggregateSpout($this->spouts);
        }
    }
}