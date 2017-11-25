<?php

require_once(dirname(__FILE__) . "/classes/TodoList.php");
//todo: receive a projectId (which can be 0), a search term, a boolean for archived, a boolean for shared, and return a list of TodoList with their TODO items. To be called by ajax or php