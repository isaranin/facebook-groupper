<?

/* 
 * facebook-groupper
 * https://github.com/isaranin/facebook-groupper
 *  
 * Worker with facebook groups
 *  
 * repository		git@github.com:isaranin/facebook-groupper.git
 *  
 * author		Ivan Saranin
 * company		Saranin Co.
 * url			http://saranin.co
 * copyright		(c) 2016, Saranin Co.
 *  
 *  
 * created by Ivan Saranin <ivan@saranin.com>, on 22.03.2016, at 18:44:22
 */

/* 
 * Template login-success
 */
var_dump($tpl);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title lng-name="title">Connected to Facebook</title>
		
		<link href='tpl/css/login-success.css' rel='stylesheet' type='text/css'>
		<link href='tpl/bootstrap/css/bootstrap.min.css' rel='stylesheet' type='text/css'>
		<link href='tpl/bootstrap/css/bootstrap-theme.min.css' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div class="success-panel panel panel-default center-block">
			<? if (isset($tpl['fbUrl'])) { ?>
				<h2 class="title">Login Facebook</h2>
				<a class="btn btn-lg btn-primary btn-block" href="<?= $tpl['fbUrl']?>">Click me!</a>
			<? } ?>
			<h3>Current status:</h3>
			<div class="alert alert-warning" role="alert">
				<?= $tpl['error'] ?>
			</div>
			
		</div>
	</body>
</html>
