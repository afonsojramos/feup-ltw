
<div class="modal" opener="openAddListModal">
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
		</div>
		<footer class="formFooter">
			<input class="closeModal" type="submit" value="Add">
		</footer>
	</div>
</div>
<button id="openAddListModal" class="fab fixed fabAccent">+</button>