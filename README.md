# CRUD Module API Generator Command

The CRUD Module API Generator command is a Laravel Artisan command designed to automate the creation of CRUD (Create, Read, Update, Delete) files based on a specified namespace.

## Purpose

The purpose of this command is to streamline the development process by generating boilerplate code for CRUD operations in Laravel applications. By providing a namespace, the command creates the necessary folder structure and files following specific naming conventions and coding standards, including a working resource CRUD API for single entities with pagination and search functionality.

## Architecture and Patterns

The CRUD Module API Generator follows a structured architecture and design patterns to ensure maintainability, scalability, and adherence to best practices in software development. The architecture consists of multiple layers, including:

1. **Presentation Layer:** Responsible for handling user interactions and presenting data to the user. This layer includes controllers for handling HTTP requests, form request classes for validating incoming data, and resource classes for transforming data before sending it to the client.

2. **Domain Layer:** Contains the business logic of the application. This layer includes validator classes implementing the ValidationInterface, defining validation rules for entities, ensuring data integrity, and validating inputs.

3. **Infrastructure Layer:** Handles interactions with external systems such as databases. This layer includes eloquent models representing entities in the database and defining relationships between them.

4. **Services Layer:** Implements the application's use cases by orchestrating interactions between the domain and infrastructure layers. Service classes implement the ServiceInterface and accept repository interfaces in their constructors, providing a clear separation of concerns and promoting code reusability.

5. **Repositories Layer:** Acts as an intermediary between the domain and infrastructure layers, providing a clean and consistent interface for accessing and manipulating data. Repository classes implement repository interfaces defined in the contracts folder, allowing different data storage implementations without affecting the business logic.

6. **Routes Layer:** Defines the application's HTTP routes and maps them to controller methods, enabling the execution of specific actions based on incoming requests.

7. **Configuration Layer:** Handles service registration and binding in the Laravel application. The service provider classes in the Config folder bind repository and service interfaces to their respective implementations, facilitating dependency injection and inversion of control.

By adhering to this architectural pattern, the CRUD Module API Generator promotes modularity, separation of concerns, and maintainability, making it easier to extend and evolve the application over time. Additionally, it leverages Laravel's built-in features and conventions to streamline development and ensure consistency across the codebase.

## Requirements

- Laravel framework
- PHP >= 7.4

## Installation

1. Clone this repository to your local machine.
2. Navigate to the project directory in your terminal.
3. Run `composer install` to install the project dependencies.

## Usage

To use the CRUD Module API Generator command, follow these steps:

1. Open your terminal.
2. Navigate to your Laravel project directory.
3. Run the following command:

```bash
php artisan app:module {namespace}
```

Replace `{namespace}` with the desired namespace for your CRUD operations.

For example:

```bash
php artisan app:module App\API\V1\Products
```

This command will create the necessary folder structure and files for the specified namespace.

## Folder Structure

The command generates the following folder structure:

- `app`
  - `{Namespace}`
    - `Contracts`
    - `Domain`
    - `Presentation`
      - `Controllers`
      - `Requests`
      - `Resources`
    - `Infrastructure`
    - `Services`
    - `Repositories`
    - `Routes`
    - `Config`

## Naming Conventions

- Method, functions, variables: camelCase
- Class: Initial capital on every word
- File prefix: End of namespace word
- Namespace: Full namespace with App\API instead of App\

## File Generation

The command generates the following files:

- Controller
- CreateFormRequest
- UpdateFormRequest
- Resource
- Validator
- Service
- Repository
- ServiceProvider
- Route file

## Example

```bash
php artisan app:module App\API\V1\Products
```

This will create the necessary folder structure and files for the `App\API\V1\Products` namespace.

## Integration with Laravel

To integrate the generated module with your Laravel application, follow these steps:

### 1. Adding Service Provider

Open the `config/app.php` file in your Laravel project and locate the `providers` array. Add the generated module's service provider to this array. The service provider class should be located in the `Config` directory of the generated module.

```php
/*
|--------------------------------------------------------------------------
| Autoloaded Service Providers
|--------------------------------------------------------------------------
|
| The service providers listed here will be automatically loaded on the
| request to your application. Feel free to add your own services to
| this array to grant expanded functionality to your applications.
|
*/

return [
    // Other service providers...
    App\YourGeneratedModule\Config\YourModuleServiceProvider::class,
];
```

