<?php

if (userLogin()['level'] != 1) {
    header("location:" . $main_url . "error-page.php");
    exit();
}

// module-user.php

function insert($data) {
    global $koneksi;

    $username   = mysqli_real_escape_string($koneksi, $data['username']);
    $fullname   = mysqli_real_escape_string($koneksi, $data['fullname']);
    $password   = mysqli_real_escape_string($koneksi, $data['password']);
    $password2  = mysqli_real_escape_string($koneksi, $data['password2']);
    $level      = $data['level'];
    $address    = mysqli_real_escape_string($koneksi, $data['address']);

    // ✅ Validasi password
    if ($password !== $password2) {
        echo "<script>alert('Konfirmasi password tidak sama!');</script>";
        return 0;
    }

    // Enkripsi password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Handle upload foto
    $foto = "default.webp"; // default kalau user tidak upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed   = ['jpg','jpeg','png','gif','webp'];
        $filename  = $_FILES['image']['name'];
        $filesize  = $_FILES['image']['size'];
        $tmp_name  = $_FILES['image']['tmp_name'];

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            if ($filesize > 2000000) { // max 2MB
                echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB');
                </script>";
                return 0;
            }
            // buat nama unik biar ga nabrak
            $newname = uniqid() . "." . $ext;
            move_uploaded_file($tmp_name, "../asset/image/" . $newname);
            $foto = $newname;
        }
    }

    // Insert ke DB
    $query = "INSERT INTO tbl_user (username, fullname, password, level, address, foto) 
              VALUES ('$username', '$fullname', '$passwordHash', '$level', '$address', '$foto')";
    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}


function delete($id, $foto){
    global $koneksi;

    $sqlDel = "DELETE FROM tbl_user WHERE userid = $id";
    mysqli_query($koneksi, $sqlDel);
    if ($foto != 'default.png') {
        unlink('../asset/image' . $foto);
    }

    return mysqli_affected_rows($koneksi);
}

function selectUser1($level){
    $result = null;
    if ($level == 1) {
        $result = "selected";
    }
    return $result;
}

function selectUser2($level){
    $result = null;
    if ($level == 2) {
        $result = "selected";
    }
    return $result;
}

function selectUser3($level){
    $result = null;
    if ($level == 3) {
        $result = "selected";
    }
    return $result;
}

function update($data) {
    global $koneksi;

    $iduser     = mysqli_real_escape_string($koneksi, $data['id']);
    $username   = strtolower( mysqli_real_escape_string($koneksi, $data['username']));
    $fullname   = mysqli_real_escape_string($koneksi, $data['fullname']);
    $level      = $data['level'];
    $address    = mysqli_real_escape_string($koneksi, $data['address']);
    $gambar     = mysqli_real_escape_string($koneksi, $_FILES['image']['name']);
    $fotoLama   = mysqli_real_escape_string($koneksi, $data['oldImg']);

    // cek username sekarang
    $queryUsername = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE userid = $iduser");
    $dataUsername  = mysqli_fetch_assoc($queryUsername);
    $curUsernme    = $dataUsername['username'];

    // cek username baru
    $newUsername   = mysqli_query($koneksi, "SELECT username FROM tbl_user WHERE username = '$username'");

    if ($username !== $curUsernme) {
        if (mysqli_num_rows($newUsername)) {
            echo "<script>
                 alert('username sudah terpakai, update dta user gagal !');
                 document.location.href = 'data-user.php';                
            </script>";
            return false;

        }
    }

    // cek gambar
    if ($gambar != null) {
        $url     = "data-user.php";
        $imgUser = uploadimg($url);
        if ($fotoLama != 'default.png') {
           @unlink('../asset/image' . $fotoLama);

        }
    } else {
        $imgUser = $fotoLama;
    }

    mysqli_query($koneksi, "UPDATE tbl_user SET
                            username   = '$username',
                            fullname   = '$fullname',
                            address    = '$address',
                            level      = '$level',
                            foto       = '$imgUser'
                            WHERE userid = $iduser
                           ");
   
    return mysqli_affected_rows($koneksi);
}