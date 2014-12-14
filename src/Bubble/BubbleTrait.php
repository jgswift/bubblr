<?php
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    
    trait BubbleTrait {
        protected $result;
        
        public function getResult() {
            if($this->result instanceof BubbleInterface) {
                return $this->result->getResult();
            }
            
            return $this->result;
        }
        
        public function setContext($context) {
            $this->result = $context;
        }
        
        abstract public function resume(SpoutInterface $spout);
        
        abstract public function suspend(SpoutInterface $spout);
    }
}