<?php
// Ngăn chặn việc include file nhiều lần
if (defined('DB_CONFIG_INCLUDED')) {
    return;
}
define('DB_CONFIG_INCLUDED', true);

$hname='localhost';
$unam='root';
$pass='';
$db='hotel';

$con =mysqli_connect($hname,$unam,$pass,$db);
if(!$con){
    die("Cannot connect to Database".mysqli_connect_error());
}

// lọc và làm sạch dữ liệu đầu vào
if (!function_exists('filteration')) {
    function filteration($data){
        foreach($data as $key=>$value){
            $value = trim($value);
            $value = stripslashes($value);
            $value = htmlspecialchars($value);
            $value = strip_tags($value);

            $data[$key]=$value;
        }
        return $data;
    }
}

if (!function_exists('selectAll')) {
    function selectAll($table){
        $con=$GLOBALS['con'];
        $res=mysqli_query($con,"SELECT * FROM $table");
        return $res;
    }
}

if (!function_exists('select')) {
    function select($sql, $values, $datatypes)
    {
        $con=$GLOBALS['con'];
        if($stmt=mysqli_prepare($con, $sql))
        {
            mysqli_stmt_bind_param($stmt,$datatypes,...$values);
            if(mysqli_stmt_execute($stmt)){
                $res=mysqli_stmt_get_result($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            }
            else {
                mysqli_stmt_close($stmt);
                die("Query failed");
            }
        }else{
            die("Query failed");
        }
    }
}

if (!function_exists('update')) {
    function update($query, $values, $datatypes)
    {
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con, $query))
        {
            mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
            if(mysqli_stmt_execute($stmt))
            {
                $res = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            }
            else
            {
                mysqli_stmt_close($stmt);
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}

if (!function_exists('insert')) {
    function insert($sql, $values, $datatypes)
    {
        $con=$GLOBALS['con'];
        if($stmt=mysqli_prepare($con, $sql))
        {
            mysqli_stmt_bind_param($stmt,$datatypes,...$values);
            if(mysqli_stmt_execute($stmt)){
                $res=mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            }
            else {
                mysqli_stmt_close($stmt);
                die("Insert failed");
            }
        }else{
            die("Insert failed");
        }
    }
}

if (!function_exists('delete')) {
    function delete($sql, $values, $datatypes)
    {
        $con=$GLOBALS['con'];
        if($stmt=mysqli_prepare($con, $sql))
        {
            mysqli_stmt_bind_param($stmt,$datatypes,...$values);
            if(mysqli_stmt_execute($stmt)){
                $res=mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            }
            else {
                mysqli_stmt_close($stmt);
                die("Delete failed");
            }
        }else{
            die("Delete failed");
        }
    }
}
?>