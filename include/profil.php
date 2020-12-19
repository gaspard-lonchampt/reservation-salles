<?php
if (isset($_POST['register'])) {
   if (isset($_POST['username']) AND isset($_POST['password'])) {

      $login = htmlspecialchars($_POST['username']);
      $password = htmlspecialchars($_POST['password']);
      $user = new user("$login", "$password", NULL);
	   $user_update = $user->profil(); 
	  	  
   } 

   var_dump($user_update);
}
?>

			<div class="">
				<form action="../pages/profil.php" method="post">
               <input class="text mb-5" type="text" name="username" value="<?php echo $_SESSION['user']['login'] ?>" >
               <label class="text-white" for="password">Entrer votre mot de passe</label>
					<input class="text w3lpass mt-1 mb-5" type="password" name="password" placeholder="Mot de passe">

               <?php 
                     if(isset($error) == TRUE) {
                     echo '<span> <h5 class="text-danger text-center">'.$errorMsg.'</h5> </span>'; 
                     }
                     elseif (isset($sucess) == TRUE) {
                      echo '<span> <h5 class="text-white text-center">'.$sucessMsg.'</h5> </span>';
                     }
                  
               ?> 

					<input type="submit" name="register" value="Modifier vos informations">
				</form>
			</div>
