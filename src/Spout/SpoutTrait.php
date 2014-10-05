<?php
namespace bubblr\Spout {
    use bubblr\Bubbler\BubblerInterface;
    use bubblr\Bubble\BubbleInterface;
    
    trait SpoutTrait {
        private $bubbles = [];
        
        /**
         *
         * @var bubblr\Bubbler\BubblerInterface
         */
        private $bubbler;
        private $cursor = 0;
        private $enabled = false;
        
        public function __construct(BubblerInterface $bubbler) {
            $this->bubbler = $bubbler;
            $this->bubbles = [];
            $this->resume();
        }
    
        public function execute($bubble=null) {
            if(!$this->enabled) {
                return;
            }
            
            if($bubble instanceof BubbleInterface) {
                $this->push($bubble);
            } elseif(!empty($bubble)) {
                $this->push($this->bubbler->getAdapter()->execute($this, $bubble));
            }
            
            while(!empty($this->bubbles)) {
                $bubble = $this->pop();
                
                $this->invoke($bubble);
                
                if(!$bubble->isComplete()) {
                    $this->push($bubble);
                }
            }
            
            return $this;
        }

        public function getBubbler() {
            return $this->bubbler;
        }

        abstract public function invoke(BubbleInterface $bubble);

        public function pop() {
            return array_shift($this->bubbles);
        }

        public function push(BubbleInterface $bubble) {
            array_push($this->bubbles,$bubble);
        }

        public function stop() {
            $this->bubbles = [];
            $this->enabled = false;
        }

        public function suspend() {
            if(!$this->enabled) {
                return;
            }
            
            $this->enabled = false;
            
            $this->bubbler->detach($this);
        }
        
        public function resume() {
            if($this->enabled) {
                return;
            }
            
            $this->enabled = true;
            $this->bubbler->attach($this);
        }

        public function key() {
            return key($this->bubbles);
        }
        
        public function current() {
            return current($this->bubbles);
        }

        public function next() {
            next($this->bubbles);
        }

        public function rewind() {
            reset($this->bubbles);
        }

        public function valid() {
            return (current($this->bubbles) !== false);
        }
    }
}