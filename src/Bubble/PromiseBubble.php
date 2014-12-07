<?php 
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    use React\Promise\PromiseInterface;
    
    class PromiseBubble extends BubbleTask {
        protected $promise;
        
        function __construct(PromiseInterface $promise) {
            $this->promise = $promise;
        }
        
        public function getPromise() {
            return $this->promise;
        }
               
        public function resume(SpoutInterface $spout) {
            $this->promise->then(
                function($result = null) {
                    $this->complete($this);
                    //$this->complete = true;
                    $this->result = $result;
                },
                function($reason) {
                    $this->exception = new \Exception($reason);
                }
            );
        }
    }
}