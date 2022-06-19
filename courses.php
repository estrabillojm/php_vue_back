<?php
    include "conn.php";
    $myArray = array();
    $data = json_decode(file_get_contents('php://input'), true);
    $method = $data['method'];

    $query = "";
    $submethod = "";
    $lastinserted = "";
    switch($method){
        case 'fetchAllCourses':
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $submethod = "GET";
                $query = "SELECT * FROM courses ORDER BY id DESC";
            }
        break;
        case 'updateCourse':
            if($_SERVER['REQUEST_METHOD'] == 'PUT'){
                $course = mysqli_real_escape_string($conn, $data['payload']['course']);
                $id =  mysqli_real_escape_string($conn,$data['payload']['id']);   
                $query = "UPDATE courses SET course='$course' WHERE id = $id";
            }
        break;
        case 'addCourse':
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $submethod = "ADD";
                $course = mysqli_real_escape_string($conn, $data['payload']['course']);   
                $query = "INSERT INTO courses (course) VALUES ('$course')";  
            }
        break;

        case 'deleteCourse':
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $submethod = "DELETE";

                
                $id = mysqli_real_escape_string($conn, $data['payload']);   
                
                $query = "DELETE FROM courses WHERE id = $id";  
            }
        break;

        default: break;
    }
    if($submethod == "GET"){
        $result = $conn->query($query);
        while($row = $result->fetch_assoc()) {
            $myArray[] = $row;
        }
        print json_encode($myArray);
    }else{
        $result = $conn->query($query);
        if(isset($conn -> insert_id)){
            $lastinserted = $conn -> insert_id;
        }
        
        print json_encode(array("data"=>$data['payload'], "id" => $lastinserted));
    }
    

?>