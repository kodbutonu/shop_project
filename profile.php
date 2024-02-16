<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit(); // Session yoksa buradan çık
}

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
    exit(); // Çıkış yapıldıktan sonra buradan çık
}

// Düzenleme formu gönderildiyse
if (isset($_POST['submit'])) {
    $new_name = mysqli_real_escape_string($conn, $_POST['new_name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['new_email']);

    // Veritabanını güncelle
    mysqli_query($conn, "UPDATE `user_form` SET name = '$new_name', email = '$new_email' WHERE id = '$user_id'") or die('Güncelleme sorgusu başarısız');
}

// Kullanıcı bilgilerini al
$select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('Sorgu başarısız');

if (mysqli_num_rows($select_user) > 0) {
    $fetch_user = mysqli_fetch_assoc($select_user);
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
</head>

<body>
    <div class="container">
        <div class="user-profile">
            <div class="profile-image">
                <!-- Profile Image Here -->
                <img src="images/profile.jpg" alt="Profile Image">
            </div>

            <?php if (!isset($_POST['edit'])) : ?>
                <p> Kullanıcı Adı: <span><?php echo $fetch_user['name']; ?></span> </p>
                <p> E-posta: <span><?php echo $fetch_user['email']; ?></span> </p>

                <div class="flex">
                    <a href="login.php" class="btn">login</a>
                    <a href="register.php" class="btn-success">register</a>
                    <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');" class="btn-danger">logout</a>
                    <form method="post">
                        <input type="submit" name="edit" class="btn" value="edit">
                    </form>
                </div>
            <?php else : ?>
                <div class="edit-form flex-1">
    <form method="post">
        <!-- Diğer form elemanları -->
        <label for="new_name">New Username:</label>
        <input type="text" name="new_name" value="<?php echo $fetch_user['name']; ?>" required>

        <label for="new_email">New Email:</label>
        <input type="email" name="new_email" value="<?php echo $fetch_user['email']; ?>" required>

        <input type="submit" name="submit" class="btn-success" value="Save">
        <a href="profile.php" class="btn-danger">Cancel</a>
    </form>
</div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
