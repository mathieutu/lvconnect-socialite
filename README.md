# LVConnect Socialite

## Abstract

[LVConnect](https://gitlab.com/LinkValue/Lab/LVConnect/LvConnect) is a project that aims to unify login over all Linkvalue apps with an authentication service based on OAuth2.

[Linkvalue](https://link-value.fr) is a french web/mobile agency.

[Socialite](https://github.com/laravel/socialite) is a simple and convenient way to authenticate users with OAuth providers for Laravel. 

**This package is an internal tool. You can download it and use it as an example for making your own, but it will be unusable if you're not a Linkvalue partner.** 
  
## Installation

This assumes that you have composer installed globally
```bash
composer require mathieutu/lvconnect-socialite
```

## Configuration setup

You will need to add an entry to the services configuration file so that after config files are cached for usage in production environment (Laravel command `artisan config:cache`) all config is still available.

#### Add to `config/services.php`.

```php
 'lvconnect' => [
    'client_id' => env('LVCONNECT_ID'),
    'client_secret' => env('LVCONNECT_SECRET'),
    'redirect' => env('LVCONNECT_CALLBACK'),
],
```

If you want to do some test with another url that production one, you can optionally add an url key with the base url: 
```php
    'url' => 'https://lvconnect-staging.herokuapp.com',
```

#### Fill your `.env` / environment variables:
[Create your application](https://lvconnect.link-value.fr/dashboard/apps/new) in the LVConnect website and fill the information in your env:

```dotenv
LVCONNECT_ID=XXXX
LVCONNECT_SECRET=XXXX
LVCONNECT_CALLBACK=http://my-full-website-url.localhost/login/callback
```

## Usage

See [Laravel docs](https://laravel.com/docs/master/socialite) on socialite usage.

* You should now be able to use it like you would regularly use Socialite.

```php
return $socialite->driver('lvconnect')->redirect();
```

**You can publish all the [auth scaffolding](https://github.com/mathieutu/lvconnect-socialite/tree/master/src/stubs) with the following command:**
```bash 
php artisan lvconnect:publish
```

### Stateless

You can set whether or not you want to use the provider as stateless. LVConnect supports whatever option you choose.

**Note:** If you are using this with Lumen, all providers will automatically be stateless since **Lumen** does not keep track of state.

```php
// to turn off stateless
return Socialite::with('lvconnect')->stateless(false)->redirect();

// to use stateless
return Socialite::with('lvconnect')->stateless()->redirect();
```

### The LVConnect User.

The user that socialite will provide has this shape:
```
User {#341 ▼
  +accessTokenResponseBody: array:6 [▼
    "access_token" => "0bc47e47-1e88-4d08-a402-1cd7c235de3d"
    "token_type" => "bearer"
    "expires_in" => 172800
    "refresh_token" => "73a17012-b999-4300-8d4d-99e8675abc08"
    "scope" => array:3 [▼
      0 => "users:get"
      1 => "users:modify"
      2 => "profile:get"
    ]
    "need_password_change" => false
  ]
  +token: "0bc47e47-1e88-4d08-a402-1cd7c235de3d"
  +refreshToken: "73a17012-b999-4300-8d4d-99e8675abc08"
  +expiresIn: 172800
  +id: "589ae9cfe2eb1d0009790659"
  +nickname: null
  +name: "Foo BAR"
  +email: "foo.bar@link-value.fr"
  +avatar: "https://www.gravatar.com/avatar/d5763d35fe855464633ff3ec5064ecad?s=200"
  +user: array:10 [▼
    "tags" => []
    "roles" => array:5 [▼
      0 => "tech"
      1 => "business"
      2 => "hr"
      3 => "board"
      4 => "com"
    ]
    "firstName" => "Foo"
    "lastName" => "BAR"
    "email" => "foo.bar@link-value.fr"
    "createdAt" => "2017-02-08T09:50:07.813Z"
    "city" => "Lyon"
    "description" => "A really good friend!"
    "profilePictureUrl" => "https://www.gravatar.com/avatar/d5763d35fe855464633ff3ec5064ecad?s=200"
    "id" => "589ae9cfe2eb1d0009790659"
  ]
}
```

It's a instance of \Laravel\Socialite\Two\User class.

#### Reference

* [Laravel Socialite Docs](https://laravel.com/docs/master/socialite)
* [Laracasts Socialite video](https://laracasts.com/series/whats-new-in-laravel-5/episodes/9)

## License

This LVConnect Socialite package is an open-sourced software licensed under the MIT license.

## Contributing

Issues and PRs are obviously welcomed and encouraged, for features as well as documentation.
