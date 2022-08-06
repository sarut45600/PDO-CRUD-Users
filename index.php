<?php

require_once "config/db.php";

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $deletestmt = $conn->query("DELETE FROM users WHERE id = $delete_id");
    $deletestmt->execute();

    if ($deletestmt) {
        header("refresh:1; url=index.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDO-CRUD-Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>

<body>

    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เพิ่มผู้ใช้</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="insert.php" id="formData" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="firstname" class="col-form-label">ชื่อ:</label>
                            <input type="text" required class="form-control" name="firstname">
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="col-form-label">นามสกุล:</label>
                            <input type="text" required class="form-control" name="lastname">
                        </div>
                        <div class="mb-3">
                            <label for="position" class="col-form-label">ตำแหน่ง:</label>
                            <input type="text" required class="form-control" name="position">
                        </div>
                        <div class="mb-3">
                            <label for="image" class="col-form-label">รูปภาพ:</label>
                            <input type="file" required class="form-control" id="imgInput" name="image">
                            <img width="100%" id="previewImg" alt="">
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button type="submit" id="submit" name="submit" class="btn btn-success m-3">เพิ่ม</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 ">
                <h1>ข้อมูลผู้ใช้</h1>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">เพิ่มผู้ใช้</button>
            </div>
        </div>
        <hr>

        <!-- Users Data -->
        <table class="table">
            <thead class="text-center">
                <tr>
                    <th scope="col">ชื่อ</th>
                    <th scope="col">นามสกุล</th>
                    <th scope="col">ตำแหน่ง</th>
                    <th scope="col">รูปภาพ</th>
                    <th scope="col">ดำเนินการ</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php
                $stmt = $conn->query("SELECT * FROM users");
                $stmt->execute();
                $users = $stmt->fetchAll();

                if (!$users) {
                    echo "<tr><td colspan='6' class='text-center'>ไม่พบผู้ใช้</td></tr>";
                } else {
                    foreach ($users as $user) {
                ?>
                        <tr>
                            <td><?= $user['firstname']; ?></td>
                            <td><?= $user['lastname']; ?></td>
                            <td><?= $user['position']; ?></td>
                            <td width="250px"><img width="100%" src="uploads/<?= $user['image']; ?>" class="rounded" alt=""></td>
                            <td>
                                <a href="edit.php?id=<?= $user['id']; ?>" class="btn btn-warning ">แก้ไขข้อมูล</a>
                                <a data-id="<?= $user['id']; ?>" href="?delete=<?= $user['id']; ?>" class="btn btn-danger delete-btn">ลบข้อมูล</a>
                            </td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>
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

        $(".delete-btn").click(function(e) {
            var userId = $(this).data('id');
            e.preventDefault();
            deleteConfirm(userId);
        })

        function deleteConfirm(userId) {
            Swal.fire({
                title: 'คุณแน่ใจไหม ?',
                text: "ต้องการลบข้อมูลหรือไม่ ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#3085d6',
                cancelButtonText: 'ยกเลิก',
                cancelButtonColor: '#d33',
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                                url: 'index.php',
                                type: 'GET',
                                data: 'delete=' + userId,
                            })
                            .done(function() {
                                Swal.fire({
                                    title: 'ลบข้อมูล',
                                    text: 'ลบข้อมูลสำเร็จ',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6'
                                }).then(() => {
                                    document.location.href = 'index.php';
                                })
                            })
                            .fail(function() {
                                Swal.fire('เกิดข้อผิดพลาด', 'ลบข้อมูลไม่สำเร็จ', 'error')
                                window.location.reload();
                            });
                    });
                },
            });
        }
    </script>

</body>

</html>