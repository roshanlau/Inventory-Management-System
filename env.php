<?php

if (count(get_included_files()) == 1)
    exit("Direct access not permitted.");


class Env
{
    public static $salt = "wTn4qZdJ3e"; //need change
    public static $servername = "localhost";
    public static $username = "root";
    public static $password = "";
    public static $dbname = "inventory_management_db";
    public static $port = "3306";

}

?>