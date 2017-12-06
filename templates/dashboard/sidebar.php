<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/../../classes/Project.php");

$projects = Project::getAllByUser($_SESSION["userId"]);

require_once(dirname(__FILE__) . "/modal_add_project.php");
?>

<div id="mySidenav" class="sidenav">
	<a class="closebtn" onclick="toggleSideBar()">&times;</a>
	<ul class="sidebar">
		<li class="dropdown">
			<a href="dashboard.php?filter=mine"><i class="material-icons">lock_outline</i> My Lists</a>
			<a href="dashboard.php?filter=all"><i class="material-icons">lock_open</i> All Lists</a>
			<a href="dashboard.php?filter=archived"><i class="material-icons archived">archive</i> Archived</a>
			<hr/>
		</li>
		<?php foreach ($projects as $project) : ?>
			<li><a href="dashboard.php?projectId=<?= $project->projectId ?>"><?= $project->title ?></a> <a class="floatRight" href="project.php?projectId=<?= $project->projectId ?>"><i class="material-icons">edit</i></a></li>
		<?php endforeach ?>
		<li>
			<hr/>
			<a href="#" id="openAddProject"><i class="material-icons">add</i> New Project</a>
		</li>
	</ul>
</div>