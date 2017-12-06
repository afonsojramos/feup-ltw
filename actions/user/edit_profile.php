<?php

/**
 * This action file treats 2 kinds of requests:
 * 	- chnage of user name and/or email.
 *  - change of password.
 *
 * These are two separate actions, and cannot be done concurrently.
 *
 */

require_once(dirname(__FILE__) . "/../../includes/common/only_allow_login.php");
verifyCSRF();

require_once(dirname(__FILE__) . "/../../includes/common/check_request.php");
require_once(dirname(__FILE__) . "/../../classes/User.php");

$result = array("success" => false);

if (isset($_POST['pwd1'])) {
	verifyAttributes($_POST, ["pwd1", "pwd2"]);
	/**
	 * This is a password change. Let's treat it acordingly.
	 */

	$user = new User();
	if (!$user->load($_SESSION['userId'])) { //load user
		$result["errors"] = array("username no longer exists");
	}

	if ($_POST['pwd1'] !== $_POST['pwd2']) {
		$result["errors"] = array("passwords don't match");
	} else {
		$user->password = $_POST['pwd1'];
	}

	if ($user->validate()) {
		$user->hashPassword();
		if ($user->clear()->update() == 1) {
			$result["success"] = true;
		}
	} else {
		$result["errors"] = $user->errors;
	}

} elseif (isset($_POST["username"])) {
	/**
	 * This is a username/email change.
	 */
	verifyAttributes($_POST, ["username", "email"]);

	$user = new User();
	if (!$user->load($_SESSION['userId'])) { //load user
		$result["errors"] = array("username no longer exists");
	}

	$user->username = $_POST['username'];
	$user->email = $_POST['email'];

	if ($user->validate()) {
		if ($user->duplicateUsernameOnEditProfile()) {
			$result["errors"] = array("username already exists");
		} elseif ($user->duplicateEmailOnEditProfile()) {
			$result["errors"] = array("email already exists");
		} elseif ($user->clear()->update() == 1) {
			$result["success"] = true;
		}
	} else {
		$result["errors"] = $user->errors;
	}
} else {
    // Undefined | Multiple Files | $_FILES Corruption Attack, If this request falls under any of them, treat it invalid.
	if (!isset($_FILES['profile']['error']) || is_array($_FILES['profile']['error'])) {
		$result["errors"] = array("Invalid file parameters");
	}

    // Check $_FILES['profile']['error'] value.
	switch ($_FILES['profile']['error']) {
		case UPLOAD_ERR_OK:
			break;
		case UPLOAD_ERR_NO_FILE:
			echo json_encode(array("No file received"));
			die();
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			echo json_encode(array("Exceeded filesize limit"));
			die();
		default:
			echo json_encode(array("Unknown error occurred"));
			die();
	}

    // Doublecheck filesize here.
	if ($_FILES['profile']['size'] > 1000000) {
		echo json_encode(array("Exceeded filesize limit"));
		die();
	}

    // Check MIME Type, $_FILES['profile']['mime'] can be misleeading
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	if (false === $ext = array_search(
		$finfo->file($_FILES['profile']['tmp_name']),
		array(
			'jpg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif',
		),
		true
	)) {
		echo json_encode(array("Invalid file format"));
		die();
	}

	//create thumbnail
	generateThumbnail($_FILES['profile']['tmp_name'], "../../public/images/profile/thumb" . $_SESSION["userId"] . ".jpg", 100);

	//create larger picture
	generateThumbnail($_FILES['profile']['tmp_name'], "../../public/images/profile/" . $_SESSION["userId"] . ".jpg", 700, true);

	header("Location: ../../index.php");
}

echo json_encode($result);


//generate a thumbnail given a finalWidth
function generateThumbnail($src, $dest, $finalWidth, $orMax = false) {
	$what = getimagesize($src);

	//if the type is one of the allowed ones
	switch (strtolower($what['mime'])) {
		case 'image/png':
			$srcImage = imagecreatefrompng($src);
			break;
		case 'image/jpeg':
			$srcImage = imagecreatefromjpeg($src);
			break;
		case 'image/gif':
			$srcImage = imagecreatefromgif($src);
			break;
		default:
			echo json_encode(array("Invalid file format"));
			die();
	}
	$width = imagesx($srcImage);
	$height = imagesy($srcImage);
	if ($orMax && $width < $finalWidth) {//keep image size if it is not big enough
		$finalWidth = $width;
	}
	// find the "desired height" of this thumbnail, relative to the desired width
	$finalHeight = floor($height * ($finalWidth / $width));

	// create a new, "virtual" image
	$virtualImage = imagecreatetruecolor($finalWidth, $finalHeight);

	// copy source image at a resized size
	imagecopyresampled($virtualImage, $srcImage, 0, 0, 0, 0, $finalWidth, $finalHeight, $width, $height);

	// create the physical thumbnail image to its destination
	imagejpeg($virtualImage, $dest);
	return true;
}

