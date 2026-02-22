
-- Создание базы данных
CREATE DATABASE IF NOT EXISTS artgorka_testtask;
USE artgorka_testtask;

-- Таблица проэктов
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    platform_type TINYINT NOT NULL,
    status TINYINT NOT NULL,
    description TEXT NOT NULL,
    created_at timestamp NOT NULL,
    updated_at timestamp NOT NULL
);

CREATE INDEX idx_projects_status_platform
ON projects(status, platform_type);

CREATE INDEX idx_projects_created_at
ON projects(created_at);

INSERT INTO projects (name, url, platform_type, status, description, created_at, updated_at)
VALUES
("Яндекс", "https://yandex.ru", 4, 2, "Поисковая система", "2026-01-01 00:00:00", "2026-02-18 00:00:00"),
("1С-Битрикс", "https://www.1c-bitrix.ru/", 4, 2, "Сервисы и инструменты для бизнеса", "2026-01-01 00:00:00", "2026-02-18 00:00:00"),
("Шатура", "https://www.shatura.com/", 2, 2, "Интернет-магазин мебели", "2026-01-01 00:00:00", "2026-02-18 00:00:00"),
("LETTO", "https://www.letto.ru/", 2, 2, "LETTO интернет-магазин", "2026-01-01 00:00:00", "2026-02-18 00:00:00"),
("Обои", "https://oboi1.ru/", 2, 1, "Обои №1", "2026-01-01 00:00:00", "2026-02-18 00:00:00"),
("Sony Music", "https://www.sonymusic.com/", 1, 2, "На сайте размещаются новости о релизах артистов, пресс-релизы о сделках, анонсы концертов, информация о лейблах.", "2026-01-01 00:00:00", "2026-02-18 00:00:00"),
("TechCrunch", "https://techcrunch.com/", 1, 2, "Онлайн-издание, специализирующееся на подробном освещении стартапов, венчурных инвестиций, инноваций и последних новостей технологической индустрии.", "2026-01-01 00:00:00", "2026-02-18 00:00:00"),
("The Walt Disney Company", "https://thewaltdisneycompany.com/", 1, 2, "официальный корпоративный ресурс, освещающий деятельность одного из крупнейших в мире медиаконгломератов — The Walt Disney Company. Он ориентирован на инвесторов, партнеров и соискателей, предоставляя информацию о бизнесе, новостях, стратегии, вакансиях и структуре компании, включающей студии (Pixar, Marvel, Lucasfilm), парки, ESPN и Disney+.", "2026-01-01 00:00:00", "2026-02-18 00:00:00"),
("PlayStation.Blog", "https://blog.playstation.com/", 1, 2, "PlayStation.Blog – Official PlayStation Blog for news and video updates on PlayStation, PS5, PS4, PS VR, PlayStation Plus and more.", "2026-01-01 00:00:00", "2026-02-18 00:00:00"),
("The Mozilla Blog", "https://blog.mozilla.org/en/", 1, 2, "The Mozilla Blog", "2026-01-01 00:00:00", "2026-02-18 00:00:00"),
("The Rolling Stones", "https://rollingstones.com/", 1, 2, "The Rolling Stones | Official Website", "2026-01-01 00:00:00", "2026-02-18 00:00:00");
