<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link rel="stylesheet" href="../css/style.css"> </head>
<body>
    <div class="container" style="margin-top: 50px;">
        <form action="proses_login_admin.php" method="POST" class="registration-form">
            <h2>Login Panel Admin</h2>
            <div class="form-group">
                <label for="kode_anggota">Username Admin</label>
                <input type="text" name="kode_anggota" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="button">Login</button>
        </form>
    </div>
</body>
</html>