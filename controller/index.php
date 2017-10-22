<?php

if_get('/', function ()
{
    return render('index/index', [
        'text' => 'hello world',
    ]);
});
