bubblr
====
PHP 5.5+ asynchronous cooperative task scheduler 

[![Build Status](https://travis-ci.org/jgswift/bubblr.png?branch=master)](https://travis-ci.org/jgswift/bubblr)
[![Latest Stable Version](https://poser.pugx.org/jgswift/bubblr/v/stable.svg)](https://packagist.org/packages/jgswift/bubblr)
[![License](https://poser.pugx.org/jgswift/bubblr/license.svg)](https://packagist.org/packages/jgswift/bubblr)

## Description

bubblr is a lightweight component that allows for cooperative multitasking without needing to understand generators or requiring code modification.
bubblr allows multitasking using both functional and imperative programming paradigms.

## Installation

Install via cli using [composer](https://getcomposer.org/):
```sh
php composer.phar require jgswift/bubblr:0.1.*
```

Install via composer.json using [composer](https://getcomposer.org/):
```json
{
    "require": {
        "jgswift/bubblr": "0.1.*"
    }
}
```

## Dependency

* php 5.5+
* [jgswift/observr](https://github.com/jgswift/observr)
* [react/promise](https://github.com/reactphp/promise)

## Usage

### Coroutine (Bubble)

Coroutines, or Bubbles (as they are referred to in this package) are units of work which are queued and executed.
Any function or method can be wrapped by a coroutine to allow easy multitasking with minimal overhead.

#### BubbleInterface (abbr.)

```php
interface BubbleInterface extends EventInterface, EventAwareInterface {

    public function getResult();

    public function setContext($context);

    public function resume(SpoutInterface $spout);

    public function suspend(SpoutInterface $spout);
}
```

### Spouts

Spouts act as the scheduler queue to handle coroutine execution.  
This is conceptually similar to a thread but must be differentiated as coroutines are executed in sequence.
Coroutines may yield execution priority back to the spout to allow individual coroutines to allow other tasks to execute.

#### SpoutInterface (abbr.)

```php
interface SpoutInterface extends BubblerAwareInterface {

    public function push(BubbleInterface $bubble);

    public function pop();

    public function invoke(BubbleInterface $bubble);

    public function suspend();

    public function resume();
}
```

### Core Bubbler

The core bubbler is the primary application that handles the creation and scheduling of spouts.

Coroutines, spouts, and the core are all open to modification using the globally aliased API.

#### BubblerInterface (abbr.)

```php
interface BubblerInterface {

    public function execute($bubble = null);

    public function attach(SpoutInterface $spout);

    public function detach(SpoutInterface $spout);

    public function resume();

    public function suspend();

    public function getSpout();
}
```

### Simple example (returning values)

```php
$hello = bubblr\async(function() {
    return 'hello world';
});

echo $hello(); // 'hello world'
```

### Scheduling existing methods

```php
function hello() {
    return 'hello world';
}

$hello = bubblr\async('hello');

echo $hello(); // 'hello world'
```

### Rescheduling during long-running task

```php
$fib = bubblr\async(function($num) {
    $n1 = 0; $n2 = 1; $n3 = 1;
    for ($i = 2; $i < $num; ++$i) {
        $n3 = $n1 + $n2;
        $n1 = $n2;
        $n2 = $n3;
        // every 50 iterations this bubble will suspend and allow other waiting bubbles to execute
        if (!($i % 50)) { 
              bubblr\reschedule();
        }
    }
    return $n3;
});

echo is_infinite($fib(3000)); // true
```

### Scheduling multiple coroutines at once

```php
function hello() {
    return 'hello';
}

function world() {
    return 'world';
}

$results = bubblr\asyncAll([
    'hello',
    'world'
]);

var_dump($results); // Array ['hello','world']
```

### Throwing and handling exceptions

```php
$addition = bubblr\async(function($a,$b) {
    if(!is_numeric($a) || !is_numeric($b)) {
        throw new \InvalidArgumentException();
    }

    return $a + $b;
});

try {
    $addition('bar',5);
} catch(\InvalidArgumentException $e) {
    echo 'Invalid argument';
}
```