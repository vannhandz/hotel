<?php

    $hname='localhost';
    $unam='root';
    $pass='';
    $db='hotel';

    $con =mysqli_connect($hname,$unam,$pass,$db);
    if(!$con){
        die("Cannot connect to Database".mysqli_connect_error());
    }

    //lam sach du lieu
    function filteration($data){
        foreach($data as $key=>$value){
           $data[$key] = trim($value);
           $data[$key] = stripslashes($value);
           $data[$key] = htmlspecialchars($value);
           $data[$key] = strip_tags($value);
        }
        return $data;
    }

    // kiem tra usse pas
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

    // update seting
    function update($sql, $values, $datatypes)
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
                die("Update failed");
            }
        }else{
            die("Update failed");
        }
    }
?>