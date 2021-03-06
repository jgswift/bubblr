<?php
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    use observr\Event;
    
    abstract class BubbleTask extends Event implements BubbleInterface {
        use BubbleTrait;
        
        public function resume(SpoutInterface $spout) {
            $spout->resume();
        }
        
        public function suspend(SpoutInterface $spout) {
            $spout->suspend();
        }
        
        public function __invoke() {
            $result = \bubblr\Bubbler::spout($this, func_get_args());
            if(!empty($result)) {
                return $result[0];
            }
        }
    }
}