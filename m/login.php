<!DOCTYPE html> 
<html>
<head>
	<title>Worklog Mobile</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>
	<script src="js/jquery.crypt.js"></script>
	<script src="js/login.js"></script>
</head>

<body>
	<div data-role="page" id="login-page" data-theme="d">

	<div data-role="header">
		<h1>Bejelentkezés</h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="c">
		<label for="textinput-s">Felhasználónév:</label>
    	<input type="text" name="username" id="username" placeholder="Felhasználónév" value="" data-clear-btn="true">
    	<label for="textinput-s">Jelszó:</label>
    	<input type="password" name="password" id="password" placeholder="Jelszó" value="" data-clear-btn="true">
    	<a href="#" data-role="button" id="submit" >Szezám tárulj!</a>
	</div><!-- /content -->

	<div data-role="footer">
		<h4>Page Footer</h4>
	</div><!-- /footer -->
</div><!-- /page -->
</body>
</html>