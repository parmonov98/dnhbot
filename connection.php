<?php

class connection
{
  public $error = false;
  public $lastId = 0;
  private $user = DB_USER;
  private $pass = DB_PASS;
  private $dbn = DB_NAME;
  private $pdo;


  function __construct()

  {

    try {
      $this->pdo = new PDO(
        'mysql:host=localhost;dbname=' . $this->dbn . ';charset=UTF8MB4',
        $this->user,
        $this->pass,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
      );
    } catch (PDOException $ex) {
      echo $ex->getMessage();
      // $this->error_parser($ex->getCode());
    }

    try {
      $this->pdo->exec("use " . $this->dbn);
    } catch (PDOException $ex) {
      $this->error = $ex->getCode();
      $res = $this->error_parser($ex->errorInfo[1]);
    }
  }



  function select(string $sql)
  {

    # static $cnt = 0;
    # file_put_contents("seletcs$cnt.txt", $sql);
    # $cnt++;
    $stmt = $this->pdo->prepare($sql);
    try {
      $stmt->execute();


      #file_put_contents('goodsbyCatefromConnection2', json_encode($dbdata = $stmt->fetchAll(PDO::FETCH_ASSOC)));

      return $dbdata = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
      file_put_contents('errors/DB_ERROR_SELECT.log', $ex->getMessage());
      $this->error = $ex->getCode();
    }
  }



  function insert(string $sql)
  {

    $stmt = $this->pdo->prepare($sql);
    file_put_contents('errors/DB_ERROR_INSERT_SQL.log', $sql);
    try {
      $stmt->execute();
      $this->lastId = $this->pdo->lastInsertId();

      // file_put_contents('lastidfromconnection.log', $this->lastId);
    } catch (PDOException $ex) {
      // file_put_contents('errors/DB_ERROR_INSERT.log', $ex->getCode());
      echo $ex->getMessage();
      $this->error = $ex->getCode();
      return $ex->getCode();
    }
    if ($stmt->rowCount() == 1)
      return $stmt->rowCount();
    else
      return false;
  }

  function execute(string $sql)
  {
    try {
      $result = $this->pdo->exec($sql);
    } catch (PDOException $ex) {
      echo $ex->getMessage();
      $this->error = $ex->getCode();
      return $ex->getCode();
    }
    if ($result === false)
      return false;
    else
      return true;
  }

  function update(string $sql)
  {

    $stmt = $this->pdo->prepare($sql);
    file_put_contents('errors/DB_ERROR_UPDATE_SQL.log', $sql);
    try {
      $stmt->execute();
    } catch (PDOException $ex) {
      file_put_contents('errors/DB_ERROR_UPDATE.log', $ex->getCode());
      $this->error = $ex->getCode();
      return $ex->getCode();
    }
    if (is_numeric($rows = $stmt->rowCount()))
      return $rows;
    else
      return false;
  }

  function getLastInsertId()
  {
    return $this->lastId;
  }

  function remove(string $sql)
  {
    file_put_contents('errors/DB_REMOVE_SQL.log', $sql);
    $stmt = $this->pdo->prepare($sql);
    try {
      $stmt->execute();
    } catch (PDOException $ex) {
      $this->error = $ex->getCode();
      file_put_contents('errors/DB_ERROR_REMOVE.log', $ex->getMessage());
    }
    if ($stmt->rowCount() == 1)
      return true;
    else
      return 'error';
  }



  function choose_database()
  {
    // use exec() because no results are returned
    $sql = "USE " . $this->dbname;
    $this->pdo->exec($sql);


    $res = $this->create_table();

    if ($res == true) {

      $this->test_insert();
    } else {
      echo $this->error_parser($res);
    }
  }

  function error_parser(string $errcode)
  {
    // echo $errcode;
    switch ($errcode) {
      case '1049':
        $res = $this->create_database($this->dbn);
        break;
      case '1146':
        $res = $this->create_table();
        if ($res != true)
          return $res;
        else
          die;
        break;
      case '1044':
        $res = 'please, ask to administrator to give privileges';
        break;
      case '1064':
        $res = 'There was occured unknown error while creating! please, check your SQL REQUEST!';
        break;
      case '3D000': // when a db is not chosen then the is returned
        $res = $this->choose_database();
        break;
      case '1045':
        return ' You have entered incorrect username  OR password for connection!';
        break;
      default:
        $res = 'Unknown error occured!';
    }
    return $res;
  }
}
