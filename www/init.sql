-- Установка кодировки
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Удаляем старую базу и создаем новую
DROP DATABASE IF EXISTS apt_db;
CREATE DATABASE apt_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE apt_db;

-- =====================================================
-- ТАБЛИЦА 1: СТУДЕНТЫ (9 полей + фото)
-- =====================================================
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID студента',
    student_id VARCHAR(20) NOT NULL UNIQUE COMMENT 'Номер студенческого билета',
    full_name VARCHAR(100) NOT NULL COMMENT 'Полное имя',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Email',
    phone VARCHAR(20) COMMENT 'Телефон',
    birth_date DATE COMMENT 'Дата рождения',
    address TEXT COMMENT 'Адрес проживания',
    group_name VARCHAR(50) NOT NULL COMMENT 'Название группы',
    course INT DEFAULT 1 COMMENT 'Курс (1-4)',
    specialty VARCHAR(100) NOT NULL COMMENT 'Специальность',
    photo VARCHAR(255) DEFAULT 'default_student.jpg' COMMENT 'Фото студента',
    enrollment_year YEAR COMMENT 'Год поступления',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ТАБЛИЦА 2: ПРЕПОДАВАТЕЛИ (9 полей + фото)
-- =====================================================
CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID преподавателя',
    teacher_id VARCHAR(20) NOT NULL UNIQUE COMMENT 'Табельный номер',
    full_name VARCHAR(100) NOT NULL COMMENT 'Полное имя',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Email',
    phone VARCHAR(20) COMMENT 'Телефон',
    birth_date DATE COMMENT 'Дата рождения',
    address TEXT COMMENT 'Адрес проживания',
    position VARCHAR(100) NOT NULL COMMENT 'Должность',
    department VARCHAR(100) NOT NULL COMMENT 'Кафедра',
    degree VARCHAR(100) COMMENT 'Ученая степень (кандидат/доктор наук)',
    experience_years INT DEFAULT 0 COMMENT 'Стаж работы (лет)',
    photo VARCHAR(255) DEFAULT 'default_teacher.jpg' COMMENT 'Фото преподавателя',
    specialization TEXT COMMENT 'Специализация/предметы',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ТАБЛИЦА ПОЛЬЗОВАТЕЛЕЙ ДЛЯ АВТОРИЗАЦИИ
-- =====================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    user_role ENUM('user', 'admin') DEFAULT 'user',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ТАБЛИЦА СООБЩЕНИЙ
-- =====================================================
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    admin_reply TEXT,
    replied_by INT,
    replied_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- ТЕСТОВЫЕ ДАННЫЕ - СТУДЕНТЫ
-- =====================================================
INSERT INTO students (student_id, full_name, email, phone, birth_date, address, group_name, course, specialty, photo, enrollment_year) VALUES
('ST-2024-001', 'Иванов Иван Иванович', 'ivan.ivanov@student.apt.ru', '+7 (912) 345-67-89', '2005-05-15', 'г. Москва, ул. Студенческая, д. 15, кв. 45', 'П-41', 4, 'Программная инженерия', 'ivan_photo.jpg', 2024),
('ST-2024-002', 'Петрова Анна Сергеевна', 'anna.petrova@student.apt.ru', '+7 (911) 234-56-78', '2006-08-22', 'г. Москва, пр. Вернадского, д. 10, кв. 120', 'П-31', 3, 'Прикладная информатика', 'anna_photo.jpg', 2024),
('ST-2024-003', 'Сидоров Алексей Дмитриевич', 'alexey.sidorov@student.apt.ru', '+7 (916) 123-45-67', '2005-12-10', 'г. Москва, ул. Академическая, д. 8, кв. 78', 'ИС-21', 2, 'Информационные системы', 'alexey_photo.jpg', 2024),
('ST-2024-004', 'Козлова Елена Владимировна', 'elena.kozlova@student.apt.ru', '+7 (903) 987-65-43', '2007-03-28', 'г. Москва, ул. Молодежная, д. 25, кв. 10', 'П-41', 4, 'Программная инженерия', 'elena_photo.jpg', 2024),
('ST-2024-005', 'Морозов Дмитрий Петрович', 'dmitry.morozov@student.apt.ru', '+7 (915) 456-78-90', '2006-07-19', 'г. Москва, ул. Техническая, д. 5, кв. 32', 'ИС-21', 2, 'Информационные системы', 'dmitry_photo.jpg', 2024),
('ST-2024-006', 'Соколова Мария Андреевна', 'maria.sokolova@student.apt.ru', '+7 (909) 111-22-33', '2005-11-03', 'г. Москва, ул. Ленина, д. 50, кв. 15', 'П-31', 3, 'Прикладная информатика', 'maria_photo.jpg', 2024),
('ST-2024-007', 'Павлов Константин Викторович', 'konstantin.pavlov@student.apt.ru', '+7 (917) 444-55-66', '2006-09-25', 'г. Москва, ул. Гагарина, д. 12, кв. 8', 'П-41', 4, 'Программная инженерия', 'konstantin_photo.jpg', 2024);

