<?php
namespace bubblr\Bubble {
    use bubblr\Spout\SpoutInterface;
    
    trait BubbleTrait {
        protected $result;
        
//        protected $canceled = false;
//        
//        protected $complete = false;
//        
//        protected $failure = false;
//        
//        protected $exception;
        
        public function getResult() {
            if($this->result instanceof BubbleInterface) {
                return $this->result->getResult();
            }
            
            return $this->result;
        }
        
        abstract public function resume(SpoutInterface $spout);
        
        abstract public function suspend(SpoutInterface $spout);
        
//        public function isSuccess() {
//            return (!$this->canceled) && $this->complete && (!$this->failure);
//        }
//        
//        public function isCanceled() {
//            return $this->canceled;
//        }
//
//        public function isComplete() {
//            return $this->complete;
//        }
//
//        public function isFailure() {
//            return $this->failure;
//        }
//        
//        /**
//         * Retrieve failure exception
//         * @return \Exception
//         */
//        public function getException() {
//            return $this->exception;
//        }
//        
//        /**
//         * Update failure exception
//         * @param \Exception $exception
//         */
//        public function setException(\Exception $exception) {
//            $this->exception = $exception;
//        } 
    }
}