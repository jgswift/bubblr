<?php
namespace bubblr\Spout {
    use bubblr\Bubbler\BubblerSpout;
    use bubblr\Bubble\BubbleInterface;
    use bubblr\Bubbler\BubblerInterface;
    
    class AggregateSpout extends BubblerSpout {
        protected $spouts;
        
        public function __construct(array $spouts, BubblerInterface $bubbler) {
            $this->spouts = $spouts;
            parent::__construct($bubbler);
        }
        
        public function execute($bubble = null) {
            foreach($this->spouts as $spout) {
                $spout->setBubbler($this->bubbler);
                $spout->execute($bubble);
            }
            
            return $this;
        }
        
        public function invoke(BubbleInterface $bubble) {
            
        }
    }
}