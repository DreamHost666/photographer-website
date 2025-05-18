# Фотографический сайт с админ-панелью

Профессиональный сайт фотографа с функционалом онлайн-записи и управлением портфолио.

## Особенности проекта

✅ Полноценный лендинг фотографа с портфолио  
✅ Админ-панель для управления контентом  
✅ Система онлайн-записи на фотосессии  
✅ Галерея работ с фильтрацией по категориям  
✅ Адаптивный дизайн для всех устройств  

## Технологии

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 7.4+, MySQL
- **Сервер**: Apache (XAMPP)
- **Библиотеки**: Font Awesome, Google Fonts

## Установка и настройка

1. Клонируйте репозиторий:
```bash
git clone https://github.com/DreamHost666/photographer-website.git
```

2. Настройте XAMPP:

*Поместите проект в C:\xampp\htdocs\photographer-website

*Импортируйте БД из файла database/photographer_db.sql

3. Настройте подключение к БД в config.php:
   
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'photographer_db');

4. Установите права на запись:
chmod -R 755 images/photos/


Структура проекта

photographer-website/
├── admin/              # Админ-панель
├── css/                # Стили
├── images/             # Изображения
├── js/                 # JavaScript
├── php/                # PHP скрипты
├── index.html          # Главная страница
└── README.md           # Документация


Скриншоты

Интерфейс управления галереей
![image](https://github.com/user-attachments/assets/948bbf75-0360-450a-b3a3-e5889298f881)
Админ-панель

Онлайн-запись на фотосессию
![image](https://github.com/user-attachments/assets/54acf0c8-c282-4409-b637-2f1c4b485815)
Форма записи
