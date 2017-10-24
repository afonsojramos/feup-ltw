# feup-ltw - Group 1
LTW Project - [Instructions](https://web.fe.up.pt/~arestivo/page/courses/2017/ltw/project/)

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
   
 
# Code practices
 * Use **camelCase** in variables and database fields;
 * Use [EditorConfig](https://marketplace.visualstudio.com/items?itemName=EditorConfig.EditorConfig);
 * Use `dirname(__FILE__)` when including/requiring files, example in the classes folder files:
  
```php
require_once(dirname(__FILE__)."../connection.php");
```
 
 
# Extra 
 * Embed todo list in other websites
 * RSS Feed
 * User can share
 * `.htaccess` to use REST-like paths

 
