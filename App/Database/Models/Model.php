<?php 

namespace App\Database\Models;

use App\Database\Connection\Connection;

class Model extends Connection {

    public function search($tableName , $columnName , $value)

    {
        $query = "SELECT * FROM {$tableName} WHERE {$columnName} = ?";
        $returned_stmt = $this->connect->prepare($query); 

        if(! $returned_stmt){
            return false;
        }

        $returned_stmt ->bind_param('s',$value); 
        $returned_stmt ->execute();
        return $returned_stmt ->get_result();
    }

}