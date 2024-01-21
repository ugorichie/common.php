 <?php
require('Config.php');


class Db{
        
    private $dbHost = DB_SERVER ;
    private $dbUsername = DB_USERNAME;
    private $dbPassword = "";
    private $dbName = DB_NAME;



    //THIS FUNCTION CONNECTS THIS CLASS(PHP) TO THE DATABASE
    public function connect(){
        try{

            global $conn;

            $dsn = "mysql:host={$this->dbHost};dbname={$this->dbName}";
           $conn = new PDO($dsn,$this->dbUsername,$this->dbPassword);
            $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             return $conn;
            //   

        }catch(PDOexception $e){

            echo $e -> getMessage();

        }

    }

    //public function tep_query_db($table,$input,$id = '' ){

    public function tep_single_query_db($table,$condition, $id = '' ){
       
        if($id){
            $query = "SELECT * FROM $table WHERE $condition = '$id' ";
        }else{
            $query = "SELECT * FROM $table";
        }
        
        $stmt = $this-> connect() -> prepare($query);
        $stmt ->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
         return $result;
        
    }



    function cast(){
        global $conn;
        if ($conn->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
     echo "Running on mysql; doing something mysql specific here\n";
        }
    }

   

//  public function insertIntoDatabase($tableName, $data) {
   
//         $columns = implode(", ", array_keys($data));
//         $values = ":" . implode(", :", array_keys($data));
//         $sql = "INSERT INTO $tableName ($columns) VALUES ($values)";

//         // Prepare and execute the SQL statement
//         $stmt = $conn->prepare($sql);
//         $stmt->execute($data);

//         echo "Record inserted successfully";
//  }

 public function writeToDatabase($operation, $tableName, $data, $condition = null) {
    global $conn;
       
    if ($operation === 'insert') {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $tableName ($columns) VALUES ($values)";

    } elseif ($operation === 'update') {
        $updateColumns = array_map(function ($column) {
            return "$column = :$column";
        }, array_keys($data));
        $sql = "UPDATE $tableName SET " . implode(", ", $updateColumns);

        if ($condition !== null) {
            $sql .= " WHERE $condition";
        }
    } else {
        echo "Invalid operation specified. Use 'insert' or 'update'.";
        return;
    }

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);

    echo "Record $operation successfully";


}

   






}

    //INSTANTIALIZATION HAPPENS HERE
         $demo = new Db();
       print_r($demo -> tep_single_query_db('engineerdetails','engineer_email'));

       
       $demo-> cast();




       
    // Example usage for insert:
    $tableName = "your_table_name";
    $dataToInsert = array(
        'column1' => 'value1',
        'column2' => 'value2',
        'column3' => 'value3',
    );
    
    writeToDatabase('insert', $tableName, $dataToInsert);
    
    // Example usage for update:
    $tableName = "your_table_name";
    $dataToUpdate = array(
        'column1' => 'new_value1',
        'column2' => 'new_value2',
    );
    
    $condition = "id = 1"; // Specify the condition for the update
    writeToDatabase('update', $tableName, $dataToUpdate, $condition);
    
    