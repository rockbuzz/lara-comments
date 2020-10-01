# Lara Comments

Comments is a package for managing comments on features like Posts

[![Build Status](https://travis-ci.org/rockbuzz/lara-comments.svg?branch=master)](https://travis-ci.org/rockbuzz/lara-comments)

## Requirements

PHP >=7.2

## Install

```bash
$ composer require rockbuzz/lara-comments
```

## Run migrations

We need to create the table for comments.

```bash
php artisan vendor:publish --provider="Rockbuzz\LaraComments\ServiceProvider"
```

```bash
php artisan migrate
```

Add the `HaveComments and Commentable` trait in models for:

```php
use Rockbuzz\LaraComments\Traits\HaveComments;

class User extends Model
{
    use HaveComments;
}

use Rockbuzz\LaraComments\Traits\Commentable;

class Post extends Model
{
    use Commentable;
}
```

## Usage

#### User
```php
$post->comments; //Collection;
$post->hasComments(); //bool;
```

#### Post
```php
$post->commenter: //User
$post->commentable; //Collection;
$post->asPending($comment); //void;
$post->approve($comment); //void;
$post->unapprove($comment); //void;
```

#### Comment
```php
$comment->isPending(); //bool;
$comment->isApprove(); //bool;
$comment->isUnapprove(); //bool;
```

Scope
```php
Comment::approved(); //Builder;
Comment::pending(); //Builder;
Comment::unapproved(); //Builder;
```

#### Events
```php
\Rockbuzz\LaraComments\Events\AsPendingEvent::class;
\Rockbuzz\LaraComments\Events\ApprovedEvent::class;
\Rockbuzz\LaraComments\Events\UnapprovedEvent::class;
```


## License

The Lara Comments is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).