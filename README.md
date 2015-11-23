# Illaoi

[![Build Status](https://travis-ci.org/laravelista/Illaoi.svg)](https://travis-ci.org/laravelista/Illaoi)

[![Latest Stable Version](https://poser.pugx.org/laravelista/illaoi/v/stable)](https://packagist.org/packages/laravelista/illaoi) [![Total Downloads](https://poser.pugx.org/laravelista/illaoi/downloads)](https://packagist.org/packages/laravelista/illaoi) [![Latest Unstable Version](https://poser.pugx.org/laravelista/illaoi/v/unstable)](https://packagist.org/packages/laravelista/illaoi) [![License](https://poser.pugx.org/laravelista/illaoi/license)](https://packagist.org/packages/laravelista/illaoi)

Generates slugs for Laravel.

**Has support for croatian characters čćžšđ.**

- Can generate unique slugs for database

##  Installation

First, pull in the package through Composer.

```json
"require": {
    "laravelista/illaoi": "~1.0"
}
```

And then, if using Laravel 5.1, include the service provider within `config/app.php`.

```php
'providers' => [
    ...
    Laravelista\Illaoi\IllaoiServiceProvider::class
];
```

And if you want you can add a facade alias to this same file at the bottom:

```php
'aliases' => [
    ...
    'Illaoi' => Laravelista\Illaoi\Facades\Illaoi::class
];
```

## API

#### `generate($text)`

Just pass it some trivial text and expect slug in return.

```php
Illaoi::generate('This is a post')

// returns: this-is-a-post
```

#### `generateUnique($text, Model $model, $idToIgnore = null)`

Use this when you want to create a unique slug for a model.

It searches for generated slug in Model by field `slug` and if found increments last number by  1 starting from number 2.

eg. this-is-a-post, this-is-a-post-2, this-is-a-post-3

##### Creating new Model

```php
Illaoi::generateUnique('This is a post', new App\Post);

// returns: this-is-a-title
```

##### Updating Model

```php
$post = Post::create([
    'id' => 1,
    'title' => 'This is a post,
    'slug' => 'this-is-a-post'
]);

Illaoi::generateUnique('This is a post', new App\Post, 1);

// returns: this-is-a-title

Illaoi::generateUnique('This is a post', new App\Post);

// returns: this-is-a-title-2
```