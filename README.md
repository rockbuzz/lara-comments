# Lara Comments

Lara Comments is a package of Laravel 6 or higher that includes comments there are features like Posts, Boxes, etc ...

[![Build Status](https://travis-ci.org/rockbuzz/lara-comments.svg?branch=master)](https://travis-ci.org/rockbuzz/lara-comments)

## Requirements

PHP: >=7.3

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
## Add Commentable trait to models

Add the `Commentable` trait to the model for which you want to enable comments for:

```php
use Rockbuzz\LaraComments\Traits\HasComments;

class User extends Model
{
    use HasComments;
}

use Rockbuzz\LaraComments\Traits\Commentable;

class Post extends Model
{
    use Commentable;
}
```

## Usage

#### Post
```php
$post->comments; //Collection;
$post->comments(); //Builder;
$post->asPending($comment); //void;
$post->approve($comment); //void;
$post->disapprove($comment); //void;
```

#### Comment
```php
//status
$comment->isPending(); //bool;
$comment->isApprove(); //bool;
$comment->isDisapprove(); //bool;
//scope
$comment->approved(); //Builder;
$comment->pending(); //Builder;
$comment->disapproved(); //Builder;
```

#### Events
```php
\Rockbuzz\LaraComments\Events\AsPendingEvent::class;
\Rockbuzz\LaraComments\Events\ApprovedEvent::class;
\Rockbuzz\LaraComments\Events\DisapprovedEvent::class;
```


## License

The Lara Comments is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).