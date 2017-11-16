<?php
	require_once(dirname(__FILE__)."/includes/common/only_allow_logout.php");
	require_once(dirname(__FILE__)."/includes/common/defaults.php");
	$PAGE["title"] .= " : Homepage";
	//$PAGE["styles"][] = "index.css";//add a css file at the end
	//$PAGE["scripts"] = array("index.js");//add the scripts needed
	require_once(dirname(__FILE__)."/templates/common/header.php");
	require_once(dirname(__FILE__)."/includes/common/choose_navbar.php");
?>

<div class="container">
	<h1>Hello World</h1>
	<h3>Maybe some statistics about this application...</h3>
	<?php
		require_once(dirname(__FILE__)."/classes/User.php");
		$query = new QueryBuilder(User::class);
		var_dump($query->select()->getAll());
	?>
</div>

<?php require_once(dirname(__FILE__)."/templates/common/footer.php"); ?>