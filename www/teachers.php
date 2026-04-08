<?php require_once 'config.php';

$pdo = getDBConnection();
$stmt = $pdo->query("SELECT * FROM teachers ORDER BY position, full_name");
$teachers = $stmt->fetchAll();

$totalTeachers = $pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
$totalProfessors = $pdo->query("SELECT COUNT(*) FROM teachers WHERE position LIKE '%Профессор%'")->fetchColumn();
$totalCandidates = $pdo->query("SELECT COUNT(*) FROM teachers WHERE degree LIKE '%Кандидат%'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Преподаватели - <?= SITE_NAME ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Сброс стилей */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Единый контейнер как на всех страницах */
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        /* Стили для страницы преподавателей */
        .page-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .page-header h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            font-size: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .teachers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }
        
        .teacher-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .teacher-card:hover { transform: translateY(-5px); }
        
        .teacher-photo {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .teacher-photo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }
        
        .teacher-info { padding: 1.5rem; }
        
        .teacher-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        
        .teacher-position {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-bottom: 10px;
        }
        
        .teacher-detail {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 8px 0;
            color: #666;
            font-size: 0.85rem;
        }
        
        .teacher-detail i { width: 20px; color: #764ba2; }
        
        .degree {
            background: #f0f0f0;
            padding: 8px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 0.8rem;
        }

        .btn-details {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 20px;
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.3s;
            text-align: center;
            width: 100%;
        }
        .btn-details:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
            .stats { grid-template-columns: 1fr; }
            .teachers-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="page-container">
        <div class="page-header">
            <h1><i class="fas fa-chalkboard-user"></i> Наши преподаватели</h1>
            <p>Высококвалифицированные специалисты с многолетним опытом</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h3><?= $totalTeachers ?></h3>
                <p>Всего преподавателей</p>
            </div>
            <div class="stat-card">
                <h3><?= $totalProfessors ?></h3>
                <p>Профессоров</p>
            </div>
            <div class="stat-card">
                <h3><?= $totalCandidates ?></h3>
                <p>Кандидатов наук</p>
            </div>
        </div>

        <div class="teachers-grid">
            <?php foreach($teachers as $teacher): ?>
            <div class="teacher-card">
                <div class="teacher-photo">
                    <img src="uploads/teachers/<?= htmlspecialchars($teacher['photo']) ?>?v=<?= time() ?>" 
                         onerror="this.src='https://ui-avatars.com/api/?background=764ba2&color=fff&size=100&name=<?= urlencode($teacher['full_name']) ?>'"
                         alt="Фото преподавателя">
                </div>
                <div class="teacher-info">
                    <div class="teacher-name"><?= htmlspecialchars($teacher['full_name']) ?></div>
                    <div class="teacher-position"><?= htmlspecialchars($teacher['position']) ?></div>
                    <div class="teacher-detail"><i class="fas fa-id-card"></i> <?= htmlspecialchars($teacher['teacher_id']) ?></div>
                    <div class="teacher-detail"><i class="fas fa-envelope"></i> <?= htmlspecialchars($teacher['email']) ?></div>
                    <div class="teacher-detail"><i class="fas fa-phone"></i> <?= htmlspecialchars($teacher['phone']) ?></div>
                    <div class="teacher-detail"><i class="fas fa-building"></i> <?= htmlspecialchars($teacher['department']) ?></div>
                    <div class="teacher-detail"><i class="fas fa-calendar-alt"></i> Стаж: <?= $teacher['experience_years'] ?> лет</div>
                    <div class="degree">
                        <i class="fas fa-graduation-cap"></i> <?= htmlspecialchars($teacher['degree']) ?>
                    </div>
                    <div class="teacher-detail" style="margin-top: 10px;">
                        <i class="fas fa-book-open"></i>
                        <span><?= htmlspecialchars($teacher['specialization']) ?></span>
                    </div>
                    <a href="teacher_view.php?id=<?= $teacher['id'] ?>" class="btn-details">
                        <i class="fas fa-eye"></i> Подробнее
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <div class="page-container">
            <p>&copy; 2024 АПТ Техникум. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>