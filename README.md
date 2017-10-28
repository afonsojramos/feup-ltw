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


# UML (napknin version)

<p align="center">
	<img src="https://github.com/msramalho/feup-ltw/blob/master/public/images/other/uml_basic.png"/>
</p>

# Code practices
 * All the content should be in english;
 * Use **camelCase** in variables and database fields;
 * Classes names start with **C**apital letter;
 * Classes file names start with **C**apital letter, example: `User.php`;
 * pages file names are _underscore_ separared, example: `produt_edit_members.php`;
 * Use [EditorConfig](https://marketplace.visualstudio.com/items?itemName=EditorConfig.EditorConfig);
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
This operations only construct bits of the query, to run the generated query do `.get($parameters)` or `.getAll($parameters)`

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

**UPDATE** - call the `.update($what, $where)` function, both parameters are optional. If `$what` is not given, then all the coumns that have changed since the last `INSERT` or `UPDATE` are updated in the database. If `$where` is not given and the `.where($where)` function has not been called before then it is called for the default primary keys. 
