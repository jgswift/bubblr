<?php 
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    
    class GeneratorBubble extends IteratorBubble {
        function __construct(\Generator $generator) {
            parent::__construct($generator);
        }
        
        public function getGenerator() {
            return parent::getIterator();
        }
               
        public function resume(SpoutInterface $spout) {
            return $this->iterator->send($this->result);
        }
    }
}