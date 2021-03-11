<?php

use Rakit\Validation\Rule;

class UniqueRule extends Rule
{
    protected $message = ":attribute :value has been taken";

    protected $fillableParams = ['table', 'column', 'except'];

    protected $pdo;

    public function __construct()
    {
        $Server_username        = $_ENV['DB_USERNAME'];
        $Server_Password        = $_ENV['DB_PASSWORD'];
        $Server_Database_name   = $_ENV['DB_DATABASE'];
        $Server_host            = $_ENV['DB_HOST'];

        $this->pdo = new PDO('mysql:host='.$Server_host.';dbname='.$Server_Database_name, $Server_username, $Server_Password);
    }

    public function check($value): bool
    {
        // make sure required parameters exists
        $this->requireParameters(['table', 'column']);

        // getting parameters
        $column = $this->parameter('column');
        $table  = $this->parameter('table');
        $except = $this->parameter('except');

        if ($except AND $except == $value) {
            return true;
        }

        // do query
        $stmt = $this->pdo->prepare("select count(*) as count from `{$table}` where `{$column}` = :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // true for valid, false for invalid
        return intval($data['count']) === 0;
    }


    function something() {
        // We could simply use `global $db;`, but using globals is bad. Instead we can do this:
        $db = Database::instance();
        return$db;
    }
}