<div class="worklog-container">
<img src="images/see_learn_develop.png" style="float:right; margin-top: 10px; margin-right: 20px;">
<h4>Belépés</h4>
<?
	if (!isset($_POST['entername']) && $_SESSION['loggedin_worklog']=="false") { 
	// a bejelentkező doboz
		echo"<form action=\"index.php\" method=\"post\" name=\"loginform\" ><table width=130 cellpadding=\"5\" cellspacing=\"0\" border=\"0\" ><tr><td>Név:</td><td><input type=\"text\" name=\"entername\" value=\"\"  maxlength=\"255\" class=\"loginbox\"></td></tr><tr><td>Jelszó:</td><td><input type=\"password\" name=\"enterpassword\" value=\"\" class=\"loginbox\" maxlength=\"255\"></td></tr><tr><td>&nbsp;</td><td align=left><input type=\"submit\" name=\"save\" value=\"Belép\" class=\"btn btn-primary\" > </td></tr></table></form>";
	}

	if (isset($_POST['entername']) && $_SESSION['loggedin_worklog']=="false") { 
	// hibás jelszó, vagy felhasználónév esetén
		echo"<form action=\"index.php\" method=\"post\" width=130 name=\"loginform\" ><table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" ><tr><td>Név:</td><td><input type=\"text\" name=\"entername\" value=\"\"  maxlength=\"255\" class=\"loginbox\"></td></tr><tr><td>Jelszó:</td><td><input type=\"password\" name=\"enterpassword\" value=\"\" class=\"loginbox\" maxlength=\"255\"></td></tr><tr><td>&nbsp;</td><td align=left><input type=\"submit\" name=\"save\" value=\"Belép\" class=\"btn btn-primary\"> </td></tr><tr><td colspan=2 align=center><b><font color=red>Hibás jelszó <br>vagy felhasználónév!</font></b></td></tr></table></form>"; 
	} 


?>
</div>