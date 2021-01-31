<?php

/** роуты */
return [
    ['url' => '', 'action' => 'index', 'controller' => 'todo',],
    ['url' => 'todo/list', 'action' => 'list', 'controller' => 'todo'],
    ['url' => 'todo/create', 'action' => 'create', 'controller' => 'todo'],
    ['url' => 'todo/update', 'action' => 'update', 'controller' => 'todo'],
    ['url' => 'auth/login', 'action' => 'login', 'controller' => 'auth'],
    ['url' => 'auth/logout', 'action' => 'logout', 'controller' => 'auth'],
];
