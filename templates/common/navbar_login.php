<nav class="navbar">
	<div class="container">
		<?php if ($PAGE["showSideBar"]): ?>
			<a class="navLeft" onclick="toggleSideBar()"><i class="material-icons">menu</i></a>
		<?php endif ?>
		<h1 class="logo"><a href="index.php"><i class="material-icons md-36">dashboard</i> 4Me2Do</a></h1>
		<div class="nav search">
			<i class="material-icons search">search</i>
			<input type="text" name="search" id="search" placeholder="search">
			<i class="material-icons close">close</i>
		</div>
		<ul class="nav navRight">
			<li><a href="edit_profile.php"><i class="material-icons">account_circle</i> Edit Profile</a></li>
			<li><a href="actions/logout.php"><i class="material-icons">exit_to_app</i> Logout</a></li>
		</ul>
	</div>
</nav>
<span class="afterNav"></span>