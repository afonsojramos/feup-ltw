<!DOCTYPE html>
<html lang="<?= $PAGE["lang"] ?>">
<head>
	<title><?= $PAGE["title"] ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="public/images/favicon.ico" type="image/x-icon" />
	<?php foreach ($PAGE["styles"] as $style) : ?>
		<link href="<?= $PAGE["cssFolder"] . $style ?>" rel="stylesheet">
	<?php endforeach; ?>

	<?php foreach ($PAGE["scripts"] as $scriptName) : ?>
		<script src="<?= $PAGE["jsFolder"] . $scriptName ?>" defer></script>
	<?php endforeach; ?>

</head>
<body<?php
	if (count($PAGE["bodyClasses"]) != 0) {
		echo ' class = "';
		foreach ($PAGE["bodyClasses"] as $c) {
			echo  htmlentities($c);
		}
		echo '"';
	}
	if ($PAGE["includeCSRF"])
		echo " data-csrf='" . $_SESSION["csrf"] . "'";
?>
>
