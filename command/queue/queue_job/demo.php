<?php

queue_job('demo', function () {/*{{{*/

    sleep(1);

    log_module('queue', 'demo successful!');

    return true;

}, 10, [1, 1, 1], 'default');/*}}}*/
