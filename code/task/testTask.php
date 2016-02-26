<?php

class testTask extends task_base {

    public function testAction() {

        $data = petal::boardInfo(1161553);
        var_dump($data);

        $daoBoard = new dao_board();
        $ret = $daoBoard->save($data);
        var_dump($ret);
    }

}
