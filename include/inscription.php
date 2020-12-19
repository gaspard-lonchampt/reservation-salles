<!-- page revue -->
<?php
if (isset($_POST['register'])) {
   if (isset($_POST['username']) AND isset($_POST['password']) AND isset($_POST['cpassword'])) {

      $login = htmlspecialchars($_POST['username']);
      $password = htmlspecialchars($_POST['password']);
      $cpassword = htmlspecialchars($_POST['cpassword']);
      $user = new user("$login", "$password", "$cpassword");
      $user_register = $user->register();
   } 
}
?>

<div class="container d-flex flex-column">
	<form action="../pages/inscription.php" method="post" class="d-flex flex-column">
      <input class="text" type="text" name="username" placeholder="Identifiant" required="">
      <input class="text" type="password" name="password" placeholder="Mot de passe" required="">
      <input class="text" type="password" name="cpassword" placeholder="Confirmation du mot de passe" required="">

<?php 
if(isset($error)){
   if($error == TRUE) {
      echo '<span> <h5 class="text-danger text-center">'.$errorMsg.'</h5> </span>'; 
   }
}
?> 

		<input type="submit" name="register" value="Inscription">
	</form>
	<p>Déjà un compte ? <a href="../pages/connexion.php"> Connectez-vous !</a></p>
</div>
