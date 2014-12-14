<?php
namespace bubblr\Tests {
    use bubblr;
    
    function apredefinedFunc() {
        return 'hello world';
    }
    
    class SchedulerTest extends BubblrTestCase {
        
        function testBubbler() {
            $c=0;
            bubblr\Bubbler::spout(function()use(&$c) {
                $c++;
            });
            
            $this->assertEquals(1,$c);
        }
        
        function testBubblerExecute() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            
            $c=0;
            $bubbler->getSpout()->push(new bubblr\Bubble\CallableBubble(function()use(&$c) {
                $c++;
            }));
            
            $bubbler->execute();
            
            $this->assertEquals(1,$c);
        }
        
        function testAggregateSpout() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            new bubblr\Bubbler\BubblerSpout($bubbler);
            
            $c=0;
            $bubbler->execute(function()use(&$c) {
                $c++;
            });
            
            $spout = $bubbler->getSpout();
            
            $this->assertInstanceOf('bubblr\Spout\AggregateSpout',$spout);
            $this->assertEquals(2,$c);
        }
        
        function testSpoutRepush() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            
            $spout = $bubbler->getSpout();
            
            $c = 0;
            
            $bubble = new bubblr\Bubble\CallableBubble(function()use(&$c) {
                $c++;
            });
            
            $spout->push($bubble,1);
            $spout->push($bubble,1);
            
            $bubbler->execute();
            
            $this->assertEquals(2,$c);
        }
        
        function testCallableBubble() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            
            $spout = new bubblr\Bubbler\BubblerSpout($bubbler);
            
            $c = 0;
            
            $spout->push(new bubblr\Bubble\CallableBubble(function()use(&$c) {
                $c++;
            },10));
            
            $spout->push(new bubblr\Bubble\CallableBubble(function()use(&$c) {
                $c++;
            },5));
            
            $bubbler->execute();
            
            $this->assertEquals(15,$c);
        }
        
        function testCallableBubbleReturnValue() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            
            $bubble = new bubblr\Bubble\CallableBubble(function() {
                return 'result';
            });

            $bubbler->execute($bubble);
            
            $this->assertEquals('result',$bubble->getResult());
        }
        
        function testImplicitBubble() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            
            $c = 0;
            
            $bubbler->execute(function()use(&$c) {
                $c++;
            });
            
            $this->assertEquals(1,$c);
        }
        
        function testEmitterBubble() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            
            $spout = new bubblr\Bubbler\BubblerSpout($bubbler);
            
            $click = new \observr\Emitter('click');
            
            $msdwn = new \observr\Emitter('mousedown');
            
            $click->attach(function($s,$e)use(&$c) {
                $c++;
            });
            
            $msdwn->attach(function($s,$e)use(&$c) {
                $c++;
            });
            
            $spout->push(new bubblr\Bubble\EmitterBubble($click));
            $spout->push(new bubblr\Bubble\EmitterBubble($click));
            
            $bubbler->execute();
            
            $this->assertEquals(2,$c);
        }
        
        private function microtime_float()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float)$usec + (float)$sec);
        }
        
        function testDelayedBubble() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            
            $spout = new bubblr\Bubbler\BubblerSpout($bubbler);
            
            $c = 0;
            $o = '';
            
            $delayedBubble = new bubblr\Bubble\CallableBubble(function()use(&$c, &$o) {
                $c++;
                $o.='3';
            });
            
            $delayedBubble2 = new bubblr\Bubble\CallableBubble(function()use(&$c, &$o) {
                $c++;
                $o.='1';
            });
            
            $delayedBubble3 = new bubblr\Bubble\CallableBubble(function()use(&$c, &$o) {
                $c++;
                $o.='2';
            });
            
            $spout->push($delayedBubble2);
            $spout->push(new bubblr\Bubble\DelayedBubble($delayedBubble,1));
            $spout->push($delayedBubble3);
            
            $startTime = $this->microtime_float();
            $bubbler->execute();
            $endTime = $this->microtime_float();
            
            $duration = $endTime-$startTime;
            $this->assertEquals(1,(int)$duration);
            $this->assertEquals(3,$c);
            $this->assertEquals('123',$o);
        }
        
        function testThrottleBubble() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            
            $spout = new bubblr\Bubbler\BubblerSpout($bubbler);
            
            $c = 0;
            
            $delayedBubble = new bubblr\Bubble\CallableBubble(function()use(&$c) {
                $c++;
            });
            
            $throttleBubble = new bubblr\Bubble\ThrottledBubble($delayedBubble,2);
            
            $spout->push($throttleBubble);
            
            $bubbler->execute();
            $this->assertEquals(1,$c);
        }
        
        function testSpoutEvents() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            
            $spout = new bubblr\Bubbler\BubblerSpout($bubbler);
            
            $c = 0;
            
            $delayedBubble = new bubblr\Bubble\CallableBubble(function() {
                
            },2);
            
            $spout->push(new bubblr\Bubble\DelayedBubble($delayedBubble,2));
            
            $spout->attach(\observr\Event::SUCCESS,function($bubble, $e)use(&$c) {
                $c++;
            });
            
            $spout->attach(\observr\Event::COMPLETE,function($bubble, $e)use(&$c) {
                $c++;
            });
            
            $bubbler->execute();
            $this->assertEquals(2,$c);
        }
        
        function testPromiseBubble() {
            $promise = \React\Promise\Resolve("resolved");
            
            $bubble = new bubblr\Bubble\PromiseBubble($promise);
            
            \bubblr\spout($bubble);
            
            $this->assertTrue($bubble->isComplete());
            $this->assertFalse($bubble->isFailure());
            
            $this->assertEquals("resolved",$bubble->getResult());
        }
        
        function testAggregateBubble() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            
            $spout = $bubbler->getSpout();
            
            $c = 0;
            
            $bubbles = [];
            $bubbles[] = function()use(&$c) {
                $c++;
            };
            $bubbles[] = function()use(&$c) {
                $c++;
            };
            
            $spout->push(new bubblr\Bubble\AggregateBubble($bubbles));
            
            $bubbler->execute();
            $this->assertEquals(2,$c);
        }
        
        function testResultHandling() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            
            $spout = new bubblr\Bubbler\BubblerSpout($bubbler);
            
            $c = 0;
            
            $spout->push(new bubblr\Bubble\CallableBubble(function()use(&$c) {
                $c++;
                return 'hello';
            }));
            
            $spout->push(new bubblr\Bubble\CallableBubble(function()use(&$c) {
                $c++;
                return 'world';
            }));
            
            
            $result = $bubbler->execute();
            $this->assertEquals([
                'hello',
                'world'
            ], $result);
            
            $this->assertEquals(2,$c);
        }
        
        function testAsyncPrequeue() {
            $bubbler = new bubblr\Bubbler\AggregateBubbler;
            
            $spout = new bubblr\Bubbler\BubblerSpout($bubbler);
            
            $c = 0;
            
            $spout->push(new bubblr\Bubble\CallableBubble(function()use(&$c,$spout) {
                \bubblr\reschedule($spout);
                $c++;
                return 'hello';
            }));
            
            $spout->push(new bubblr\Bubble\CallableBubble(function()use(&$c) {
                $c++;
                return 'world';
            }));
            
            $result = $bubbler->execute();
            
            $this->assertEquals([
                'world',
                'hello'
            ],$result);
            
            $this->assertEquals(2,$c);
        }
        
        function testBubblerAsync() {
            $c = 0;
            
            $hello = \bubblr\async(function()use(&$c) {
                $c++;
                return 'hello world';
            });
            
            $result = $hello();
            
            $this->assertEquals('hello world',$result);
            
            $this->assertEquals(1,$c);
        }
        
        public function predefinedFunc() {
            return 'hello world';
        }
        
        function testPredefinedAsync() {
            $hello = \bubblr\async([$this,'predefinedFunc']);
            
            $result = $hello();
            
            $this->assertEquals('hello world', $result);
        }
        
        function testAsyncAll() {
            $hellos = \bubblr\asyncAll([
                [$this,'predefinedFunc'],
                [$this,'predefinedFunc']
            ]);
            
            $result = $hellos();
            
            $this->assertEquals([
                'hello world',
                'hello world'
            ], $result);
        }
        
        function testAsyncStringAll() {
            $hellos = \bubblr\asyncAll([
                'bubblr\Tests\apredefinedFunc',
                'bubblr\Tests\apredefinedFunc'
            ]);
            
            $result = $hellos();
            
            $this->assertEquals([
                'hello world',
                'hello world'
            ], $result);
        }
        
        function testFibonacci() {
            $fib = \bubblr\async(function($num) {
                $n1 = 0; $n2 = 1; $n3 = 1;
                for ($i = 2; $i < $num; ++$i) {
                    $n3 = $n1 + $n2;
                    $n1 = $n2;
                    $n2 = $n3;
                    if (!($i % 50)) {
                          \bubblr\reschedule();
                    }
                }
                return $n3;
            });
            
            $result = $fib(3000);
            
            $this->assertEquals(true, is_infinite($result));
        }
    }
}