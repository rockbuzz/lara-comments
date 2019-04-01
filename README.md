# Lara Comments

Lara Comments is a package of Laravel 5.5 or higher that includes comments there are features like Posts, Boxes, etc ...

[![Build Status](https://travis-ci.org/rockbuzz/lara-comments.svg?branch=master)](https://travis-ci.org/rockbuzz/lara-comments)

## Requirements

PHP: >=7.1

## Install

```bash
$ composer require rockbuzz/lara-comments
```

## Run migrations

We need to create the table for comments.

```bash
php artisan vendor:publish --provider="Rockbuzz\LaraComments\CommentsServiceProvider"
```

```bash
php artisan migrate
```
## Add Commentable trait to models

Add the `Commentable` trait to the model for which you want to enable comments for:

```php
use Rockbuzz\LaraComments\Commentable;

class Post extends Model
{
    use Commentable;
}
```

## Methods

```php
$repository = new Rockbuzz\LaraComments\CommentsRepository;
$repository->all(string $commentableType): Builder
$repository->pending(string $commentableType): Builder
$repository->approved(string $commentableType): Builder
$repository->unapproved(string $commentableType): Builder
$repository->approve(Comment $comment)
$repository->unapprove(Comment $comment)
$repository->delete(Comment $comment)
```


## License

The Lara Comments is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).