<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-check2-square"></i> ToDo List
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (is_logged_in()): // Kullanıcı giriş yapmışsa ?>
                    <li class="nav-item">
                        <span class="navbar-text text-white me-3">
                            Hoş geldin, **<?php echo htmlspecialchars($_SESSION['username']); ?>**
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger" href="logout.php">Çıkış Yap</a>
                    </li>
                <?php else: // Kullanıcı giriş yapmamışsa ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Giriş Yap</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="register.php">Kayıt Ol</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>