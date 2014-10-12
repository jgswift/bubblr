<?php
namespace bubblr\Bubbler {
    use bubblr\Spout\AggregateSpout;
    use bubblr\Bubble\BubbleAdapter;
    use bubblr\Spout\SpoutInterface;
    
    class AggregateBubbler implements BubblerInterface {
        protected $spout;
        private $adapter;
        
        public function __construct(SpoutInterface $spout = null) {
            if(is_null($spout)) {
                $spout = new BubblerSpout;
            }
            $this->spout = new AggregateSpout([$spout],$this);
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
            foreach($this->spout as $spout) {
                $spout->setBubbler($this);
                $spout->execute($bubble);
            }
        }
        
        public function resume() {
            foreach($this->spout as $spout) {
                $spout->setBubbler($this);
                $spout->resume();
            }
        }

        public function suspend() {
            foreach($this->spout as $spout) {
                $spout->suspend();
            }
        }

        public function getSpout() {
            return $this->spout;
        }
    }
}