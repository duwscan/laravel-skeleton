# Laravel Skeleton
A stater project with best practices and principles that I collect from different sources and my own experience.
### Requirements
- PHP >= 8.3
- Composer
- Node
### Before you start
In this repository, i'm using : 
- [Laravel](https://laravel.com/) framework.
- [Laravel Actions](https://www.laravelactions.com/) package.
- [Laravel Permission](https://spatie.be/docs/laravel-permission/) package.  
So, you should have a basic understanding of Laravel, Laravel Actions, and Laravel Permission.
# Table of Contents

1. [Laravel Skeleton](#laravel-skeleton)
2. [Requirements](#requirements)
3. [Before you start](#before-you-start)
4. [Installation](#installation)
5. [Application Folder Overview](#application-folder-overview)
6. [Components](#components)
    - [Main Components](#main-components)
        - [Routes](#routes)
        - [Requests](#requests)
        - [Controllers](#controllers)
        - [Actions](#actions)
        - [Tasks](#tasks)
        - [Resource](#resource)
        - [Exceptions](#exceptions)
    - [Optional Components](#optional-components)

### Installation
- Clone the repository
```bash
git clone https://github.com/duwscan/laravel-skeleton.git
```
- Reintialize the repository if you want to use it as your own
```bash
git --init
```
- Install composer dependencies
```bash
composer install
```
- Install npm dependencies (If you need husky)
```bash
npm install
```
- Copy the .env.example file to .env
```bash
cp .env.example .env
```
- Generate the application key
```bash
php artisan key:generate
```
### Application Folder Overview
- **Core** - Contains the core functionalities trait, classes, and interfaces that are commonly used across the application.
- **Modules** - Contains the application modules. Each module is a standalone module.
- **Entities** - Contains the application database entities.
- **bootstrap** - Contains the application bootstrap files.
- **config** - Contains the application configuration files.
- **database** - Contains the application database migrations and seeds.
- **public** - Contains the application public files.
- **resources** - Contains the application resources.
- **lang** - Contains the application localization files.
### Components
#### Main Components
The key components include Routes, Requests, Controllers, Actions, Tasks, Resource, Exceptions.  
Application especially advocates for the use of Actions and Tasks which can effectively replace traditional Services.  
##### Routes
Routes are responsible for mapping all incoming HTTP requests to their controller's functions. When an HTTP request hits the Application, the Endpoints match with the URL pattern and make the call to the corresponding Controller function.  
Routes mapping can be done with pattern
```
    src/Modules/{module_name}/Routes/{version}/api.php
```
which will be loaded automatically by the src/Core/Providers/RouteServiceProvider.  
Eg - src/Modules/User/Routes/v1/api.php will be loaded as /api/v1/users/{endpoint}  
Principles: 
- Routes should be versioned.
- Every module should have its own route file and Routes folder.
##### Requests
Requests mainly serve the user input in the application. They are very useful to automatically apply the Validation and Authorization rules.  
Principles:
- Requests hold the validation rules.
- Requests MAY also be used for authorization; they can check if the user is authorized to make a request.  
##### Controllers
Controllers are responsible for validating the request, serving the request data, and building a response. Validation and response happen in separate classes but are triggered from the Controller.  
Principles:
- Controllers should be thin, and may not know about any business logic.
- A Controller SHOULD only do the following jobs:
  - Read the request data.
  - Calling an Action (and maybe pass Request Object to it as parameter).
  - Building a Response (usually builds the response based on the data collected from the Action call)
- Every Module should have its own Controllers.
- Controllers should not call the Task. They may only call the Action and the Action will call the Task.  
##### Actions
Actions represent the Use Cases - The business Logic of the Application (i.e., the actions that can be performed by a user or Modules in the application).  
Principles:
- Actions must use src/Core/Traits/AsAction trait.
- Every Action should be responsible for performing a single use case in the application.
- An Action may retrieve data from Tasks ,Requests or DTO and pass data to another Task.
- An Action may call multiple Tasks, and can call Tasks from other Modules.
- An Action may return a response to Controller.
- The Action main function handle() can accept a Request Object in the parameter.
- Actions are responsible for handling all expected Exceptions.
- Authorize an Action base on the logic "Which permissions/roles/abilities is required to perform this action?".  
- Each Module should have its own Actions.
#####
Pseudo Code Example:
```php

<?php

namespace Modules\User\Actions;

use Core\Traits\AsAction;

class CreateAdminAction
{
    use AsAction;

    protected array $access = [
        'roles' => ['admin'],
        'permissions' => ['create-admin'],
        'can' => ['create-admin'],
    ];
    
    public function handle(CreateAdminRequest $request): array
    {
       // impl create admin logic
    }
}
```
##### Tasks
Tasks are classes that hold shared business logic between multiple Actions across different Modules.
Principles:
- Tasks must use src/Core/Traits/AsTask trait.
- Every Task SHOULD have a single responsibility (job).
- A Task MAY receive and return Data. (Task SHOULD NOT return a response, the Controller's job is to return a response).
- A Task SHOULD NOT call another Task. Because that will take us back to the Services, which can lead to a big mess.
- A Task SHOULD NOT call an Action. (Logic does not make sense
- A Task SHOULD NOT be called from the Controller. Because this leads to non-documented features in your code.
##### Resource
 Are equivalent to JSON Responses Resource in Laravel. They take data and represent it in JSON, transforming Models into Arrays.
##### Exceptions
Exceptions are a form of output that should be expected (like an API exception) and well defined. They are a way to handle errors in a well-defined and expected manner.
Principles:
- Tasks, Action, Models, and any class in general can throw a very specific Exception.
- Exceptions names SHOULD be as specific as possible, and they SHOULD have clear descriptive messages.
- Exceptions SHOULD be caught in the Controller by the src/Core/Exceptions/ExceptionHandler and transformed into a JSON response.
- Exception code SHOULD be a unique identifier for the exception defined in src/Core/Exceptions/ExceptionCode.
- Exception code SHOULD be localization defined in lang/en/exceptions.php
- Each Module SHOULD have its own Exception.
####
How the status code is determined ? Read the [ExceptionCode](/src/Core/Exceptions/ExceptionCode.php)  
Pseudo Code Example:
```php
class UserException extends InternalException
{
    public static function userAlreadyExists(): self
    {
        return static::new(
            ExceptionCode::UserAlreadyExists
        );
    }
}
```
#### Optional Components
Are the components that are not mandatory but can be used to improve the application.  
Eg - DTO, Services, Repositories, Jobs, Events, Listeners, Mails, Notifications, Policies, Rules, etc.  
But if any Module needs to use these components, they should be placed in the Module folder.
##### Scopes
Scopes are a way to encapsulate common queries that you will use in your application.  
Principles:
- Scopes are trait that use in Model Builder.
####
Note: Using Scopes is a alternative of Repository.  
Pseudo Code Example:  
- Model Builder:
```php
<?php

namespace Modules\User\Builder;

use Core\Scopes\MutationQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class UserQueryBuilder extends Builder
{
    use MutationQuery;

    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }
}
```
- Model using Model Builder
```php
   public function newEloquentBuilder($query)
    {
        return new UserQueryBuilder($query);
    }
```



