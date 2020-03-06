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
use Rockbuzz\LaraComments\TestHelper;

class Post extends Model
{
    use TestHelper;
}
```

## Usage

#### Post
```php
$post->comments();
```

#### Comment
```php
//scope
$comment->approved();
$comment->pending();
$comment->disapproved();
//change status
$comment->approve();
$comment->disapprove();
$comment->asPending();
```

#### Events
```php
\Rockbuzz\LaraComments\Events\AsPendingEvent::class;
\Rockbuzz\LaraComments\Events\ApprovedEvent::class;
\Rockbuzz\LaraComments\Events\DisapprovedEvent::class;
```


## License

The Lara Comments is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).