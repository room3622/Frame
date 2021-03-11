<?php
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

class Counter
{

    private static $table_name = "counter";
    public static $db_fields = array();

    public $id;
    public $counter;







    public function __construct()
    {
        $db = $this->databese();

        $result_set = $db->query("SELECT * FROM ".self::$table_name." LIMIT 1");
        $num_fields = $result_set->result->field_count;
        foreach($result_set->result_array() as $key => $value)
        {
            $stdArray[$key] = (array) $value;
            foreach ($stdArray[0] as $key=>$value):
                self::$db_fields[] = $key;
                $name =  $this->{$key} ;
            endforeach;
        }


    }








    function databese() {
        // We could simply use `global $db;`, but using globals is bad. Instead we can do this:
        $db = Database::instance();
        return$db;
    }


    public function __get($name) {
        echo "Object <b>$name</b> does not exist From <b>".ucfirst(self::$table_name)."</b> Class";
        exit();
    }



    public function __set($name, $value) {
        echo "Object <b>$name</b> does not exist On <b>".ucfirst(self::$table_name)."</b> Database";
        exit();
    }




    public static function find_by_id($id=0) {
        $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function find_by_sql($sql=""){
        $database = new Database();
        $result_set = $database->query($sql);
        $data= $result_set->result_array();


        $object_array = array();
        $i = 0;
        foreach($row = $result_set->result_array() as $key => $value)
        {

            $object_array[] = self::instantiate($row[$i++]);
        }



        return $object_array;
    }



    public static function convertToObject($array) {
        $array = (object)$array;

        return $array;
    }

    public function Save(){
        return isset($this->id) ? $this->Update() : $this->Create();
    }

    public function Create(){

        $data = (array) $this;

        $db = $this->databese();
        $db->insert(self::$table_name,$data);
        return $db->id();
    }




    public function Update(){
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = array();
        foreach($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE ".self::$table_name." SET ";
        $sql .= join(", ", $attribute_pairs);
        $sql .= " WHERE id=". $this->id;
        $db = $this->databese();
        $db->query($sql);
        return ($db->affected() == 1) ? true : false;
    }


    protected function sanitized_attributes() {

        $clean_attributes = array();
        // sanitize the values before submitting
        // Note: does not alter the actual value of each attribute
        foreach($this->attributes() as $key => $value){
            $clean_attributes[$key] = $value;
        }
        return $clean_attributes;
    }


    public static function instantiate($record) {
        $object = new self;
        foreach($record as $attribute=>$value){
            if($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }

    private function has_attribute($attribute) {
        return array_key_exists($attribute, $this->attributes());
    }
    protected function attributes() {
        $attributes = array();
        foreach(self::$db_fields as $field) {
            if(property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        }
        return $attributes;
    }
}