<?php 

class user {

  private $id;
  private $connect;
  private $db;
  public $login;
  public $password;
   
    public function __construct($login, $password, $cpassword = NULL) {
      $this->login = $login;
      $this->password = $password;
      $this->cpassword = $cpassword;
      $this->connect = "0";
      $this->db = $this->db_connexion();
    }
    
    public function db_connexion() {
      try {
          $db = new PDO("mysql:host=localhost;dbname=reservationsalles", 'root', 'root');
          $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $db;
      }
      
      catch (PDOException $e) {
          echo 'Echec de la connexion : ' . $e->getMessage();
      }
    }


    public function checklogin() {

      $requete_same_login = $this->db->prepare("SELECT * FROM utilisateurs WHERE login = ?");
      $requete_same_login->execute([$this->login]);
      $loginExist = $requete_same_login->fetch();
      return $loginExist;
    }
    
    public function register() {
      
      $checklogin = $this->checklogin();

      if ($checklogin == FALSE) {

        if (strlen($this->login) > 60) {
          echo "L'identifiant doit faire moins de 60 caractères";
        }

        elseif ($this->login == $this->password) {
          echo "L'identifiant et le mot de passe doivent être différents";
        }

        elseif ($this->password !== $this->cpassword) {
          echo "Le mot de passe et la confirmation sont différents";
        }

        elseif (!preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#", $this->password)) {
          echo "Le mot de passe doit contenir plus de 8 caractères, doit contenir une majuscule, une majuscule, un chiffre et un caractère spécial";
       }

        elseif ($this->password == $this->cpassword) {
          $hash = password_hash($this->password, PASSWORD_DEFAULT);
          $requete_register = $this->db->prepare("INSERT INTO utilisateurs (login,password) VALUES(:login,:password)");      
          $requete_register->execute([
                  'login' => $this->login,
                  'password' => $hash]);
          return [$this->login, $hash];  
        }
      }

      else {
        echo "identifiant déjà prit";
      }
    }

    public function connect() {
      $requete_connexion = $this->db->prepare("SELECT * FROM utilisateurs WHERE login = ?");
	    $requete_connexion->execute([$this->login]);
	    $user = $requete_connexion->fetch(); 

      if ($user AND password_verify($this->password, $user['password'])) {
        $this->id           = $user['id'];
        $this->login        = $user['login'];
        $this->password     = $user['password'];
        $this->connect      = "1";      
        return $tab = ['id' => $this->id, 'login' => $this->login, 'password' => $this->password, 'connect' => $this->connect];
        echo "Fonction connect ici";
      }

      else {
        echo "Mot de passe ou identifiant incorrect"; 
      }
    }

    public function disconnect() {
            unset($this->id, $this->login, $this->password, $this->connect);  
    }

    public function delete() {
      $requete_delete = $this->db->prepare("DELETE FROM utilisateurs WHERE id = ?");
      $requete_delete->execute([$this->id]);
      $this->disconnect();
    }

    public function update() {
      $hash = password_hash($this->password, PASSWORD_DEFAULT);
      $requete_update = $this->db->prepare("UPDATE utilisateurs SET login= :login, password= :password WHERE id = :id");
      $requete_update->execute(
        array(
            'id' => $this->id,
            'login' => "Update",
            'password' => $hash,
        ));
    }

    // public function isConnected() {
    //   if ($this->connect == 1) {
    //     return TRUE;
    //   }
    //   else {
    //     return FALSE;
    //   }
    // }

    // public function getAllInfos() {
    //   $requete_allinfos = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = ?"); 
    //   $requete_allinfos->execute([$this->id]);
    //   $result_allinfos = $requete_allinfos->fetchall();
    //   return [$this->id, $this->login, $this->password, $this->connect];
    // }

    // public function getLogin() {
    //   return $this->login;
    // }

    // public function refresh() {
    //   $requete_refresh = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = ?"); 
    //   $requete_refresh->execute([$this->id]);
    //   $user = $requete_refresh->fetchall();
    //   $this->login        = $user[0]['login'];
    //   $this->password     = $user[0]['password'];
    //   return [$this->login, $this->password];
    // }

