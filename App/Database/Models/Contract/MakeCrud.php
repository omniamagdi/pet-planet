<?php

namespace App\Database\Models\Contract;


interface MakeCrud {
    function create() :bool;
    function read() :\mysqli_result;
    function update()  :bool;
    function delete()  :bool;
}
?>