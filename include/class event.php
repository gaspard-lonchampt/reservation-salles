<?php

class event {

  private $id_user;
  public $titre;
  public $description;
  public $date_start;
  public $date_end;

  public function __construct($titre, $description, $date_start, $date_end) {
    $this->titre = $titre;
    $this->description = $description;
    $this->date_start = $date_start;
    $this->date_end = $date_end;
    $this->id_user = $_SESSION['user']['id'];
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


















}