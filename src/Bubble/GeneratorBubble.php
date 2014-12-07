<?php 
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    
    class GeneratorBubble extends IteratorBubble {
        protected $generatorCreate;
        
        function __construct(callable $generator) {
            $this->generatorCreate = $generator;
            
            parent::__construct($generator());
        }
        
        public function getGenerator() {
            return parent::getIterator();
        }
               
        public function resume(SpoutInterface $spout) {
            if($this->isComplete()) {
                $generatorCreate = $this->generatorCreate;
                $this->iterator = $generatorCreate();
            }
            
            return $this->iterator->send($this->result);
        }
    }
}