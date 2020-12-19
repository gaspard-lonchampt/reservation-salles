
<?php
if (isset($_POST['register'])) {
   if (isset($_POST['titre']) AND isset($_POST['description']) AND isset($_POST['date_start']) AND isset($_POST['date_end'])) {

      $titre = htmlspecialchars($_POST['titre']);
      $description = htmlspecialchars($_POST['description']);
      $date_start = htmlspecialchars($_POST['date_start']);
      $date_end = htmlspecialchars($_POST['date_end']);
   } 
}
?>



<div class="container d-flex flex-column">
	<form action="../pages/reservation-form.php" method="post" class="d-flex flex-column">
      <input class="text" type="text" name="titre" placeholder="Titre" required="">
      <input class="text" type="textarea" name="description" placeholder="Description" required="">
      <input class="text" type="datetime-local" name="date_start" placeholder="Date de début" required="">
      <input class="text" type="datetime-local" name="date_end" placeholder="Date de fin" required="">
<?php 
if(isset($error)){
   if($error == TRUE) {
      echo '<span> <h5 class="text-danger text-center">'.$errorMsg.'</h5> </span>'; 
   }
}
?> 

		<input type="submit" name="register" value="Ajouter un événement">
	</form>
	<p>Déjà un compte ? <a href="../pages/connexion.php"> Connectez-vous !</a></p>
</div>