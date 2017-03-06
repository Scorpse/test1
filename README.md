# Lumen API OAuth
[![Build Status](https://travis-ci.org/OmarElGabry/lumen-api-oauth.png)](https://travis-ci.org/OmarElGabry/lumen-api-oauth)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/OmarElGabry/lumen-api-oauth/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/OmarElGabry/lumen-api-oauth/?branch=master)
[![Code Climate](https://codeclimate.com/github/OmarElGabry/lumen-api-oauth/badges/gpa.svg)](https://codeclimate.com/github/OmarElGabry/lumen-api-oauth)
[![Dependency Status](https://www.versioneye.com/user/projects/57060d31fcd19a0039f15da4/badge.svg?style=flat)](https://www.versioneye.com/user/projects/57060d31fcd19a0039f15da4)

[![Latest Stable Version](https://poser.pugx.org/omarelgabry/lumen-api-oauth/v/stable)](https://packagist.org/packages/omarelgabry/lumen-api-oauth)
[![License](https://poser.pugx.org/omarelgabry/lumen-api-oauth/license)](https://packagist.org/packages/omarelgabry/lumen-api-oauth)

A RESTful API based on Lumen micro-framework with OAuth2. Lumen API OAuth is a simple application, indented for small projects, helps to understand creating RESTful APIs with Lumen and OAuth2, know how to authenticate and authorize, and more.

The RESTful API for Users and Transactions, where Users can view, create, update, and delete. It provides authorization mechanism to authorize against access tokens using OAuth2, ownership, and non-admin Vs admin users.

## Index
+ [Installation](#installation)
+ [Terminology](#terminology)
+ [Authorization](#authorization)
+ [Routing](#routing)
+ [Support](#support)
+ [Contribute](#contribute)
+ [Dependencies](#dependencies)
+ [License](#license)

## Installation <a name="installation"></a>
Steps:

1. Run [Composer](https://getcomposer.org/doc/00-intro.md)

	```
		composer install
	```
2. Laravel Homestead

	If you are using Laravel Homestead, then follow the [Installation Guide](https://laravel.com/docs/5.2/homestead).

3. WAMP, LAMP, MAMP, XAMP Server

	If you are using any of WAMP, LAMP, MAMP, XAMP Servers, then create a MySQL database, and update public/index.php 
	from ```$app->run();```


4. Configure the```.env``` file

	Rename ```.env.example``` file to ```.env```, set your application key to a random string with 32 characters long, edit database name, database username, and database password if needed.

5. Finally, Run Migrations and Seed the database with fake data.

	```
		php artisan migrate --seed
	```

## Terminology<a name="terminology"></a>
There are some terminologies that will be used on the meaning of the terms used by OAuth 2.0. If you need a refresher, then check [this](https://www.digitalocean.com/community/tutorials/an-introduction-to-oauth-2) out.

## Authorization<a name="authorization"></a>
Authorization comes in two layers. The first layer authorize against the access token, and the second one is for checking against ownership, and non-admin Vs admin users.

By default, user can delete or update a post or a comment **only** if he is the owner. Admins are authorized to view, create, update or delete anything.

### Access Tokens<a name="authorization"></a>
The application implements [Resource owner credentials grant](https://github.com/lucadegasperi/oauth2-server-laravel/blob/master/docs/authorization-server/choosing-grant.md#resource-owner-credentials-grant-section-43), which essentially requires the client to submit 5 fields: ```username```, ```password```, ```client_id```, ```client_secret```, and ```grant_type```.

The authorization server will then issue access tokens to the client after successfully authenticating the client credentials and presenting authorization grant(user credentials).

In ```app/Http/routes.php```, A route has been defined for requesting an access token.

### Ownership, & non-Admin Vs Admin Users<a name="authorization"></a>
Now, after validating the access token, we can extend the authorization layers and check if the current user is owner of the requested resource(i.e. post or comment), or is admin. So, _How does it work?_

**Assign Middleware to controller**
```php
	public function __construct(){
		
		$this->middleware('oauth', ['except' => ['index', 'show']]);
		$this->middleware('authorize:' . __CLASS__, ['except' => ['index', 'show', 'store']]);
	}

```

**Order**

Please note that the middlewares has to be applied in a certain order. The ```oauth``` has to be added before the ```authorize``` Middleware.

**Override isAuthorized() method**
```php
	public function isAuthorized(Request $request){

		$resource = "transactions";
		$post     = Transactions::find($this->getArgs($request)["post_id"]);

		return $this->authorizeUser($request, $resource, $post);
	}
```

In ```app/Providers/AuthServiceProvider.php```, Abilities are defined using ```Gate``` facade.

