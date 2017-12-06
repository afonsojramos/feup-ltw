
<?php
require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
require_once(dirname(__FILE__) . "/../../classes/Project.php");

?>

<form class="modal" opener="openAddProject" id ="modalAddProject">
	<div class="errors"></div>
	<div class="modalContent cardForm grid">
		<div class="formHeader">
			<h3 class="formTitle">New Project</h3>
		</div>
		<div class="formBody">
			<div>
				<input type="text" name="title" placeholder="List title" required>
			</div>
			<div>
				<textarea name="description" placeholder="Project Description"></textarea>
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
			<input type="submit" value="Add">
		</footer>
	</div>
</form>