# feup-ltw - Group 1
LTW Project - A TODO list management platform:

Official [instructions](https://web.fe.up.pt/~arestivo/page/courses/2017/ltw/project/).

# Database

To initialise the database, go to the **database** folder and run: `sqlite3 -init todo.sql todo.db`

# Project Organization

:open_file_folder: **root** - Contains the folder structure and the files that represent the web pages
 * :file_folder: **classes** - `php` files that describe the layout and behaviour of the classes
 * :file_folder: **database** - files related to the database (`todo.sql`, `todo.db`, `connection`)
 * :open_file_folder: **public** - only the files that anyone should be able to access
    * :file_folder: **css** - CSS files
    * :file_folder: **js** - javascript files (can have more subfolders)
    * :open_file_folder: **images** - Images: profile pictures and other media
      * :file_folder: **profile** - User's profile pictures
      * :file_folder: **other** - Any other images
 * :file_folder: **actions** - php files that receive post requests and redirect
 * :file_folder: **includes** - contains files that are reused across other `.php` files
 * :open_file_folder: **templates** - folder that contains the `html` templates for the main pages to use
    * :file_folder: **common** - Common includes
    * :file_folder: **user** - User includes
    * :file_folder: **list** - List includes
    * :file_folder: **project** - Project includes

# Tasks
 - [ ] Define the pages to use:
 	* index.php - the landing page, with links to `login.php` and `register.php`
		* If no login -> display an image, and some text
		* If login -> redirect to `dashboard.php`
	* dashboard.php - man interface page tha shows the projects, the todo lists cards and other utilities
	* register.php - display the register form in a single page
		* calls the `actions/register.php` with post values.
	* actions/register.php - receive a `POST` to try to register a user
		* redirect to `login.php` on success
		* redirect to `register.php` on failure, with the errors (as GET parameters or in a `$_SESSION` variable)
	* login.php - display the login form in a single page, can receive an `email` as GET and fills it automatically, redirects to `actions/login.php`
	* actions/login.php - receives a `POST` and tries to login a user
		* redirect to `dashboard.php` on success
		* redirect to `login.php` on failure, with the errors
	* actions/logout.php -  log out the user and redirect to `login.php`
	* edit_profile.php - accessible only for looged users, displays a form to edit the user profile, calls `actions/edit_profile.php`
	* actions/edit_profile.php - receives a `POST` request to edit a user's profile
		* redirect to ?????.php on success
		* redirect to `edit_profile.php` on failure, with the errors
	* user.php?id={userId} - accessible only for logged users, displays a user profile that is "readonly"
	* 
 - [ ] Implement the default desgin (css)


# UML (napknin version)

<p align="center">
	<img src="https://github.com/msramalho/feup-ltw/blob/master/public/images/other/uml_basic.png"/>
</p>

# Code practices
 * All the content should be in english;
 * Use **camelCase** in variables and database fields;
 * Classes names start with **C**apital letter;
 * Classes file names start with **C**apital letter, example: `User.php`;
 * Pages file names are _underscore_ separared, example: `produt_edit_members.php`;
 * Pages must have specific titles assigned, in the html title tag;
 * Use [EditorConfig](https://marketplace.visualstudio.com/items?itemName=EditorConfig.EditorConfig);
 * Always use `'use strict';` at the beginning of all **javascript** files;
 * Use `defer` in script tags inside the html, at the head, like so: `<script = "script.js" defer></script>`, or `async` if necessary;
 * Use `dirname(__FILE__)` when including/requiring files, example in the classes folder files:

```php
require_once(dirname(__FILE__)."../connection.php");
```

# Features

<h2 align="center">Minimum</h2>

- [ ] Register a new account
- [ ] Login into an account
- [ ] Logout of an account
- [ ] Edit user profiles
- [ ] List a user's todo lists
- [ ] Create new todo list (that belongs to a user)
- [ ] Add items to a todo list
- [ ] Mark an item as complete
- [ ] Delete a todo list

<h2 align="center">Extra</h2>

- [ ] Share a link to a todo list
- [ ] Create a Project
- [ ] Add users to Project
- [ ] Change a List's color
- [ ] Edit tags
- [ ] Allow items to be assigned to user (in project)
- [ ] Create a new todo list (in a project)
- [ ] Escolher foto de perfil


# Potential Extra
 * Embed todo list in other websites
 * RSS Feed
 * User can share
 * `.htaccess` to use REST-like paths

 # [QueryBuilder.php](https://github.com/msramalho/feup-ltw/blob/master/classes/QueryBuilder.php)
 A class to isolate all the sql operations and query management.

## Extending QueryBuilder

 How to use it:
 1. Database class should extend it: `class User extends QueryBuilder{...};`;
 2. The primary key of the class should be the first property of the class;
 3. If more than one primary key exists they can be specified by:
 	1. Declaring a `static` property named `$primaryKeys`, like so:
	``` php
	public static $primaryKeys = array("id1", "id2");
	```
	2. Using the `.setKey()` method and passing it an array with the names of the parameters
4. `public` properties of the child class are not "whatched" for changes, so if you want to call `update` to write the changes on the object to the database in the following way:
```php
$user->name = "New Name";
$user->update();
```
You need to declare `name` as `protected`: `protected $name;`;

5. If the table name is not the same as the class name, but with the first letter in lowercase and with an `s` at the end, then you should set it using `.setTable()`, and give it the table name (or an array of table names, if that is the case);
6. Do `parent::__construct();` inside the new class's constructor;

## Using QueryBuilder directly
1. Create a QueryBuilder instance and pass it a Class that defines the database table: `$query = new QueryBuilder(User::class, $keys);`;
2. The second parameter of the constructor is optional and will be the used to call `.setKey($keys)`;
3. Now you can use `$query to build and execute queries`.

## QueryBuilder Methods
This is a list of the methods and actions that yo can invoke on a QueryBuilder instance or on a Class that inherits from QueryBuilder:
### Creating Queries
These operations only construct bits of the query, to run the generated query do `.get($parameters)` or `.getAll($parameters)`

Notice that, for every new parameter you add that is not defined for that class, it must be given when the query executes `get($missingParameters)`. You can also call `.addParam($key, $value)` or `.addParams($arrayOfKeyValues)`.

#### 1. `select($what)`
 * Choose `$what`(string) you want to select;
 * _Default_ is `"*"`;
 * _Returns_ reference to the object(`$this`) .
#### 2. `where($where)`
 * If this function is not called, no `WHERE` condition is added unless it is later required in the query, like `DELETE`;
 * If a string is passed as a parameter that string will be used as the where condition, it is added after "WHERE ";
 * If it is `true` then the default key values will be used;
 * If it is called with no parameters (or none of the above) then the `WHERE` condition is removed;
 * _Returns_ reference to the object(`$this`) .
#### 3. `orderBy($order)`
 * Adds `ORDER BY` clause to query;
 * No parameters -> removes `ORDER BY` clause;
 * String parameter should decide the new order: ex: "dateUpdated DESC, score ASC".
 * _Returns_ reference to the object(`$this`) .
#### 4. `limit($limit)`
 * Adds `LIMIT` clause to query;
 * No parameters -> removes `LIMIT` clause;
 * Numeric parameter should specify the limit;
 * _Returns_ reference to the object(`$this`) .
#### 5. `offset($offset)`
 * Adds `OFFSET` clause to query;
 * No parameters -> removes `OFFSET` clause;
 * Numeric parameter should specify the offset;
 * This clause **requires** a `LIMIT` clause, so be sure to set it before executing the query;
 * _Returns_ reference to the object(`$this`) .
#### 6. `clear()`
 * Erases the result of all the query changes calls made preivously;
 * Empties the custom parameters added, through `addParam` and `addParams`;
 * _Returns_ reference to the object(`$this`) .

### Executing Queries

**SELECT** - If you want to do a `SELECT` query you can define it's structure using the functions above. Afterwards, you would call `.get()` to fetch one row from the database or `.getAll()` to fetch all of the selected rows.

**UPDATE** - To execute an `UPATE` query, call the `.update($what, $where)` function, both parameters are optional. If `$what` is not given, then all the columns that have been changed since the last `INSERT` or `UPDATE` are updated in the database. If `$where` is not given and the `.where($where)` function has not been called before then it is called for the default primary keys.

**INSERT** - To execute an `INSERT` query, call the `.insert($autoIncrement = true, $columnsToInsert = null)` function, both parameters are optional. If `$autoIncrement` is `true`  **and** there is only a single primary key (which makes sense) then the the id of the variable will be automatically loaded . If `$columnsToInsert` is not given then all the columns that are not in the primary keys will be inserted, if you only wish to insert some of those properties pass an array with the names of the columns to insert.

**LOAD** - To get a row from the database and loaded it into the variable, use the function `load($ids = null)`. This function loads an object into this instance **or** return a new instance of the class if QueryBuilder is used directly (not through inheritance). If no `$ids` are given then it uses the default keys, if a number is given and there is only one primary key that row is loaded, if an array of `$key=>$value` is used then the load uses this `$keys` as the primary keys and `$values` as its values, if an array of `$values` is given and it matches the length of the primary keys, then these values are used to load the object properties from the database.


### More functionality
Aditionally you can invoke the following functions:
 * `.clear()` - resets all the changes made after the constructor is called to the dynamic queries;
 * `.setKey($keys = null)` - Used when the fisrt parameter of the class is not the primary key. Receives an array of strings (the key names), or a single string with the id of the class, or even a number `n` that means that the first nth properties of the class constitute the primary key;
 * `.setTable($table = "")` - If a string is passed that is the new table name, else if no parameter is passed derive the name from the class name, or if an array of strings is multiple tables are used;
 * `.addParams($parameters)` - Adds one or more parameters to `QueryBuilder::parameters` (not static), so they are used when binding key values in the query execution. Receives an array of `$key=>$value`;
 * `.addParam($param, $value)` - Adds a parameter to the `QueryBuilder::parameters` (not static).
