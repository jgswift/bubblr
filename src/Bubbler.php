<?php
namespace bubblr {
    class Bubbler {
        public static function spout($entry) {
            $bubbler = new Bubbler\AggregateBubbler;
            $bubbler->execute($entry);
        }
    }
}