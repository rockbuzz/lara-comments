# Lara Comments

Comments is a package for managing comments on features like Posts

<p><img src="https://github.com/rockbuzz/lara-comments/workflows/Main/badge.svg"/></p>

## Requirements

PHP >=7.2

## Install

```bash
$ composer require rockbuzz/lara-comments
```

```bash
php artisan vendor:publish --provider="Rockbuzz\LaraComments\ServiceProvider"
```

```bash
php artisan migrate
```

Add the `Commenter and Commentable` trait in models for:

```php
use Rockbuzz\LaraComments\Traits\{Commentable, Commenter};

class User extends Authenticatable
{
    use Commenter;
}

class Post extends Model
{
    use Commentable;
}
```

## Usage

#### User
```php
$user->comments(): HasMany;
$user->commentOn(Model $commentable, string $body, string $title = null): Comment;
$user->likes(): BelongsToMany;
$user->likeTo(Comment $comment): void;
$user->dislikeTo(Comment $comment): void;
```

#### Post
```php
$post->comments(): MorphMany;
$post->hasComments(): bool;
$post->asPending($comment): void;
$post->approve($comment): void;
$post->unapprove($comment): void;
```

#### Comment
```php
$comment->commenter(): BelongsTo;
$comment->commentable(): MorphTo;
$comment->children(): HasMany;
$comment->parent(): BelongsTo
$comment->isPending(): bool;
$comment->isApprove(): bool;
$comment->isUnapprove(): bool;
```

Scope
```php
Comment::approved();
Comment::pending();
Comment::unapproved();
```

#### Events
```php
\Rockbuzz\LaraComments\Events\AsPendingEvent::class;
\Rockbuzz\LaraComments\Events\ApprovedEvent::class;
\Rockbuzz\LaraComments\Events\UnapprovedEvent::class;
```


## License

The Lara Comments is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).