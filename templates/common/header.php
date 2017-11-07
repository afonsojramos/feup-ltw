<!DOCTYPE html>
<html lang="<?= $PAGE["title"] ?>">
<head>
	<title><?= $PAGE["title"] ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php foreach ($PAGE["styles"] as $style) : ?>
		<link href="<?= $style ?>" rel="stylesheet">
	<?php endforeach; ?>

	<?php foreach ($PAGE["scripts"] as $scriptName) : ?>
		<script type="text/javascript" src="<?= $scriptName ?>" defer></script>
	<?php endforeach; ?>

</head>
<body>
