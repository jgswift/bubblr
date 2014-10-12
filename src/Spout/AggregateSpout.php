<?php
namespace bubblr\Spout {
    use bubblr\Bubble\BubbleInterface;
    use bubblr\Bubbler\BubblerInterface;
    use observr\Subject\SubjectInterface;
    use bubblr\Spout\SpoutInterface;
    use observr\Subject;
    
    class AggregateSpout implements SpoutInterface, SubjectInterface, \IteratorAggregate, \Countable {
        use SpoutTrait, Subject;
        public $spouts;
        
        public function __construct(array $spouts, BubblerInterface $bubbler) {
            $this->spouts = $spouts;
            $this->bubbler = $bubbler;
        }
        
        public function execute($bubble = null) {
            foreach($this->spouts as $spout) {
                $spout->setBubbler($this->bubbler);
                $spout->execute($bubble);
            }
            
            return $this;
        }
        
        public function count() {
            return count($this->spouts);
        }
        
        public function invoke(BubbleInterface $bubble) {
            foreach($this->spouts as $spout) {
                $spout->invoke($bubble);
            }
        }
        
        public function suspend() {
            foreach($this->spouts as $spout) {
                $spout->suspend();
            }
        }
        
        public function resume() {
            foreach($this->spouts as $spout) {
                $spout->resume();
            }
        }
        
        public function push(BubbleInterface $bubble) {
            foreach($this->spouts as $spout) {
                $spout->push($bubble);
                break;
            }
        }
        
        public function pop() {
            foreach($this->spouts as $spout) {
                $bubble = $spout->pop();
                if(!empty($bubble)) {
                    return $bubble;
                }
            }
        }

        public function offsetExists($offset) {
            return isset($this->spouts[$offset]);
        }

        public function offsetGet($offset) {
            return $this->spouts[$offset];
        }

        public function offsetSet($offset, $value) {
            $this->spouts[$offset] = $value;
        }

        public function offsetUnset($offset) {
            unset($this->spouts[$offset]);
        }
        
        public function getIterator() {
            return new \ArrayIterator($this->spouts);
        }
    }
}