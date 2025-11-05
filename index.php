<?php
// Header'ı çağır (config/functions.php'yi otomatik olarak dahil eder)
require_once 'partials/header.php';

// Bu sayfayı görmek için giriş yapmak ZORUNLU
require_login();

// Mevcut kullanıcının ID'sini al
$user_id = $_SESSION['user_id'];

// Bu kullanıcıya ait tüm görevleri veritabanından çek
// Bu fonksiyonu functions.php'de tanımlamıştık
$tasks = get_tasks($user_id);

?>

<div class="row mb-3">
    <div class="col">
        <h2 class="h4">Görevlerim</h2>
    </div>
    <div class="col text-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
            <i class="bi bi-plus-lg"></i> Yeni Görev Ekle
        </button>
    </div>
</div>

<?php if (isset($_GET['status'])): ?>
    <?php if ($_GET['status'] == 'added'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Yeni görev başarıyla eklendi.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($_GET['status'] == 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Görev durumu güncellendi.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($_GET['status'] == 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Görev başarıyla silindi.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Hata!</strong> <?php echo htmlspecialchars($_GET['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


<div class="card shadow-sm">
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <?php if (empty($tasks)): ?>
                <li class="list-group-item text-center text-muted">
                    Henüz eklenmiş bir göreviniz bulunmuyor.
                </li>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>

                    <li
                        class="list-group-item d-flex justify-content-between align-items-center <?php echo ($task['status'] == 1) ? 'bg-success-subtle' : ''; ?>">

                        <span class="<?php echo ($task['status'] == 1) ? 'task-completed' : ''; ?>">

                            <?php
                            // GÖREV BAŞLIĞI
                            echo $task['task_title'];
                            ?>

                        </span>

                        <div class="btn-group" role="group">
                            <?php if ($task['status'] == 0): // Görev bekliyorsa ?>
                                <a href="task_actions.php?action=update&id=<?php echo $task['id']; ?>&status=1"
                                    class="btn btn-outline-success btn-sm" title="Tamamla">
                                    <i class="bi bi-check-lg"></i>
                                </a>
                            <?php else: // Görev tamamlanmışsa ?>
                                <a href="task_actions.php?action=update&id=<?php echo $task['id']; ?>&status=0"
                                    class="btn btn-outline-warning btn-sm" title="Geri Al">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </a>
                            <?php endif; ?>

                            <a href="task_actions.php?action=delete&id=<?php echo $task['id']; ?>"
                                class="btn btn-outline-danger btn-sm" title="Sil"
                                onclick="return confirm('Bu görevi silmek istediğinize emin misiniz?');">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>


<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Yeni Görev Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="task_actions.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="task_title" class="form-label">Görev Başlığı</label>
                        <input type="text" class="form-control" id="task_title" name="task_title"
                            placeholder="Örn: Proje raporunu hazırla" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-primary">Görevi Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
// Footer'ı çağır
require_once 'partials/footer.php';
?>