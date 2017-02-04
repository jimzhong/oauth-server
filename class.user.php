<?php

require 'vendor/autoload.php';

class User
{
    private $db;
    public $userid
    public $username;
    public $realname;
    public $email;
    public $bbsuid;
    public $zjuid;
    public $is_staff = false;
    public $is_admin = false;

    function __construct($dbh)
    {
      $this->db = $dbh;
    }

    // public function register($fname,$lname,$uname,$umail,$upass)
    // {
    //    try
    //    {
    //        $new_password = password_hash($upass, PASSWORD_DEFAULT);
    //
    //        $stmt = $this->db->prepare("INSERT INTO users(user_name,user_email,user_pass)
    //                                                    VALUES(:uname, :umail, :upass)");
    //
    //        $stmt->bindparam(":uname", $uname);
    //        $stmt->bindparam(":umail", $umail);
    //        $stmt->bindparam(":upass", $new_password);
    //        $stmt->execute();
    //
    //        return $stmt;
    //    }
    //    catch(PDOException $e)
    //    {
    //        echo $e->getMessage();
    //    }
    // }

    // public function save()
    // {
    //     $sth = $this->db->prepare("INSERT INTO users(username, bbsuid, email) VALUES (:username, :bbsuid, :email)");
    //     $sth->bindparam(":username", $this->$username);
    //     $sth->bindparam(":email", $this->$email);
    //     $sth->bindparam(":bbsuid", $this->$bbsuid);
    // }

    public function load_info_from_session()
    {
        $this->bbsuid = $_SESSION['bbsuid'];
        $this->username = $_SESSION['username'];
        $this->email = $_SESSION['email']
    }

    public function save_info_to_session()
    {
        
    }

    public function fetch_info_from_zjuam($zjuid, $password)
    {
        return false;
    }

    public function fetch_info_from_bbs($username, $password)
    {
        $client = new GuzzleHttp\Client(['timeout'  => 5.0, 'http_errors' => false]);
        $res = $client->request('POST', "https://api.zjubtv.com/Passport/userLogin",
            ['form_params' => ["identity"=> $username, "password"=> $password]]);
        if ($res->getStatusCode() != 200)
            return false;
        $data = json_decode($res->getBody(), true);
        if ($data != null && $data[0] > 0)
        {
            $this->bbsuid = $data[0];
            $this->username = $data[1];
            $this->email = $data[3];
            return true;
        }
        return false;
    }

    public function is_new_user_from_bbs()
    {
       $sth = $this->db->prepare("SELECT id FROM users WHERE username=:username LIMIT 1");
       $sth->execute(array(':username'=>$this->username));
       return ($sth->rowCount() == 0);
    }

    public function is_new_user_from_zju()
    {
       $sth = $this->db->prepare("SELECT id FROM users WHERE zjuid=:zjuid LIMIT 1");
       $sth->execute(array(':username'=>$this->zjuid));
       return ($sth->rowCount() == 0);
    }

    public static function is_loggedin()
    {
      return isset($_SESSION['username']);
    }

    public static function logout()
    {
        unset($_SESSION['username']);
        session_destroy();
        return true;
    }
}
?>
