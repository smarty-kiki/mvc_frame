<?php

if_get('/', function ()
{
    return render('index/index', [
        'title' => 'hello world',
        'url_infos' => [
        ],
    ]);
});
