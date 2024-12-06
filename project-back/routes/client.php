<?php

return [
    [
        'uri' => '/clients', 
        'method' => 'GET', 
        'action' => 'ClientController@index'
    ],
    [
        'uri' => '/clients/create', 
        'method' => 'POST', 
        'action' => 'ClientController@create'
    ],
    [
        'uri' => '/clients/show', 
        'method' => 'GET', 
        'action' => 'ClientController@show'
    ],
];
