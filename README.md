# Follow below steps

1) Run this in termianl
```sh
composer install
```

2) Fill your database credentials in config/database.json file.

```json
{
	"in_use" : "connection_1_psql",

	"connection_1_psql" : {
		"driver"   : "pdo_pgsql",
		"host"	   : "localhost",
		"port"     : 5432,
		"dbname"   : "your database name here",
		"user"     : "user name here",
		"password" : "your password here"
	}
}
```

3) Run this in terminal
```sh
vendor/bin/doctrine orm:schema-tool:create
```

4) Populate created tables (i.e. articles and categories with some data and relate them in articles_categories table)

#### Here is entities:
* [Article](https://github.com/s-ghazaryan/ubiquitous/blob/master/src/Database/Entities/Article.php)
* [Category](https://github.com/s-ghazaryan/ubiquitous/blob/master/src/Database/Entities/Category.php)

#### What kind of relation do this entities have to each other (annotation is defined in [Article Entity](https://github.com/s-ghazaryan/ubiquitous/blob/master/src/Database/Entities/Article.php))?
```php
/**
 * Many Articles have Many Categories.
 * @ManyToMany(targetEntity="Category", inversedBy="articles")
 * @JoinTable(name="articles_categories")
 */
```
#### How they are connected ?
As above annotation states they are connected with **associative** table!

### What is this api offering so far ?
[JSON:API](https://jsonapi.org/format/) **GET** functionalities.

* [Pagination](https://jsonapi.org/format/#fetching-pagination) with collections (obviously)
* [Compound Document](https://jsonapi.org/format/#fetching-includes)
* [Sorting](https://jsonapi.org/format/#fetching-sorting)

### Is it easy to add other functionalities (CRUD) too ?
- [x] For maintainability perspective **yes**, but it will take a little bit longer that static definition.
- [x] For other entities (tables) with and without relationships **yes**.

### Can this api be extensible ?
**YES**

### What can be wrong and why ?
Some functionalities can have errors, because of tight shchedule (regarding work) - SORRY in advance!

* Only 200 status code will be returned.
* Even single object placeholder (e.g. /categories/{id} - 
	if not integer then error will be thrown) is not validated for integer.
* Resource will have empty arrays for not used members json:api (v1.0).
* etc.