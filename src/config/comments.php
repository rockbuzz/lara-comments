<?php

return [
    'tables' => [
        'morph_names' => [
            'commentable' => 'commentable',
            'commenter' => 'commenter'
        ],
        'morph_columns' => [
            'commentable_id' => 'commentable_id',
            'commentable_type' => 'commentable_type',
            'commenter_id' => 'commenter_id',
            'commenter_type' => 'commenter_type',
        ]
    ],
    'models' => [
        'comment' => \Rockbuzz\LaraComments\Models\Comment::class,
        'commenter' => App\User::class
    ]
];
