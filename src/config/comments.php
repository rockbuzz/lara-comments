<?php

return [
    'tables' => [
        'comments' => 'comments'
    ],
    'models' => [
        'comment' => \Rockbuzz\LaraComments\Comment::class,
        'commenter' => App\User::class
    ]
];
