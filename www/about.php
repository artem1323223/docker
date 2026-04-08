<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О нас - <?= SITE_NAME ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .content {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .content h1 { margin-bottom: 1rem; color: #333; }
        .values {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 2rem 0;
        }
        .value-item {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .value-item i {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 1rem;
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
            .content { padding: 1.5rem; }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="page-container">
        <div class="content">
            <h1>О АПТ Техникуме</h1>
            <p style="margin: 1rem 0; font-size: 1.1rem;">АПТ Техникум - современное образовательное учреждение для подготовки IT-специалистов.</p>
            
            <h2>Наши ценности</h2>
            <div class="values">
                <div class="value-item"><i class="fas fa-rocket"></i><h3>Инновации</h3><p>Современные технологии</p></div>
                <div class="value-item"><i class="fas fa-users"></i><h3>Команда</h3><p>Дружная атмосфера</p></div>
                <div class="value-item"><i class="fas fa-chart-line"></i><h3>Развитие</h3><p>Постоянное совершенствование</p></div>
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