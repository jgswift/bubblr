<?php
namespace bubblr\Bubble {
    class CallableBubble extends GeneratorBubble {
        private $callable;
        private $count;
        
        function __construct(callable $callable, $count = 1) {
            $this->callable = $callable;
            $this->count = $count;
            
            $generator = function()use($callable,$count) {
                for($i=1;$i<=$count;$i++) {
                    if($callable instanceof \Closure) {
                        @$callable->bindTo($this,$this);
                    }
                    $this->result = $callable();
                    yield;
                }
            };
            
            parent::__construct($generator());
        }
        
        function getCallable() {
            return $this->callable;
        }
    }
}