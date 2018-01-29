# feup-ltw - Group 1

Univeristy Subject - Web Technologies Laboratory (Laboratório de Tecnologias Web)

[Faculty of Engeneering of University of Porto](https://sigarra.up.pt/feup/en/WEB_PAGE.INICIAL)

2017/2018

With the colaboration of [Afonso Jorge Ramos](https://github.com/AJRamos308) and [Daniel Silva](https://github.com/Dannyps)

**A TODO list management platform:**

Official [instructions](https://web.fe.up.pt/~arestivo/page/courses/2017/ltw/project/).
### Full Reference in the [:book: WIKI](https://github.com/msramalho/feup-ltw/wiki)

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
    * :file_folder: **dashboard** - Dasboard templates

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

- [x] Create a Project
- [x] Manage Projects
- [x] Manage Project's Members
- [x] Create a new todo list (in a project)
- [x] Share a link to a Todo List
- [x] Share a links in Projects
- [x] List's Tags and editing
- [x] Flexible search
- [x] Bookmarkable searches
- [x] Choose a profile picture
- [x] Creation of thumbnails and full-sized photos
- [x] Change a List's color
- [x] Choose a Project's color

Many more

<h2 align="center">Security Functionalities</h2>

- [x] Hashed passwords
- [x] Authentication only pages
- [x] Authentication AND permission only pages
- [x] CSRF tokens on forms
- [x] CSRF tokens on ajax
- [x] XSS prevention

<h2 align="center">Keyboard Shortcuts</h2>

- [x] Ctrl+F opens Search
- [x] Ctrl+S opens Sidebar
- [x] Ctrl+P opens a Project's Modal
- [x] Ctrl+L opens a New List's Modal
- [x] Esc clears the search, closes modals, and cancels the text edits
