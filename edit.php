<?php

require_once "config/db.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDO-CRUD-Users</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <style>
        .container {
            max-width: 550px;
        }
    </style>

</head>

<body>

    <div class="container mt-5">
        <h1>แก้ไขข้อมูล</h1>
        <hr>
        <form action="update.php" method="post" enctype="multipart/form-data">
            <?php
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $stmt = $conn->query("SELECT * FROM users WHERE id = $id");
                $stmt->execute();
                $data = $stmt->fetch();
            }
            ?>
            <div class="mb-3">
                <input type="hidden" readonly value="<?= $data['id']; ?>" required class="form-control" name="id">
                <label for="firstname" class="col-form-label">ชื่อ:</label>
                <input type="text" value="<?= $data['firstname']; ?>" required class="form-control" name="firstname">
                <input type="hidden" value="<?= $data['image']; ?>" required class="form-control" name="image2">
            </div>
            <div class="mb-3">
                <label for="lastname" class="col-form-label">นามสกุล:</label>
                <input type="text" value="<?= $data['lastname']; ?>" required class="form-control" name="lastname">
            </div>
            <div class="mb-3">
                <label for="position" class="col-form-label">ตำแหน่ง:</label>
                <input type="text" value="<?= $data['position']; ?>" required class="form-control" name="position">
            </div>
            <div class="mb-3">
                <label for="image" class="col-form-label">รูปภาพ:</label>
                <input type="file" class="form-control" id="imgInput" name="image">
                <img width="100%" src="uploads/<?= $data['image']; ?>" id="previewImg" alt="">
            </div>

            <div class="modal-footer justify-content-center">
                <button type="submit" name="update" class="btn btn-success m-3">บันทึก</button>
                <a class="btn btn-secondary" href="index.php">ย้อนกลับ</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <script>
        let imgInput = document.getElementById('imgInput');
        let previewImg = document.getElementById('previewImg');

        imgInput.onchange = evt => {
            const [file] = imgInput.files;
            if (file) {
                previewImg.src = URL.createObjectURL(file);
            }
        }
    </script>

</body>

</html>