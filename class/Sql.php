<?php

class Sql extends PDO {

  private $conn;

  public function __construct($dbtype, $host, $dbname, $user, $password) {
    $this->conn = new PDO($dbtype.":host=".$host.";dbname=".$dbname, $user, $password);
  }

  private function setParam($statement, $key, $value) {
    $statement->bindParam($key, $value);
  }

  private function setParams($statement, $parameters = array()) {
    foreach ($parameters as $key => $value) {
      $this->setParam($statement, $key, $value);
    }
  }

  public function query($query, $params = array()) {
    $stmt = $this->conn->prepare($query);
    $this->setParams($stmt, $params);
    $stmt->execute();
    return $stmt;
  }

  public function select($query, $params = array()):array {
    $stmt = $this->query($query, $params);
    return $stmt->fetchALL(PDO::FETCH_ASSOC);
  }

  public function toCSV($query, $filename) {

    $data = $this->select($query);

    $headers = array();

    $files = scandir(".");

    if(!in_array("$filename.csv", $files)) {

      $arq = fopen("$filename.csv", "w+");

      foreach ($data[0] as $key => $value) {
        array_push($headers, ucfirst($key));
      }

      fwrite($arq, implode(";", $headers)."\r\n");

      foreach ($data as $row) {

        $col = array();

        foreach ($row as $key => $value) {
          array_push($col, $value);
        }

        fwrite($arq, implode(";", $col)."\r\n");

      }

      fclose($arq);
      $msg = "Arquivo criado com sucesso!";
    } else {
      $msg = "Nome de arquivo já existente.";
    }

    echo $msg;

  }

}

?>
