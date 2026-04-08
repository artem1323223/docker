<?php 
require_once 'config.php';

$pdo = getDBConnection();

// Получение всех сообщений
$stmt = $pdo->query("
    SELECT m.*, u.full_name as user_name 
    FROM messages m 
    LEFT JOIN users u ON m.user_id = u.id 
    ORDER BY m.created_at DESC
");
$messages = $stmt->fetchAll();

// Обработка ответа администратора
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'])) {
    if (!isAdmin()) {
        header('Location: login.php');
        exit;
    }
    
    $message_id = $_POST['message_id'];
    $admin_reply = trim($_POST['admin_reply']);
    
    if (!empty($admin_reply)) {
        $stmt = $pdo->prepare("UPDATE messages SET admin_reply = ?, status = 'replied', replied_by = ?, replied_at = NOW() WHERE id = ?");
        $stmt->execute([$admin_reply, $_SESSION['user_id'], $message_id]);
        header('Location: messages.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сообщения - <?= SITE_NAME ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .messages-header {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: center;
        }
        .message-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
            flex-wrap: wrap;
            gap: 10px;
        }
        .priority-high { border-left: 4px solid #e74c3c; }
        .priority-medium { border-left: 4px solid #f39c12; }
        .priority-low { border-left: 4px solid #27ae60; }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        .badge-high { background: #e74c3c; color: white; }
        .badge-medium { background: #f39c12; color: white; }
        .badge-low { background: #27ae60; color: white; }
        .reply-form {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        .reply-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 10px 0;
            font-family: inherit;
        }
        .btn-reply {
            background: #667eea;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-reply:hover { background: #5a67d8; }
        .admin-reply {
            background: #f0f0f0;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        footer {
            background: rgba(0,0,0,0.8);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }
        @media (max-width: 768px) {
            .message-header { flex-direction: column; }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <div class="messages-header">
            <h1><i class="fas fa-envelope"></i> Все сообщения</h1>
            <p>Здесь отображаются все обращения пользователей</p>
        </div>

        <?php if (empty($messages)): ?>
            <div class="message-card" style="text-align: center;">
                <p>Пока нет сообщений</p>
            </div>
        <?php else: ?>
            <?php foreach($messages as $msg): ?>
            <div class="message-card priority-<?= $msg['priority'] ?>">
                <div class="message-header">
                    <div>
                        <strong><?= htmlspecialchars($msg['name'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <?php if ($msg['user_name']): ?>
                            <span style="color: #667eea;">(<?= htmlspecialchars($msg['user_name'], ENT_QUOTES, 'UTF-8') ?>)</span>
                        <?php endif; ?>
                    </div>
                    <div>
                        <span class="badge badge-<?= $msg['priority'] ?>"><?= $msg['priority'] ?></span>
                        <small><?= $msg['created_at'] ?></small>
                    </div>
                </div>
                <div><strong>Тема:</strong> <?= htmlspecialchars($msg['subject'], ENT_QUOTES, 'UTF-8') ?></div>
                <div style="margin-top: 10px;"><strong>Сообщение:</strong></div>
                <div style="margin-top: 5px;"><?= nl2br(htmlspecialchars($msg['message'], ENT_QUOTES, 'UTF-8')) ?></div>
                
                <?php if ($msg['admin_reply']): ?>
                <div class="admin-reply">
                    <strong><i class="fas fa-reply"></i> Ответ администратора:</strong>
                    <div style="margin-top: 5px;"><?= nl2br(htmlspecialchars($msg['admin_reply'], ENT_QUOTES, 'UTF-8')) ?></div>
                    <small>Отвечено: <?= $msg['replied_at'] ?></small>
                </div>
                <?php endif; ?>
                
                <?php if (isAdmin() && !$msg['admin_reply']): ?>
                <div class="reply-form">
                    <form method="POST">
                        <input type="hidden" name="message_id" value="<?= $msg['id'] ?>">
                        <textarea name="admin_reply" rows="3" placeholder="Напишите ответ..."></textarea>
                        <button type="submit" name="reply" class="btn-reply">Ответить</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2024 АПТ Техникум. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>