Replace `YourGeneratedModule` with the actual namespace of your generated module and `YourModuleServiceProvider` with the name of the generated service provider class.

### 2. Defining Routes

Open the `RouteServiceProvider` located at `app/Providers/RouteServiceProvider.php`. In the `boot()` method, add the route definition for your generated module.

```php
/**
 * Define the routes for the application.
 *
 * @return void
 */
public function boot()
{
    // Other route mappings...

    // Define routes for your generated module
    Route::middleware('api')
        ->prefix('api')
        ->group(base_path('path/to/your/module/routes.php'));
}
```

Replace `'path/to/your/module/routes.php'` with the actual path to the generated module's route file. Adjust the middleware and prefix as needed for your module.

Once you have completed these steps, your generated module's routes should be integrated with your Laravel application and its routes and services should be available for use.  Happy Coding!

### Using the Module Generator in an Existing Project


If you want to use the module generator in an existing project, follow these steps:

1. Copy the `CodeStubs` folder to the root directory of your project.
2. Copy the `app/Console/Commands/ModuleGenerator.php` file to the `app/Console/Commands` directory of your Laravel project.
3. Copy the `app/Helpers/PaginationHelper.php` file to the `app/Helpers` directory of your Laravel project.
4. Copy the `app/API/Common` folder contents to the `app/API/Common` directory of your Laravel project.
5. You may need to register the `ModuleGenerator` command in your Laravel application. If it's not already registered, add it to the `$commands` array in the `app/Console/Kernel.php` file:

   ```php
   protected $commands = [
       // Other commands...
       \App\Console\Commands\ModuleGenerator::class,
   ];
   ```
### Generating Commands

Once you have completed the steps to integrate the module generator into your existing Laravel project, you can use the following command to generate modules:

```bash
php artisan app:module {namespace}
```
    
### 3. Roadmap
## Module Generator for Laravel

#### Current Features (Implemented):
- [x] **CRUD Module API Generation**: Generate CRUD modules for Laravel applications, including a working resource CRUD API for single entities with pagination and search functionality.
- [x] **Folder Structure**: Generate a predefined folder structure for the module.
- [x] **PSR-4 Namespace**: Follow PSR-4 standard for generating namespaces.
- [x] **Template Generation**: Generate stub files for controllers, models, routes, etc., based on predefined templates.

### Note
- A sample entity (e.g., Products module) is generated with this project to demonstrate usage. Feel free to explore and learn from this sample. You can delete it when it's no longer needed.

### Simple Login Functionality
- This project includes a simple login functionality using Laravel Passport. Please note that the provided login functionality is not intended for production use and may lack robust security features. Refer to Laravel Passport documentation for more details on configuration and usage.

### Security Considerations
- This module generator is primarily intended for learning and inspiration purposes. It has not been thoroughly tested with security considerations in mind. Before deploying generated modules to production environments, ensure to conduct thorough security testing and implement appropriate security measures to safeguard your application.

### Roadmap (Planned Features) 

- [ ] **Customizable Templates**: Allow users to define custom templates for generated files.
- [ ] **Interactive CLI**: Implement an interactive command-line interface to guide users through generating CRUD modules.
- [ ] **DTO Generation**: Automatically generate Data Transfer Objects (DTOs) for entities, enhancing data handling and validation.
- [ ] **Automatic Field Validation**: Implement automatic field validation based on database schema or migrations, reducing manual validation effort.
- [ ] **Relationships**: Support defining relationships between modules/entities.
- [ ] **Testing Support**: Include support for generating test files (e.g., PHPUnit tests) along with CRUD modules.
- [ ] **Localization Support**: Add support for generating multilingual CRUD modules.
- [ ] **Version Control Integration**: Integrate with version control systems (e.g., Git) to automatically commit generated files.
- [ ] **Documentation Generation**: Automatically generate documentation for the generated modules.

### License
- This project is licensed under the MIT License.

### Disclaimer
- This project is provided as free and open-source software without warranty of any kind, expressed or implied. Use it at your own risk.

---

Feel free to let me know if there are any further improvements you'd like to make!
By incorporating these features, we aim to create a versatile and powerful module generator that meets the needs of a wide range of projects and developers. Happy Coding!
