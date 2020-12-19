<?php
if (isset($_POST['connexion'])) {
   if (isset($_POST['username']) AND isset($_POST['password'])) {

      $login = htmlspecialchars($_POST['username']);
      $password = htmlspecialchars($_POST['password']);
      $user = new user("$login", "$password", NULL);
	  $user_connect = $user->connect(); 
	  $_SESSION['user'] = $user_connect;
	  echo "utilisateur connecté";	  
   } 
}
echo "<pre>";
var_dump($user_connect);
echo "</pre>";

echo "<pre>";
var_dump($_SESSION['user']);
echo "</pre>";
?>

	<!-- main -->
<div class="container">
	<form action="../pages/connexion.php" method="post">
    	<input class="text" type="text" name="username" placeholder="Identifiant" required="">
		<input class="text" type="password" name="password" placeholder="Mot de passe" required="">
    	<label class="anim">
			<input type="checkbox" class="checkbox" required="">
			<span> &nbsp; J'accèpte les conditions générales d'utilisation</span>
		</label>
			<?php 
        		if(isset($error) == TRUE) {
              	 echo '<span> <h5 class="text-danger text-center">'.$errorMsg.'</h5> </span>'; 
            	}
			?> 
		<input type="submit" name="connexion" value="Connexion">
	</form>
	<p>Pas encore de compte ? <a href="../pages/inscription.php"> Inscrivez-vous !</a></p>
</div>