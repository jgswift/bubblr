<?php
namespace bubblr {
    use bubblr\Bubble\CallableBubble;
    use bubblr\Bubble\BubbleInterface;
    use bubblr\Bubble\AggregateBubble;
    use bubblr\Spout\SpoutInterface;
    
    class Bubbler {
        protected static $application;
        protected static $adapter;
        
        public static function spout($bubble, array $args = []) {
            if(empty(self::$application)) {
                self::$application = new Bubbler\AggregateBubbler;
                self::$adapter = new \bubblr\Bubble\BubbleAdapter();
            }
            
            if(!($bubble instanceof BubbleInterface)) {
                $bubble = self::$adapter->execute(null,$bubble);
            }
            
            if(!empty($args)) {
                $bubble->setContext($args);
            }
            
            self::$application->getSpout()->push($bubble);
            
            return self::$application->execute();
        }
        
        public static function async($callable) {
            if(is_callable($callable) ||
               is_array($callable)) {
                return new CallableBubble($callable);
            } 
                
            throw new \InvalidArgumentException('async argument must be callable');
        }
        
        public static function asyncAll(array $bubbles) {
            return new AggregateBubble($bubbles);
        }
        
        public static function reschedule($spout = null) {
            if($spout instanceof SpoutInterface) {
                return $spout->execute();
            } else {
                if(empty(self::$application)) {
                    self::$application = new Bubbler\AggregateBubbler;
                    self::$adapter = new \bubblr\Bubble\BubbleAdapter();
                }
                
                self::$application->getSpout()->execute();
            }
        }
    }
}