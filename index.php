<?php
require_once(dirname(__FILE__) . "/includes/common/only_allow_logout.php");
require_once(dirname(__FILE__) . "/includes/common/defaults.php");
$PAGE["title"] .= " : Homepage";
require_once(dirname(__FILE__) . "/templates/common/header.php");
require_once(dirname(__FILE__) . "/includes/common/choose_navbar.php");



//load statistics
require_once(dirname(__FILE__) . "/classes/User.php");
require_once(dirname(__FILE__) . "/classes/TodoList.php");
require_once(dirname(__FILE__) . "/classes/Item.php");
require_once(dirname(__FILE__) . "/classes/Project.php");
require_once(dirname(__FILE__) . "/classes/Member.php");

$statistics = array(
	"Users" => User::countAll(),
	"Todo Lists" => TodoList::countAll(),
	"Items" => Item::countAll(),
	"Projects" => Project::countAll(),
	"Members of Projects" => Member::countAll()
);
?>

<div class="container">
	<h1 class="center strong">Welcome to 4Me2DO</h1>
	<img class="center mainLogo" src="public/images/logo_ltw.png">
	<h3 class="center">The ultimate todo list management tool!</h3>

	<h2 class="center strong">Statistics</h2>
	<div class="statistics">
		<?php foreach ($statistics as $name => $value) : ?>
		<ul class="statistics list">
			<li class="statistics value"><?= $value ?></li>
			<li class="statistics name"><?= $name ?></li>
		</ul>
		<?php endforeach ?>
	</div>
	<hr/>
	<h2 class="center strong">Here is a dump from our database, using QueryBuilder. We advise you to use <a class="primaryLink" href="https://xdebug.org/">XDebug</a> to see prretty dumps!</h2>
	<?php
		$query = new QueryBuilder(User::class);
		var_dump($query->select()->getAll());
	?>
</div>

<?php require_once(dirname(__FILE__) . "/templates/common/footer.php"); ?>