    public function profil() {

      $checklogin = $this->checklogin();
      $requete_update = $this->db->prepare("UPDATE utilisateurs SET login= ?, password= ? WHERE id= ?");

      // changement du login seul
      if ($this->login !== NULL AND $this->password == NULL) {
  
        if ($checklogin == TRUE AND $checklogin['id'] == $_SESSION['user']['id']) {
          //  $error = TRUE;
          //  $errorMsg = "Ceci est déjà votre identifiant";
           echo "Ceci est déjà votre identifiant";
        }

        elseif ($checklogin == TRUE AND $checklogin['id'] !== $_SESSION['user']['id']) {   
          //  $error = TRUE;
          //  $errorMsg = "Identifiant déjà prit";
           echo "Identifiant déjà prit";
        }

        else {
           if (strlen($this->login) > 60) {
              // $error = TRUE;
              // $errorMsg = "L'identifiant doit faire moins de 60 caractères"; 
              echo "L'identifiant doit faire moins de 60 caractères"; 
           }

           else {
           $requete_update->execute([$this->login, $_SESSION['user']['password'], $_SESSION['user']['id']]);
          //  $sucess = TRUE;
          //  $sucessMsg = "Identifiant mis à jour";
           echo "Identifiant mis à jour";
           $_SESSION['user']['login'] = $this->login;
           }
        }
      }
               
      // Changement du password seul
      elseif ($this->login == $_SESSION['user']['login'] AND $this->password !== NULL) {
        // Sinon on check le nom de compte et le mot de passe
        if ($this->login == $this->password) {
          // $error = TRUE;
          // $errorMsg = "L'identifiant et le mot de passe doivent être différents";
          echo "L'identifiant et le mot de passe doivent être différents";
        }
        // On check les pré-requis mot de passe
        elseif (!preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#", $this->password)) {
          // $error = TRUE;
          // $errorMsg = "Le mot de passe doit contenir plus de 8 caractères, doit contenir une majuscule, une majuscule, un chiffre et un caractère spécial";
          echo "Le mot de passe doit contenir plus de 8 caractères, doit contenir une majuscule, une majuscule, un chiffre et un caractère spécial"; 
        }
        // les conditions sont remplis, on update
        else {
          $hash = password_hash($this->password, PASSWORD_DEFAULT);
          $id = $_SESSION['user']['id'];
          $requete_update->execute([$this->login, $hash, $id]);
          // $sucess = TRUE;
          // $sucessMsg = "Mot de passe mis à jour";
          echo "Mot de passe mis à jour";
        }
      }
  
      elseif ($this->login !== NULL AND $this->password !== NULL) {

        if ($checklogin == TRUE AND $checklogin['id'] == $_SESSION['user']['id']) {
          // $error = TRUE;
          // $errorMsg = "Ceci est déjà votre identifiant";
          echo "Ceci est déjà votre identifiant";
        }
        elseif ($checklogin == TRUE AND $checklogin['id'] !== $_SESSION['user']['id']) {   
          // $error = TRUE;
          // $errorMsg = "Identifiant déjà prit";
          echo "Identifiant déjà prit";
        }
        // Sinon on check le nom de compte et le mot de passe
        elseif ($this->login == $this->password) {
          // $error = TRUE;
          // $errorMsg = "L'identifiant et le mot de passe doivent être différents";
          echo "L'identifiant et le mot de passe doivent être différents";
        }
        // On check les pré-requis mot de passe
        elseif (!preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$#", $this->password)) {
          // $error = TRUE;
          // $errorMsg = "Le mot de passe doit contenir plus de 8 caractères, doit contenir une majuscule, une majuscule, un chiffre et un caractère spécial"; 
          echo "Le mot de passe doit contenir plus de 8 caractères, doit contenir une majuscule, une majuscule, un chiffre et un caractère spécial";
        }
        // les conditions sont remplis, on update
        else {
          $hash = password_hash($this->password, PASSWORD_DEFAULT);
          $id = $_SESSION['user']['id'];
          $requete_update->execute([$this->login, $hash, $id]);
          // $sucess = TRUE;
          // $sucessMsg = "Mot de passe et identifiant mis à jour";
          echo "Mot de passe et identifiant mis à jour";
          $_SESSION['user']['login'] = $this->login;
        }
      }
    }
}

// $user = new user("$login", "$password", "$cpassword");

// $user_checklogin = $user->checklogin();
// echo "<pre>" . "ligne 126". var_dump($user_checklogin). "</pre>";

// $user_register = $user->register();
// echo "<pre>" . var_dump($user_register). "</pre>";

// $user_connect = $user->connect();
// echo "<pre>" . var_dump($user_connect). "</pre>";

// $user_disconnect = $user->disconnect();
// echo "<pre>" . var_dump($user_disconnect). "</pre>";

// $user_delete = $user->delete();
// echo "<pre>" . var_dump($user_delete). "</pre>";

// $user_update = $user->update();
// echo "<pre>" . var_dump($user_update). "</pre>";

// $user_isConnected = $user->isConnected();
// echo "<pre>" . var_dump($user_isConnected). "</pre>";

// $user_infos = $user->getAllInfos();
// echo "<pre>" . var_dump($user_infos). "</pre>";

// $user_refresh = $user->refresh();
// echo "<pre>" . var_dump($user_refresh). "</pre>";

// echo "<pre>" . var_dump($user->isconnect). "</pre>";


?>