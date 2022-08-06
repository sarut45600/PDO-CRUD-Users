<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php

require_once "config/db.php";

if (isset($_POST['submit'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $position = $_POST['position'];
    $image = $_FILES['image'];

    $allow = array('jpg', 'jpeg', 'png');
    $extension = explode(".", $image['name']);
    $fileActExt = strtolower(end($extension));
    $fileNew = rand() . "." . $fileActExt;
    $filePath = "uploads/" . $fileNew;

    if (in_array($fileActExt, $allow)) {
        if ($image['size'] > 0 && $image['error'] == 0) {
            if (move_uploaded_file($image['tmp_name'], $filePath)) {
                $sql = $conn->prepare("INSERT INTO users(firstname, lastname, position, image) VALUES(:firstname, :lastname, :position, :image)");
                $sql->bindParam(":firstname", $firstname);
                $sql->bindParam(":lastname", $lastname);
                $sql->bindParam(":position", $position);
                $sql->bindParam(":image", $fileNew);
                $sql->execute();
            }
        }
    }

    if ($sql) {
        echo "<script>
        $(document).ready(function() {
            Swal.fire({
                title: 'เพิ่มข้อมูล',
                text: 'เพิ่มข้อมูลสำเร็จ',
                icon: 'success',
                timer: 5000,
                showConfirmButton: false
            });
        })
    </script>";
    
    header("refresh:2; url=index.php");
    }
}


?>