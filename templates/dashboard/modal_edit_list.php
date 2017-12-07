<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/../../classes/Project.php");

//if a get project id is set, that is the default one
if (isset($_GET['projectId'])) {
	$selectedProjectId = $_GET['projectId'];
}else{
	$selectedProjectId = 0;
}
//load projects if needed
if(!isset($projects) && !is_array($projects)){
	$projects = Project::getAllByUser($_SESSION["userId"]);
}
?>

<form class="modal" opener="openEditListModal" id ="modalEditList">
	<div class="errors"></div>
	<div class="modalContent cardForm grid">
		<div class="formHeader">
			<h3 class="formTitle">Edit Todo List</h3>
		</div>
		<div class="formBody">
			<div>
				<input type="text" name="title" id="title" placeholder="List title" required>
			</div>
			<div>
				<input type="text" name="tags" id="tags" placeholder="Tags (Comma separate)">
			</div>
			<div>
				<select name="colour">
					<option class="white" value="white">White</option>
					<option class="red" value="red">Red</option>
					<option class="orange" value="orange">Orange</option>
					<option class="yellow" value="yellow">Yellow</option>
					<option class="green" value="green">Green</option>
					<option class="teal" value="teal">Teal</option>
					<option class="blue" value="blue">Blue</option>
					<option class="purple" value="purple">Purple</option>
					<option class="pink" value="pink">Pink</option>
					<option class="brown" value="brown">Brown</option>
				</select>
			</div>
		</div>
		<footer class="formFooter">
			<input type="submit" value="Edit">
		</footer>
	</div>
</form>