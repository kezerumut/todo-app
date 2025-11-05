<?php
// functions.php'yi çağır (veritabanı, session ve tüm fonksiyonlar için)
require_once 'config/functions.php';

// Bu sayfadaki tüm işlemler için giriş GEREKLİ
require_login();

// Giriş yapan kullanıcının ID'si
$user_id = $_SESSION['user_id'];


// --- YENİ GÖREV EKLEME (POST İsteği) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // index.php'deki formdan gelen hidden input'u kontrol et
    if (isset($_POST['action']) && $_POST['action'] == 'add') {

        $task_title = $_POST['task_title'];

        // functions.php'deki fonksiyonu çağır
        $result = add_task($user_id, $task_title);

        if ($result === true) {
            // Başarılıysa: index.php'ye başarı mesajıyla yönlendir
            header("Location: index.php?status=added");
        } else {
            // Başarısızsa: index.php'ye hata mesajıyla yönlendir
            // urlencode, hata mesajındaki boşluk vb. karakterleri URL'de düzgün taşır
            header("Location: index.php?error=" . urlencode($result));
        }
        exit;
    }
}


// --- GÖREV GÜNCELLEME VEYA SİLME (GET İsteği) ---
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    // URL'deki 'action' parametresini kontrol et (örn: ...?action=delete&...)
    if (isset($_GET['action'])) {

        // ID parametresi zorunlu
        if (!isset($_GET['id'])) {
            header("Location: index.php?error=ID eksik");
            exit;
        }
        $task_id = (int) $_GET['id'];


        // --- SİLME İŞLEMİ ---
        if ($_GET['action'] == 'delete') {

            // functions.php'deki fonksiyonu çağır
            delete_task($task_id, $user_id);

            // index.php'ye başarı mesajıyla yönlendir
            header("Location: index.php?status=deleted");
            exit;
        }

        // --- GÜNCELLEME (TAMAMLAMA/GERİ ALMA) İŞLEMİ ---
        if ($_GET['action'] == 'update') {

            // Status parametresi zorunlu
            if (!isset($_GET['status'])) {
                header("Location: index.php?error=Durum bilgisi eksik");
                exit;
            }
            $new_status = (int) $_GET['status']; // 0 veya 1

            // functions.php'deki fonksiyonu çağır
            update_task_status($task_id, $user_id, $new_status);

            // index.php'ye başarı mesajıyla yönlendir
            header("Location: index.php?status=updated");
            exit;
        }
    }
}

// Eğer hiçbir işlem yapılmadıysa (dosyaya doğrudan girildiyse)
// Ana sayfaya yönlendir
header("Location: index.php");
exit;
?>