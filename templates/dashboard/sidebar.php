<?php
	require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
	require_once(dirname(__FILE__) . "/../../classes/Project.php");

	$projects = Project::getAllByUser($_SESSION["userId"]);
?>

<div id="mySidenav" class="sidenav">
	<a class="closebtn" onclick="toggleSideBar()">&times;</a>
	<ul class="sidebar">
		<li class="dropdown">
			<a href="dashboard.php?filter=mine" class="dropdown-toggle" data-toggle="dropdown"><i class="material-icons"  >lock_outline</i> My Lists</a>
			<a href="dashboard.php?filter=all" class="dropdown-toggle" data-toggle="dropdown"><i class="material-icons">lock_open</i> All Lists</a>
			<a href="dashboard.php?filter=archived" class="dropdown-toggle" data-toggle="dropdown"><i class="material-icons archived">archive</i> Archived</a>
		</li>
		<hr/>
		<?php foreach ($projects as $project): ?>
			<li><a href="dashboard.php?projectId=<?= $project->projectId ?>"><?= $project->title ?></a></li>
		<?php endforeach ?>
	</ul>
</div>