<?php
namespace bubblr {
    class Bubbler {
        public static function spout($entry) {
            $bubbler = new Bubbler\AggregateBubbler;
            $spout = new Bubbler\BubblerSpout($bubbler);
            $bubbler->execute($entry);
        }
    }
}