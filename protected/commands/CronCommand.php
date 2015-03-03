<?php

class CronCommand extends CConsoleCommand {

    public function run($agrs) {
        echo 'hello world';
    }

    public function actionIndex() {
        echo 'run index';
    }

}
