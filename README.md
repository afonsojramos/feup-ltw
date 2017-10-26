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

 
