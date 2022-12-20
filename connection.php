<?php
// Classe de conexão PDO com o banco de dados
class Database
{
    protected static $db;
    
    private function __construct()
    {
        $db_host = "containers-us-west-172.railway.app";
        $db_user = "root";
        $db_password = 'uwTpMIonfjY7apl02DEE';
        $db_name = "railway";
        
        $options = array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION );

        try
        {
            self::$db = new PDO('mysql:host='.$db_host.'; port=5835; dbname='.$db_name,$db_password);
            // self::$db = new PDO("$db_driver:host=$db_host; dbname=$db_name", $db_user, $db_password, $options);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            self::$db->exec('SET NAMES utf8mb4');
        }
        catch (PDOException $e)
        {   
            die("Connection Error: " . $e->getMessage());
        }
    }

    public static function connectionPDO()
    {
        if (!self::$db)
        {
            new Database();
        }
        return self::$db;
    }
}
?>
