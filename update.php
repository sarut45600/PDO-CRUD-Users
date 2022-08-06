<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php

require_once "config/db.php";

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $position = $_POST['position'];
    $image = $_FILES['image'];

    $image2 = $_POST['image2'];
    $upload = $_FILES['image']['name'];

    if ($upload != '') {
        $allow = array('jpg', 'jpeg', 'png');
        $extension = explode(".", $image['name']);
        $fileActExt = strtolower(end($extension));
        $fileNew = rand() . "." . $fileActExt;
        $filePath = "uploads/" . $fileNew;

        if (in_array($fileActExt, $allow)) {
            if ($image['size'] > 0 && $image['error'] == 0) {
                move_uploaded_file($image['tmp_name'], $filePath);
            }
        }
    } else {
        $fileNew = $image2;
    }

    $sql = $conn->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, position = :position, image = :image WHERE id = :id");
    $sql->bindParam(":id", $id);
    $sql->bindParam(":firstname", $firstname);
    $sql->bindParam(":lastname", $lastname);
    $sql->bindParam(":position", $position);
    $sql->bindParam(":image", $fileNew);
    $sql->execute();

    if ($sql) {
        echo "<script>
                $(document).ready(function() {
                    Swal.fire({
                        title: 'แก้ไขข้อมูล',
                        text: 'แก้ไขข้อมูลสำเร็จ',
                        icon: 'success',
                        timer: 5000,
                        showConfirmButton: false
                    });
                })
            </script>";
        header("refresh:2; url=index.php");
    } else {
        echo "<script>
        $(document).ready(function() {
            Swal.fire({
                title: 'เกิดข้อผิดพลาด',
                text: 'แก้ไขข้อมูลไม่สำเร็จ',
                icon: 'error',
                timer: 5000,
                showConfirmButton: false
            });
        })
    </script>";
        header("refresh:2; url=index.php");
    }
}

?>