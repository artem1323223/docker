-- Установка кодировки
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Создание базы данных
CREATE DATABASE IF NOT EXISTS apt_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE apt_db;

-- Таблица пользователей (расширенная)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    avatar VARCHAR(255) DEFAULT 'default.jpg',
    role ENUM('user', 'admin') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица сообщений (расширенная)
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    admin_reply TEXT,
    replied_by INT,
    replied_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (replied_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица сессий
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT,
    data TEXT,
    expires_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Вставка тестовых данных
INSERT INTO users (username, email, password, full_name, phone, address, role) VALUES
('admin', 'admin@apt-tech.ru', MD5('admin123'), 'Администратор АПТ Техникум', '+7 (999) 123-45-67', 'Москва, ул. Техническая, д. 1', 'admin'),
('student1', 'ivan@apt-tech.ru', MD5('student123'), 'Иван Петров', '+7 (912) 345-67-89', 'Санкт-Петербург, Невский пр., 10', 'user'),
('student2', 'maria@apt-tech.ru', MD5('student123'), 'Мария Сидорова', '+7 (911) 234-56-78', 'Казань, ул. Баумана, д. 5', 'user');

INSERT INTO messages (user_id, name, email, subject, message, priority) VALUES
(1, 'Иван Петров', 'ivan@apt-tech.ru', 'Вопрос о поступлении', 'Здравствуйте! Хочу узнать условия поступления в АПТ Техникум.', 'high'),
(2, 'Мария Сидорова', 'maria@apt-tech.ru', 'Расписание занятий', 'Когда будет опубликовано расписание на следующий семестр?', 'medium'),
(NULL, 'Алексей Смирнов', 'alex@example.ru', 'Сотрудничество', 'Предлагаю сотрудничество в области IT-образования.', 'high');
