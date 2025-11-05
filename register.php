<?php
// header.php'yi çağır (functions.php'yi de içinde çağırır)
require_once 'partials/header.php';

// Eğer zaten giriş yapmışsa, ana sayfaya yönlendir
if (is_logged_in()) {
    header("Location: index.php");
    exit;
}

$error = ''; // Hata mesajı için boş değişken

// Sayfa POST isteği aldıysa (form gönderildiyse)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Şifreler uyuşuyor mu kontrol et
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $error = "Şifreler uyuşmuyor!";
    } else {
        // register fonksiyonunu çağır (functions.php'den)
        $result = register($_POST);

        if ($result === true) {
            // Kayıt başarılı, login sayfasına yönlendir
            header("Location: login.php?success=register");
            exit;
        } else {
            // Fonksiyondan gelen hata mesajını göster
            $error = $result;
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="text-center mb-0">Hesap Oluştur</h3>
            </div>
            <div class="card-body">

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Kullanıcı Adı</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-posta Adresi</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Şifre</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Şifre (Tekrar)</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                            required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Kayıt Ol</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                Zaten bir hesabın var mı? <a href="login.php">Giriş Yap</a>
            </div>
        </div>
    </div>
</div>

<?php
// footer.php'yi çağır
require_once 'partials/footer.php';
?>