-- =====================================================
-- ТЕСТОВЫЕ ДАННЫЕ - ПРЕПОДАВАТЕЛИ
-- =====================================================
INSERT INTO teachers (teacher_id, full_name, email, phone, birth_date, address, position, department, degree, experience_years, photo, specialization) VALUES
('TCH-001', 'Профессор Сергей Викторович Смирнов', 's.smirnov@apt.ru', '+7 (495) 123-45-67', '1975-04-12', 'г. Москва, ул. Профессорская, д. 1', 'Профессор', 'Кафедра программирования', 'Доктор технических наук', 25, 'smirnov_photo.jpg', 'Java, Python, Алгоритмы'),
('TCH-002', 'Доцент Екатерина Андреевна Лебедева', 'e.lebedeva@apt.ru', '+7 (495) 234-56-78', '1980-08-25', 'г. Москва, ул. Академическая, д. 5', 'Доцент', 'Кафедра баз данных', 'Кандидат физико-математических наук', 15, 'lebedeva_photo.jpg', 'SQL, Базы данных, Big Data'),
('TCH-003', 'Старший преподаватель Михаил Дмитриевич Федоров', 'm.fedorov@apt.ru', '+7 (495) 345-67-89', '1985-03-18', 'г. Москва, ул. Университетская, д. 10', 'Старший преподаватель', 'Кафедра веб-технологий', 'Кандидат технических наук', 10, 'fedorov_photo.jpg', 'PHP, JavaScript, Laravel, React'),
('TCH-004', 'Преподаватель Ольга Николаевна Новикова', 'o.novikova@apt.ru', '+7 (495) 456-78-90', '1988-11-30', 'г. Москва, ул. Педагогическая, д. 8', 'Преподаватель', 'Кафедра математики', 'Кандидат физико-математических наук', 8, 'novikova_photo.jpg', 'Высшая математика, Дискретная математика'),
('TCH-005', 'Преподаватель Андрей Сергеевич Кузнецов', 'a.kuznetsov@apt.ru', '+7 (495) 567-89-01', '1990-06-22', 'г. Москва, ул. Молодежная, д. 20', 'Преподаватель', 'Кафедра компьютерных сетей', 'Магистр технических наук', 6, 'kuznetsov_photo.jpg', 'Компьютерные сети, Безопасность, Linux'),
('TCH-006', 'Доцент Татьяна Владимировна Морозова', 't.morozova@apt.ru', '+7 (495) 678-90-12', '1978-09-14', 'г. Москва, ул. Заречная, д. 3', 'Доцент', 'Кафедра информационной безопасности', 'Кандидат технических наук', 18, 'morozova_photo.jpg', 'Криптография, Защита информации'),
('TCH-007', 'Профессор Игорь Александрович Васильев', 'i.vasiliev@apt.ru', '+7 (495) 789-01-23', '1972-12-05', 'г. Москва, ул. Научная, д. 7', 'Профессор', 'Кафедра искусственного интеллекта', 'Доктор технических наук', 30, 'vasiliev_photo.jpg', 'Искусственный интеллект, Машинное обучение');

-- =====================================================
-- ДОБАВЛЯЕМ ПОЛЬЗОВАТЕЛЕЙ ДЛЯ АВТОРИЗАЦИИ
-- =====================================================
INSERT INTO users (username, email, password, full_name, user_role) VALUES
('admin', 'admin@apt.ru', MD5('admin123'), 'Администратор Системы', 'admin'),
('ivanov', 'ivan.ivanov@student.apt.ru', MD5('student123'), 'Иван Иванов', 'user'),
('petrova', 'anna.petrova@student.apt.ru', MD5('student123'), 'Анна Петрова', 'user');

-- =====================================================
-- ПРОВЕРКА
-- =====================================================
SELECT 'База данных успешно создана!' as Status;
SELECT COUNT(*) as Students FROM students;
SELECT COUNT(*) as Teachers FROM teachers;