<?php
namespace bubblr\Bubble {
    use observr\Emitter;
    
    class EmitterBubble extends CallableBubble {
        private $emitter;
        
        public function __construct(Emitter $emitter) {
            $this->emitter = $emitter;
            parent::__construct(function()use($emitter) {
                $emitter->emit();
            });
        }
        
        public function getEmitter() {
            return $this->emitter;
        }
    }
}