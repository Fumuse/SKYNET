<? if (!defined('P_ROOT'))
		die();
$dir = str_replace($_SERVER['DOCUMENT_ROOT'], '', P_ROOT);?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="<?=$dir?>/include/css/main.css?<?=filemtime(P_ROOT.'/include/css/main.css')?>" />
		<script src="<?=$dir?>/include/js/jquery-3.4.1.min.js" type="text/javascript"></script>
	</head>
	<body>
		<div id="workearea">