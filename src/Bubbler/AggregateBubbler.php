<?php
namespace bubblr\Bubbler {
    use bubblr\Spout\AggregateSpout;
    use bubblr\Bubble\BubbleAdapter;
    use bubblr\Spout\SpoutInterface;
    
    class AggregateBubbler implements BubblerInterface {
        protected $spout;
        private $adapter;
        
        public function __construct() {
            $this->spout = new AggregateSpout([],$this);
            $this->adapter = new BubbleAdapter;
        }
        
        public function attach(SpoutInterface $spout) {
            $this->spout->spouts[] = $spout;
        }
        
        public function getAdapter() {
            return $this->adapter;
        }

        public function detach(SpoutInterface $spout) {
            $key = array_search($spout,$this->spout);
            if($key) {
                unset($this->spout[$key]);
            }
        }

        public function execute($bubble = null) {
            if(count($this->spout) === 0) {
                $this->attach(new BubblerSpout);
            }
            
            foreach($this->spout as $spout) {
                $spout->setBubbler($this);
                $spout->execute($bubble);
            }
        }

        public function stop() {
            foreach($this->spout as $spout) {
                $spout->stop();
            }
        }

        public function getSpout() {
            return $this->spout;
        }
    }
}