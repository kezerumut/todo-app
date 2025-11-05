<?php
date_default_timezone_set('Europe/Istanbul');

// 1. Veritabanı Ayarları
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'todo_app'); // Veritabanı adını değiştirdik

// 2. Veritabanı Bağlantısı ($conn)
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 3. Oturumu Başlatma
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 4. Güvenlik Fonksiyonu (Senin istediğin gibi)
function clean($data)
{
    global $conn;
    // Not: htmlspecialchars veritabanına yazarken değil, ekrana basarken kullanılmalı.
    // Ama senin yapına sadık kalmak için bu şekilde bırakıyorum.
    return $conn->real_escape_string(htmlspecialchars(trim($data)));
}

// 5. Oturum Kontrol Fonksiyonları
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

// 6. Kullanıcı İşlem Fonksiyonları

function login($email, $password)
{
    global $conn;
    $email = clean($email);
    $sql = "SELECT * FROM users WHERE email='$email'"; // 'user' tablosunu 'users' yaptık

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Şifre kontrolü
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username']; // 'full_name' yerine 'username'
            return true;
        }
    }
    return false; // E-posta veya şifre yanlış
}

function logout()
{
    session_destroy();
    header("Location: login.php");
    exit;
}

function register($data)
{
    global $conn;

    // Verileri al ve temizle
    $username = clean($data["username"]);
    $email = clean($data["email"]);
    $password = $data["password"];

    // E-posta veya kullanıcı adı daha önce alınmış mı?
    $check = "SELECT * FROM users WHERE email='$email' OR username='$username'";
    if ($conn->query($check)->num_rows > 0) {
        return "Bu e-posta veya kullanıcı adı zaten kayıtlı.";
    }

    // Şifreyi hash'le
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);

    // SQL sorgusunu oluştur
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$pass_hash')";

    if ($conn->query($sql)) {
        return true;
    }
    return "Kayıt işlemi sırasında bir hata oluştu: " . $conn->error;
}


// 7. Görev (Task) İşlem Fonksiyonları

function add_task($user_id, $task_title)
{
    global $conn;
    $user_id = (int) $user_id;
    $task_title = clean($task_title);

    if (empty($task_title)) {
        return "Görev başlığı boş olamaz.";
    }

    $sql = "INSERT INTO tasks (user_id, task_title) VALUES ($user_id, '$task_title')";

    if ($conn->query($sql)) {
        return true;
    }
    return "Görev eklenirken bir hata oluştu: " . $conn->error;
}

function get_tasks($user_id)
{
    global $conn;
    $user_id = (int) $user_id;

    // Önce tamamlanmamış (status=0) görevleri, sonra tamamlanmış (status=1) görevleri getir
    $sql = "SELECT * FROM tasks WHERE user_id = $user_id ORDER BY status ASC, created_at DESC";

    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function update_task_status($task_id, $user_id, $new_status)
{
    global $conn;
    $task_id = (int) $task_id;
    $user_id = (int) $user_id;
    $new_status = (int) $new_status; // 0 veya 1

    // Güvenlik: Kullanıcı sadece kendi görevini güncelleyebilmeli
    $sql = "UPDATE tasks SET status = $new_status WHERE id = $task_id AND user_id = $user_id";
    $conn->query($sql);

    // Etkilenen satır sayısını kontrol et
    return $conn->affected_rows > 0;
}

function delete_task($task_id, $user_id)
{
    global $conn;
    $task_id = (int) $task_id;
    $user_id = (int) $user_id;

    // Güvenlik: Kullanıcı sadece kendi görevini silebilir
    $sql = "DELETE FROM tasks WHERE id = $task_id AND user_id = $user_id";
    $conn->query($sql);

    return $conn->affected_rows > 0;
}

?>