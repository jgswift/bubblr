<?php
namespace bubblr\Spout {
    use bubblr\Bubbler\BubblerInterface;
    use bubblr\Bubble\BubbleInterface;
    
    trait SpoutTrait {
        protected $bubbles = [];
        
        /**
         *
         * @var bubblr\Bubbler\BubblerInterface
         */
        protected $bubbler;
        protected $cursor = 0;
        protected $enabled = false;
        
        public function __construct(BubblerInterface $bubbler=null) {
            $this->bubbles = [];
            
            if($bubbler instanceof BubblerInterface) {
                $this->setBubbler($bubbler);
            }
        }
    
        public function execute($bubble=null) {
            if(!$this->enabled) {
                throw new Exception\SpoutDisabledException($this);
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
        
        public function setBubbler(BubblerInterface $bubbler) {
            $this->bubbler = $bubbler;
            $this->resume();
            return $this->bubbler;
        }

        abstract public function invoke(BubbleInterface $bubble);

        public function pop() {
            return array_shift($this->bubbles);
        }

        public function push(BubbleInterface $bubble) {
            array_push($this->bubbles,$bubble);
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
    }
}