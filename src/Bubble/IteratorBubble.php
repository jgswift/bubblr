<?php
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    
    class IteratorBubble extends BubbleTask implements \IteratorAggregate {
        protected $iterator;
        
        function __construct(\Iterator $iterator) {
            $this->iterator = $iterator;
        }
        
        function getIterator() {
            return $this->iterator;
        }

        public function isComplete() {
            return !$this->iterator->valid();
        }
        
        public function resume(SpoutInterface $spout) {
            $this->iterator->next();
        }
    }
}