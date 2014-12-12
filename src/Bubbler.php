<?php
namespace bubblr {
    use bubblr\Bubble\CallableBubble;
    
    class Bubbler {
        protected static $application;
        protected static $adapter;
        
        public static function spout($entry) {
            if(empty(self::$application)) {
                self::$application = new Bubbler\AggregateBubbler;
                self::$adapter = new \bubblr\Bubble\BubbleAdapter();
            }
            
            $bubble = self::$adapter->execute(null,$entry);
            
            self::$application->getSpout()->push($bubble);
            
            return self::$application->execute();
        }
        
        public static function async(callable $callable) {
            return new CallableBubble($callable);
        }
    }
}