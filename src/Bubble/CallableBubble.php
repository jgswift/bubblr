<?php
namespace bubblr\Bubble {
    class CallableBubble extends GeneratorBubble {
        private $callable;
        private $count;
        
        function __construct($callable, $count = 1) {
            $this->callable = $callable;
            $this->count = $count;
            
            $generator = function()use($callable,$count) {
                $context = yield;
                if($context === null) {
                    $context = [];
                } elseif(!is_array($context)) {
                    $context = [$context];
                }
                
                for($i=1;$i<=$count;$i++) {
                    if($callable instanceof \Closure) {
                        $callable->bindTo($this,$this);
                    }
                    
                    $this->result = call_user_func_array($callable,$context);
                    
                    yield $this->result;
                }
            };
            
            parent::__construct($generator);
        }
        
        function getCallable() {
            return $this->callable;
        }
    }
}