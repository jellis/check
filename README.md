## What's it all about?

The purpose of the project was to create a syntactically simple way to implement context-based user access control. What does that mean, exactly? Good question...

### Context-based access control

I wanted to start with the idea that I could use a really straight-forward syntax for my "things" (whatever they might be). The first concept I came up with was `Check::can('post.edit')`. Because I was a fan of naming my routes, this made good sense from a flow point-of-view. Because I have my routes named, I figured I'd be able to implement middleware that would also leverage the access control system.

Adding context to the access control wasn't a trivial task. Each model will have its own context. Say in a `Post` model, "owning" a post might mean that there is a `user_id` field on the `Post` that is equal to the current user, but in a `User` model, "owning" might mean that users are in the same company as you. So, how do I have a simple syntax for implementing and checking permissions, but also giving context when the need arises?

Using the Jeffrey Way school of thought, I started with how I wanted to define things... I really wanted my `Role` classes to be so simple it's almost stupid.

```php
    $permissions = [
        'post' => [
            'index', 'create', 'store', 'view', 'edit:own', 'update:own',
        ]
    ];
```

After starting with those two ideas, I set to work and actually managed to implement them. What we have is, I think, a simple, fluent way of managing user access.

### Route Aware Models

If you have, say, a listing page for your users where they can see all posts, but can only edit their own, you'd simply have to do the following.

Register the service provider config/app.php
```php
    'providers' => [
        ...
        Jellis\Check\Providers\CheckServiceProvider::class,
        ...
    ],
```

Register the facade in config/app.php
```php
    'aliases' => [
        ...
        'Check' => Jellis\Check\Facades\Check::class,
        ...
    ],
```

Name the route and assign the middleware
```php
Route::get('post', ['uses' => 'PostController@index', 'as' => 'post.index', 'middleware' => 'check']);
```

```php
<?php

namespace App\Roles;

use Jellis\Check\Roles\Base;

class Member extends Base {

    protected $permissions = [
        'post' => [
            'index', 'view', 'create', 'store', 'view', 'edit:own', 'update:own',
        ],
    ];

}
```

Configure the model to do its thing
```php

namespace App\Models;

use Jellis\Check\RouteAwareModel;

class Post extends RouteAwareModel
{

    protected $table = 'posts';

    ...

    /**
     * This is to check against a given model
     */
    public function allowOwnOnly()
    {
        return $this->user_id == \Auth::id();
    }

    /**
     * This is to restrict things coming out of the database
     */
    public function restrictOwnOnly(Builder $builder)
    {
        $builder->where('user_id', Auth::id());
    }

}
```

Register the middleware in `Kernel.php`
```php
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'check' => \Jellis\Check\Middleware\Checker::class,
    ];
```

Retrieve some records in your controller
```php
    public function index()
    {
        // You could check stuff here if you need to
        $myThing = Check::can('my.thing');

        // Or you can do a contextual check, say, on a post
        $post = Post::find(1);

        if (Check::can('post.edit', $post)) {
            // Do some thing
        }

        // In this instance, let's pass it to the view
        $posts = Post::all();

        return view('post.index', compact('posts'));
    }
```

And in the view you can do things like
```twig
@foreach($posts as $post)
    <p>{{ $post->title }}@check('post.edit', $post)<strong>You can edit</strong>@endcheck</p>
@endforeach
```