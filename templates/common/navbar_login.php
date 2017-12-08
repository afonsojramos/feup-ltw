<nav class="navbar">
	<div class="container">
		<?php if ($PAGE["showSideBar"]) : ?>
			<a class="navLeft" onclick="toggleSideBar()"><i class="material-icons">menu</i></a>
		<?php endif ?>
		<h1 class="logo"><a href="index.php"><i class="material-icons md-36">dashboard</i> 4Me2Do</a></h1>
		<div class="nav search">
			<i class="material-icons search">search</i>
			<input type="text" name="search" id="search" placeholder="search">
		</div>
		<ul class="nav navRight">
			<li><a href="edit_profile.php">
				<?php
				$filename = "public/images/profile/thumb" . $_SESSION["userId"] . ".jpg";
				if (file_exists($filename)) :?>
					<img class="navPicture" src="<?= $filename ?>"/>
				<?php else: ?>
					<i class="material-icons">account_circle</i>
				<?php endif ?>
				<span class="nav navRight text">Edit Profile</a></span></li>
			<li><a href="actions/user/logout.php"><i class="material-icons">exit_to_app</i> <span class="nav navRight text">  Logout</a></span></li>
		</ul>
	</div>
</nav>
<span class="afterNav"></span>