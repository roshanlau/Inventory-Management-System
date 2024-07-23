<?php
if (count(get_included_files()) == 1)
  exit("Direct access not permitted.");

require_once 'env.php';


class Auth
{
  const NORMAL_STAFF = "NORMAL_STAFF";
  const ADMIN = "ADMIN";
  const SUPERVISOR = "SUPERVISOR";

  public static function getAccessLevelsMap()
  {
    return [
      Auth::NORMAL_STAFF => "Normal Staff",
      Auth::ADMIN => "Admin",
      Auth::SUPERVISOR => "Supervisor",
    ];
  }

  public static function hash($rawPassword)
  {
    return password_hash($rawPassword, PASSWORD_BCRYPT, array("cost" => 10));
  }

  public static function verify($password, $hash)
  {
    return password_verify($password, $hash);
  }

  public static function login($id, $password)
  {
    global $authError;
    if (!isset($id) || $id === "") {
      $authError = "Empty ID detected. Please enter a valid ID";
      return;
    }

    if (!isset($password) || $password === "") {
      $authError = "Empty password detected. Please enter a valid password";
      return;
    }

    $conn = new PDO("mysql:host=" . Env::$servername . ";dbname=" . Env::$dbname, Env::$username, Env::$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM tbl_staffs_a189629_pt2 where fld_staff_num=:id LIMIT 1");
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetch();

    if(!$result || !Auth::verify($password, $result["fld_password"])) {                          //!Auth::verify($password, $result["fld_password"])
      $authError = "Invalid ID or Password";
      return;
    }
    
    session_start();
    $_SESSION["id"] = $id;
    $_SESSION["fullname"] = $result["fld_staff_fname"] . " " . $result["fld_staff_lname"];
    $_SESSION["accessLevel"] = $result["fld_access_level"];
  }

  public static function logout()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    unset($_SESSION["id"]);
    unset($_SESSION["accessLevel"]);
    session_unset();
  }

  public static function is_authed()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    return isset($_SESSION["id"]);
  }

  /**
   * @param Auth::NORMAL_STUFF|Auth::ADMIN|Auth::SUPERVISOR $level
   */
  public static function is_level($level)
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    if (!isset($_SESSION["accessLevel"])) {
      return false;
    }

    if (is_array($level)) {
      foreach ($level as $l) {
        if ($_SESSION["accessLevel"] === $l) {
          return true;
        }
      }
      return false;
    }

    return $_SESSION["accessLevel"] === $level;
  }

  public static function get_id()
  {
    if(!Auth::is_authed()) {
      return "";
    }

    return $_SESSION["id"];
  }

  public static function get_full_name()
  {
    if(!Auth::is_authed()) {
      return "";
    }

    return $_SESSION["fullname"];
  }

  public static function get_access_level()
  {
    if(!Auth::is_authed()) {
      return "";
    }

    return $_SESSION["accessLevel"];
  }
}
