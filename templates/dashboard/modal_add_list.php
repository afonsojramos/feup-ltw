
<form class="modal" opener="openAddListModal"id ="modalAddList">
	<div class="modalContent cardForm grid">
		<div class="formHeader">
			<h3 class="formTitle">New Todo List</h3>
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
					<option class="indigo" value="indigo">Dark Blue</option>
					<option class="purple" value="purple">Purple</option>
					<option class="pink" value="pink">Pink</option>
					<option class="brown" value="brown">Brown</option>
					<option class="grey" value="grey">Grey</option>
				</select>
			</div>
		</div>
		<footer class="formFooter">
			<input class="_closeModal" type="submit" value="Add">
		</footer>
	</div>
</form>
<button id="openAddListModal" class="fab fixed fabAccent">+</button>