<?php
namespace bubblr;

function async($callable) {
    return Bubbler::async($callable);
}

function spout($bubble, array $args = []) {
    return Bubbler::spout($bubble, $args);
}

function asyncAll(array $callables) {
    return Bubbler::asyncAll($callables);
}

function reschedule($spout = null) {
    return Bubbler::reschedule($spout);
}