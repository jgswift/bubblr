<?php
namespace bubblr\Bubbler {
    use bubblr\Spout\SpoutInterface;
    use bubblr\Spout\SpoutTrait;
    use bubblr\Bubble\BubbleInterface;
    use observr\Subject;
    use observr\Subject\SubjectInterface;
    use observr\Event;
    
    class BubblerSpout implements SpoutInterface, SubjectInterface {
        use SpoutTrait, Subject;
        
        public function invoke(BubbleInterface $bubble) {
            $e = new Event($this);
            try {
                $result = $bubble->resume($this);
                if($bubble->isSuccess()) {
                    $this->setState(Event::SUCCESS,$e);
                }
            } catch (Exception $ex) {
                $e['exception'] = $ex;
                $this->setState(Event::FAIL,$e);
            } finally {
                if($bubble->isComplete()) {
                    $this->setState(Event::COMPLETE,$e);
                }
            }
        }
    }
}