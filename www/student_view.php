<?php require_once 'config.php';

$student_id = $_GET['id'] ?? 0;

if (!$student_id) {
    header('Location: students.php');
    exit;
}

$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    header('Location: students.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($student['full_name']) ?> - Студент АПТ Техникум</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .page-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .back-btn:hover {
            background: #667eea;
            color: white;
        }
        .profile-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
            text-align: center;
            position: relative;
        }
        .profile-photo {
            width: 150px;
            height: 150px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-header h1 {
            color: white;
            margin-top: 20px;
            font-size: 1.8rem;
        }
        .profile-header .badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            margin-top: 10px;
            color: white;
        }
        .profile-body {
            padding: 30px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-section h3 {
            color: #667eea;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .info-item i {
            width: 30px;
            font-size: 1.2rem;
            color: #667eea;
        }
        .info-item .label {
            font-weight: bold;
            color: #333;
            min-width: 100px;
        }
        .info-item .value {
            color: #666;
        }
        footer {
            background: rgba(0,0,0,0.8);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }
        @media (max-width: 768px) {
            .page-container { padding: 20px 15px; }
            .info-grid { grid-template-columns: 1fr; }
            .info-item { flex-direction: column; text-align: center; }
            .profile-header h1 { font-size: 1.3rem; }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="page-container">
        <a href="students.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Назад к студентам
        </a>

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-photo">
                    <img src="uploads/students/<?= htmlspecialchars($student['photo']) ?>?v=<?= time() ?>" 
                         onerror="this.src='https://ui-avatars.com/api/?background=667eea&color=fff&size=150&name=<?= urlencode($student['full_name']) ?>'"
                         alt="Фото студента">
                </div>
                <h1><?= htmlspecialchars($student['full_name']) ?></h1>
                <div class="badge">Студент <?= htmlspecialchars($student['group_name']) ?></div>
            </div>

            <div class="profile-body">
                <div class="info-section">
                    <h3><i class="fas fa-user-graduate"></i> Личная информация</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <i class="fas fa-id-card"></i>
                            <span class="label">Студенческий билет:</span>
                            <span class="value"><?= htmlspecialchars($student['student_id']) ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span class="label">Email:</span>
                            <span class="value"><?= htmlspecialchars($student['email']) ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <span class="label">Телефон:</span>
                            <span class="value"><?= htmlspecialchars($student['phone'] ?: 'Не указан') ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span class="label">Дата рождения:</span>
                            <span class="value"><?= date('d.m.Y', strtotime($student['birth_date'])) ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="label">Адрес:</span>
                            <span class="value"><?= nl2br(htmlspecialchars($student['address'] ?: 'Не указан')) ?></span>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <h3><i class="fas fa-graduation-cap"></i> Учебная информация</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <i class="fas fa-users"></i>
                            <span class="label">Группа:</span>
                            <span class="value"><?= htmlspecialchars($student['group_name']) ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-chalkboard-user"></i>
                            <span class="label">Курс:</span>
                            <span class="value"><?= $student['course'] ?> курс</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-laptop-code"></i>
                            <span class="label">Специальность:</span>
                            <span class="value"><?= htmlspecialchars($student['specialty']) ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar-week"></i>
                            <span class="label">Год поступления:</span>
                            <span class="value"><?= $student['enrollment_year'] ?></span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <span class="label">Дата регистрации:</span>
                            <span class="value"><?= date('d.m.Y H:i', strtotime($student['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="page-container">
            <p>&copy; 2024 АПТ Техникум. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>