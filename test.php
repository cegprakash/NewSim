<?php
function __autoload($class)
{
    include __DIR__ . '/' . substr($class, strrpos($class, '\\')+1) . '.php';
}

$manager = new ThroughBall\Util\UDPManager('testing');
$opponent = new ThroughBall\Util\UDPManager('opponent');

class Tester extends ThroughBall\Player {
    protected $goaldirection = 0;
    protected $mygoaldirection = 0;
    protected $visiblegoal;
    function handleSenseBody($sensebody)
    {
        parent::handleSenseBody($sensebody);
        if (!$this->cycle) return;
        $see = $this->see;
        $params = $this->see->listSeenItems();
        if (!count($params)) {
            $this->turn(-180);
            return;
        }
        //var_export($this->toRelativeCoordinates($this->getCoordinates()));
        $goal = $see->getItem($this->ownGoal());
        if ($goal) {
            $this->mygoaldirection = $goal['direction'];
            $this->visiblegoal = $this->side;
            //echo $this->team, " ", $this->side, " see own ", $this->ownGoal(), " goal ", $goal['direction'], "\n";
        }
        $goal = $see->getItem($this->opponentGoal());
        if ($goal) {
            $this->goaldirection = $goal['direction'];
            $this->visiblegoal = $this->opponent();
            //echo $this->team, " ", $this->side, " see opponent ", $this->opponentGoal(), " goal ", $goal['direction'], "\n";
        }
        $ball = $see->getItem('(b)');
        if ($ball) {
            if ($this->isKickable($ball)) {
                if ($this->visiblegoal == $this->opponent()) {
                    $goal = $this->see->getItem($this->opponentGoal());
                    if (!$goal) {
                        $this->turn(-45);
                    } else {
                        $this->shoot();
                    }
                } else {
                    $this->kick(100, 180 - $this->mygoaldirection);
                }
            } else {
                if ($ball['distance'] < 30 && ($ball['direction'] > 5 || $ball['direction'] < -5)) {
                    echo 'turn to ball ', $ball['direction'], ' me', $sensebody->getParam('direction'),"\n";
                    $this->turnTowards($ball);
                } else {
                    echo 'move to ball ', $ball['direction'], ' me', $sensebody->getParam('direction'),"\n";
                    $this->moveTowards($ball, 60);
                }
            }
            return;
        }
        $this->turn(70);
    }
}


//$goalie = $manager->addGoalie();
$player1 = $manager->addPlayer('Tester');
//$player2 = $manager->addPlayer('Tester');

//$goalie = $opponent->addGoalie();
//$player = $opponent->addPlayer('Tester');
//$player3 = $opponent->addPlayer('Tester');

//$player->move(-10, 10);
$player1->move(-10, 10);
//$player2->move(-20, 20);
//$player3->move(-30, 30);
$manager->run();
?>