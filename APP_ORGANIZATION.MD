### BOOK LIBRARY SYSTEM - ORGANIZATION
---
![Local Image](./src/storage/documentation/app_organization%20structure.png)

1. App Organization
- This app is organized with extended MVC architecture
- The architecture places `Repository` and `Service` layers between `Models` and `Controllers` to ensure separation of concerns

2. Repository
- Repository contains all necessary SQL logic for an included model
- Every Repository need to contain one model included as `protected const MODEL`
- Every repository need to include `trait` with `default CRUD methods` and implemented `Repository Interface` for binding in `ServiceContainer`
- If you need more complex SQL logic for some methods, repository is the right place for that
3. Service
- Service contains all programming logic except SQL logic
- Every Service extend `BaseService` abstract class which save one instance of `Repository` dynamically added via `RepositoryInterface`
  - For better organization every service implement `ServiceInterface` for binding in `Service Container`
  - In every service is included default `trait` with CRUD methods
  - Every service need to have two resources defined with constants `protected const INDEX_RESOURCE` and `protected const DETAIL_RESOURCE`
  - If you need different logic, you can override them or create a new method in service
4. Controller
    - Every controller extend `Controller` abstract class which include two instances:
        - Service Instance
        - Request Instance
5. config/app_configuration
    - In this class you need to register all created `MRSC` `(Model-Repository-Service-Controller)` classes
        - eg. `Controller::class => [
              'service' => Service::class,
              'repository' => Repository1::class,
              'request' => RequestCustom::class
            ]`

6. Routing
    - In application, we use `multiple api files` approach for better organization
        - When you create a new `MRSC` combination you need to create a new `route_example.php` file inside `api` directory
