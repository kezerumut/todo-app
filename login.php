<?php
// header.php'yi çağır (functions.php'yi de içinde çağırır)
require_once 'partials/header.php';

// Eğer zaten giriş yapmışsa, ana sayfaya yönlendir
if (is_logged_in()) {
    header("Location: index.php");
    exit;
}

$error = '';

// Form gönderildiyse
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // login fonksiyonunu çağır (functions.php'den)
    if (login($email, $password)) {
        // Giriş başarılı, ana sayfaya (index.php) yönlendir
        header("Location: index.php");
        exit;
    } else {
        $error = "E-posta veya şifre hatalı!";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h3 class="text-center mb-0">Giriş Yap</h3>
            </div>
            <div class="card-body">

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if (isset($_GET['success']) && $_GET['success'] == 'register'): ?>
                    <div class="alert alert-success">
                        Kayıt başarılı! Lütfen giriş yapın.
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-posta Adresi</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Şifre</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Giriş Yap</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                Hesabın yok mu? <a href="register.php">Kayıt Ol</a>
            </div>
        </div>
    </div>
</div>

<?php
// footer.php'yi çağır
require_once 'partials/footer.php';
?>