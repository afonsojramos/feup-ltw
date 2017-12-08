<?php
require_once(dirname(__FILE__) . "/includes/common/only_allow_login.php");

require_once(dirname(__FILE__) . "/includes/common/check_request.php");
verifyAttributes($_GET, ["userId"]);

require_once(dirname(__FILE__) . "/classes/User.php");

//load the user
$user = new User();
if (!$user->load($_GET["userId"])) {
	echo "User not found";
	die();
}

//load page defaults
require_once(dirname(__FILE__) . "/includes/common/defaults.php");
$PAGE["title"] .= " : User " . htmlentities($user->username);
// $PAGE["styles"] = array_merge($PAGE["styles"], []]);
$PAGE["scripts"] = array_merge($PAGE["scripts"], array("search.js"));

require_once(dirname(__FILE__) . "/templates/common/header.php");
require_once(dirname(__FILE__) . "/includes/common/choose_navbar.php");

//load statistics
require_once(dirname(__FILE__) . "/classes/TodoList.php");
require_once(dirname(__FILE__) . "/classes/Project.php");
$projects = Project::getAllByUser($_SESSION["userId"]);
$todos = TodoList::getAllByUser($_SESSION["userId"]);
$countItems = 0;
$countIncompleteItems = 0;
$countArchived = 0;

foreach ($todos as $todo) {
	$countItems += count($todo->items);
	if ($todo->archived) $countArchived++;
	foreach ($todo->items as $item)
		if (!$item->completed)
		$countIncompleteItems++;
}

$statistics = array(
	"Projects" => count($projects),
	"Todo Lists" => count($todos),
	"Archived Todo Lists" => $countArchived,
	"Completed Items" => $countItems - $countIncompleteItems,
	"Incomplete Items" => $countIncompleteItems,
	"Total Items" => $countItems
);

?>
<div class="container" id="userMainContainer" data-userId="<?= $user->userId ?>">
	<h1 class="center">Username: <span class="strong"><?= htmlentities($user->username) ?></span></h1>

	<?php $base = "public/images/profile/";
		$filename = $base . $user->userId . ".jpg";
		if (!file_exists($filename)) $filename = $base . "default.png"
	?>
	<img alt="profile picture" class="center userImage" src="<?= $filename ?>"/>
	
	<div class="data">
	<h2 class="strong">Data</h2>
	<?php
		$data = array("userId", "username", "email");
		foreach ($data as $parameter) :
	?>
		<div class="largeFont">
			<?= $parameter ?>:
			<span class="strong"><?= htmlentities($user->$parameter) ?></span>
		</div>
	<?php endforeach ?>
	</div>

	<h2 class="center strong">Statistics</h2>
	<div class="statistics">
		<?php foreach ($statistics as $name => $value) : ?>
		<ul class="statistics list">
			<li class="statistics value"><?= $value ?></li>
			<li class="statistics name"><?= $name ?></li>
		</ul>
		<?php endforeach ?>
	</div>
</div>

<?php require_once(dirname(__FILE__) . "/templates/common/footer.php"); ?>