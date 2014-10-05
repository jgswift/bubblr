<?php
namespace bubblr\Bubble {
    use React\Promise\PromiseInterface;
    
    class BubbleAdapter {
        
        public function execute($bubble, $value) {
            if($value instanceof BubbleInterface) {
                return $value;
            } elseif($value instanceof \Generator) {
                return new GeneratorBubble($value);
            } elseif(is_callable($value)) {
                return new CallableBubble($value);
            } elseif($value instanceof PromiseInterface) {
                return new PromiseBubble($value);
            } elseif(is_array($value)) {
                return new AggregateBubble($value);
            }
            
            throw new \InvalidArgumentException('Value of type ('.gettype($value).') invalid');
        }
    }
}