<?php
require_once 'config.php';

if (!isAdmin()) {
    exit('Доступ запрещен');
}

$pdo = getDBConnection();
$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;

if ($type === 'student') {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    
    if ($item):
?>
<style>
    .detail-view { font-family: Arial, sans-serif; }
    .detail-view h3 { color: #667eea; margin-bottom: 20px; }
    .detail-row { margin: 10px 0; padding: 8px; border-bottom: 1px solid #eee; }
    .detail-label { font-weight: bold; width: 150px; display: inline-block; }
</style>
<div class="detail-view">
    <h3><i class="fas fa-user-graduate"></i> Подробная информация о студенте</h3>
    <div class="detail-row"><span class="detail-label">ФИО:</span> <?= htmlspecialchars($item['full_name']) ?></div>
    <div class="detail-row"><span class="detail-label">Студбилет:</span> <?= htmlspecialchars($item['student_id']) ?></div>
    <div class="detail-row"><span class="detail-label">Email:</span> <?= htmlspecialchars($item['email']) ?></div>
    <div class="detail-row"><span class="detail-label">Телефон:</span> <?= htmlspecialchars($item['phone']) ?></div>
    <div class="detail-row"><span class="detail-label">Дата рождения:</span> <?= $item['birth_date'] ?></div>
    <div class="detail-row"><span class="detail-label">Адрес:</span> <?= nl2br(htmlspecialchars($item['address'])) ?></div>
    <div class="detail-row"><span class="detail-label">Группа:</span> <?= htmlspecialchars($item['group_name']) ?></div>
    <div class="detail-row"><span class="detail-label">Курс:</span> <?= $item['course'] ?></div>
    <div class="detail-row"><span class="detail-label">Специальность:</span> <?= htmlspecialchars($item['specialty']) ?></div>
    <div class="detail-row"><span class="detail-label">Год поступления:</span> <?= $item['enrollment_year'] ?></div>
    <div class="detail-row"><span class="detail-label">Дата регистрации:</span> <?= $item['created_at'] ?></div>
</div>
<?php
    endif;
} elseif ($type === 'teacher') {
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    
    if ($item):
?>
<style>
    .detail-view { font-family: Arial, sans-serif; }
    .detail-view h3 { color: #764ba2; margin-bottom: 20px; }
    .detail-row { margin: 10px 0; padding: 8px; border-bottom: 1px solid #eee; }
    .detail-label { font-weight: bold; width: 150px; display: inline-block; }
</style>
<div class="detail-view">
    <h3><i class="fas fa-chalkboard-user"></i> Подробная информация о преподавателе</h3>
    <div class="detail-row"><span class="detail-label">ФИО:</span> <?= htmlspecialchars($item['full_name']) ?></div>
    <div class="detail-row"><span class="detail-label">Табельный номер:</span> <?= htmlspecialchars($item['teacher_id']) ?></div>
    <div class="detail-row"><span class="detail-label">Email:</span> <?= htmlspecialchars($item['email']) ?></div>
    <div class="detail-row"><span class="detail-label">Телефон:</span> <?= htmlspecialchars($item['phone']) ?></div>
    <div class="detail-row"><span class="detail-label">Дата рождения:</span> <?= $item['birth_date'] ?></div>
    <div class="detail-row"><span class="detail-label">Адрес:</span> <?= nl2br(htmlspecialchars($item['address'])) ?></div>
    <div class="detail-row"><span class="detail-label">Должность:</span> <?= htmlspecialchars($item['position']) ?></div>
    <div class="detail-row"><span class="detail-label">Кафедра:</span> <?= htmlspecialchars($item['department']) ?></div>
    <div class="detail-row"><span class="detail-label">Ученая степень:</span> <?= htmlspecialchars($item['degree']) ?></div>
    <div class="detail-row"><span class="detail-label">Стаж работы:</span> <?= $item['experience_years'] ?> лет</div>
    <div class="detail-row"><span class="detail-label">Специализация:</span> <?= nl2br(htmlspecialchars($item['specialization'])) ?></div>
    <div class="detail-row"><span class="detail-label">Дата регистрации:</span> <?= $item['created_at'] ?></div>
</div>
<?php
    endif;
}
?>