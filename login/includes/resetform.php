<?php
class ResetForm extends DbConn
{
  public function reset($email)
  {
    try {
      $db = new DbConn;
      $tbl_members = $db->tbl_members;
      $newpassword = rand_string(8);
      $newpasswordhash = password_hash($newpassword, PASSWORD_DEFAULT);
      $stmt = $db->conn->prepare("UPDATE ".$tbl_members." SET password =  :password WHERE email = :email");
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', $newpasswordhash);
      $stmt->execute();
      $success = "true " . $newpassword;
    } catch (PDOException $e) {
      $success = "Error: " . $e->getMessage();
    }
    return $success;
  }

  public function checkExists($email)
  {
    try {
      $db = new DbConn;
      $tbl_members = $db->tbl_members;
      $stmt = $db->conn->prepare("SELECT * FROM ".$tbl_members." WHERE email = :email");
      $stmt->bindParam(':email', $email);
      $stmt->execute();
      $success =  $stmt->fetch();
      if ($stmt->fetch()) {
        $success = true;
      }else{
        $success =  false;
      }
    } catch (PDOException $e) {
      $success = "Error: " . $e->getMessage();
    }
    return $success;
  }

  public function getUser($email)
  {
    try {
      $db = new DbConn;
      $tbl_members = $db->tbl_members;
      $stmt = $db->conn->prepare("SELECT * FROM ".$tbl_members." WHERE email = :email");
      $stmt->bindParam(':email', $email);
      $stmt->execute();
      $success =  $stmt->fetch();

    } catch (PDOException $e) {
      $success = "Error: " . $e->getMessage();
    }
    return $success;
  }
}
