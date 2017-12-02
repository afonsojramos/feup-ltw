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
    * :file_folder: **common** - Common includes
 * :open_file_folder: **templates** - folder that contains the `html` templates for the main pages to use
    * :file_folder: **common** - Common templates
    * :file_folder: **user** - User includes
    * :file_folder: **list** - List includes
    * :file_folder: **project** - Project includes

# Tasks
 - [ ] Define the pages to use:
 	* index.php - the landing page, with links to `login.php` and `register.php`
		* If no login -> display login and register forms with backgroun
		* If login -> redirect to `dashboard.php`
	* dashboard.php - main interface page that shows the projects, the todo lists cards and other utilities
	* register.php - display the register form in a single page
		* calls the `actions/register.php` with post values.
	* actions/register.php - receive a `POST` to try to register a user
		* redirect to `login.php` on success
		* redirect to `register.php` on failure, with the errors (as GET parameters or in a `$_SESSION` variable)
	* login.php - display the login form in a single page, can receive an `email` as GET and fills it automatically, redirects to `actions/login.php`
	* actions/login.php - receives a `POST` and tries to login a user
		* return JSON response with boolean success and optional errors array
	* actions/logout.php -  log out the user and redirect to `index.php`
	* edit_profile.php - accessible only for logged users, displays a form to edit the user profile, calls `actions/edit_profile.php`
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
 * Use **camelCase** in variables, database tables and database fields;
 * Classes names start with **C**apital letter;
 * Classes file names start with **C**apital letter, example: `User.php`;
 * Pages file names are _underscore_ separared, example: `produt_edit_members.php`;
 * Pages must have specific titles assigned, in the html title tag;
 * Use [EditorConfig](https://marketplace.visualstudio.com/items?itemName=EditorConfig.EditorConfig);
 * Always use `'use strict';` at the beginning of **javascript** files;
 * Use `defer` in script tags inside the html, at the head, like so: `<script = "script.js" defer></script>`, or `async` if necessary;
 * Use `dirname(__FILE__)` when including/requiring files, example in the classes folder files:

```php
require_once(dirname(__FILE__)."/connection.php");
```

# Features

<h2 align="center">Minimum</h2>

- [x] Register a new account
- [x] Login into an account
- [x] Logout of an account
- [x] Edit user profiles
- [x] List a user's todo lists
- [x] Create new todo list (that belongs to a user)
- [x] Add items to a todo list
- [x] Mark an item as complete
- [x] Delete a todo list

<h2 align="center">Extra</h2>

- [ ] Share a link to a todo list
- [ ] Create a Project
- [ ] Add users to Project
- [ ] Change a List's color
- [ ] Edit tags
- [ ] Allow items to be assigned to user (in project)
- [ ] Create a new todo list (in a project)
- [ ] Choose a profile picture


# Potential Extra
 * Embed todo list in other websites
 * RSS Feed
 * User can share
 * `.htaccess` to use REST-like paths
