# Laravel Simple REST API for Wantlist

This is a project meant to learn how to create simple yet powerful and safe REST API in the Laravel framework. I will be listing the steps I did to create this project.

## Database
First I needed to generate data. I created and seeded a mysql database with 2 tables: Customer and Wantlist. The relation between them is that a customer has Many Wantlists. This is done by creating migrations with artisan:

`php artisan make:model Customer --all`

And then creating the schema in the migrations file. Next I defined what kind of values I want to generate in the CustomerFactory and WantlistFactory files using the faker module. Lastly you define how many table entries should get generated in the CustomerSeeder and called in the DatabaseSeeder. Lastly to run the migrations to the running mysql database I used: 

`php artisan migrate:fresh --seed`

## Controller, Resources and Filters

Next I had to create a few files for resource formatting and controlling. Thankfully all of these files were made by artisan when it made the models. What I did was move the controller and resource files two folders down to Api/V1 or just /V1, so that they are versioned. This must be done because users of api will want that their integration of the api doesn't break upon updating. For the Resources each data object has two files:

`CustomerCollection.php` and `CustomerResource.php`

The Resource file is used to define a single frame of how the data is outputted in a get request (which data we leave out or add) and a Collection calls this resource automatically by Laravel if it is called, in our case when it is returned in the index() function of the `CustomerController.php`.

---
I also added a few query filters that allow you to search for specific data. For now this comes in the form of querying as such:

`localhost:8000/api/v1/customer?country[eq]=Singapore`

The query is therefore in the form of `"ColumnName"["operator"]="value"`. The list of operators is:
- eq = equals
- lt = less than
- gt = greater than
- lte = less than equal
- gte = greater than equal
- neq = not equal
- like = LIKE
- notlike = NOT LIKE

Another query parameter you can use is `showWantlists=true`, which appends all the Customers wantlists.

---
### Request
At this point the API needed a way to POST, PUT, PATCH and DELETE data. This was done with communication from the Controllers with the Requests. In the Requests files we decide the format needed for the request body parameters. The controller class then accepts this format and adds, updates, and removes the new objects from the database. Post requests for <b>Customers</b> are at the route `localhost:8000/api/v1/customer` in the form of:

```
{
    "name":"yourname",
    "type":"S (solo) or G (group)",
    "email":"youremail",
    "city":"yourcity",
    "country":"yourcountry",
    "budget":number
}
```
And <b>Waitlists</b> at the route `localhost:8000/api/v1/waitlist` in the form of:
```
{
    "customerId":yourcustomerid,
    "price":number,
    "item":"youritemname",
    "status":"W (want) or B (bought)",
    "boughtDate":"date_format:Y-m-d H:i:s" || null,

}
```
You can also bulk add Waitlists in the Route `localhost:8000/api/v1/waitlist/multiple`

## Authentication and Policies
Lastly, at least some form of authentication was needed. This was done using <b>Laravel:Sanctum</b>. I used the built in User model and enabled the use of tokens. Then I made routes for /register /login and /logout in the base `localhost:8000/api/` route, where users can register with a post request in the form of a JSON body:

```
{
    "name":"yourname",
    "email":"youremail",
    "password":"yourpassword",
    "password_confirmation":"yourpassword"
}
```
You will be given back your token that is used for the authentication of non GET requests. 

The login route just includes the email and password inputs and outputs a new token. The logout route deletes existing tokens. Tokens are configured to delete themselves in 3 hours from creation.

---

Authentication is checked in the Controller classes by using the Gate facade and Policies. Policies are declared in the Policies files and connected in the AppServiceProvider. Upon a POST, PUT, PATCH, DELETE request a `Gate::Authorize("policy", arguments)` request is called to check if the user is authorized to do this command.

## Conclusion
This is all the work done for now. More functionality will be included over time, as this project is a project made for learning. To use the api use the following terminal commands. 

`composer install`
`npm install && npm build`
`php artisan key:generate` - generate key
`php artisan migrate:fresh --seed` - seed database

