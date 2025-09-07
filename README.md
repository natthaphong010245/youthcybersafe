<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# YOUTH CYBERSAFE

### GMAIL

Username: youthcybersafe1@gmail.com

Password: youthcybersafe123

## คุณสมบัติหลัก

- 🧠 **ระบบประเมินสุขภาพจิต** - ประเมินระดับความเครียด ความวิตกกังวล และภาวะซึมเศร้า
- 🛡️ **ระบบประเมินการกลั่นแกล้งทางไซเบอร์** - ตรวจสอบและประเมินพฤติกรรมการกลั่นแกล้งออนไลน์
- 🔊 **Safe Area** - พื้นที่ปลอดภัยสำหรับบันทึกข้อความเสียง
- 👥 **ระบบผู้ใช้** - รองรับบทบาทต่างๆ ในสถานศึกษา
- 🏫 **ระบบโรงเรียน** - จัดการข้อมูลแยกตามสถานศึกษา

## ข้อกำหนดระบบ

- PHP >= 8.0
- Laravel >= 9.0
- MySQL >= 5.7
- Composer
- Node.js & NPM (สำหรับ frontend assets)

## การติดตั้ง

### 1. Clone โปรเจค
```bash
git clone <repository-url>
cd <project-name>
```

### 2. ติดตั้ง Dependencies
```bash
composer install
npm install
```

### 3. ตั้งค่าสภาพแวดล้อม
```bash
cp .env.example .env
php artisan key:generate
```

### 4. ตั้งค่าฐานข้อมูลใน .env
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```


### 5. ตั้งค่าฐานข้อมูลใน .env
```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:hnIoR3xgy0NnryFeFvz9h4hqoiMCJQ/CCtrpyTAt5IU=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cyberbully
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"


CLOUDINARY_CLOUD_NAME=dibxjfms3
CLOUDINARY_API_KEY=556989218944642
CLOUDINARY_API_SECRET=gwGJ_RDIzTQCGg5QcmGtdjBoNTE


```


### 6. รันการ Migration
```bash
php artisan migrate
```

### 7. Compile Assets
```bash
npm run dev
# หรือสำหรับ production
npm run build
```

### 8. เริ่มเซิร์ฟเวอร์
```bash
php artisan serve
```

## โครงสร้างฐานข้อมูล

### Table: users
เก็บข้อมูลผู้ใช้ระบบ

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| role | VARCHAR(255) | บทบาทของผู้ใช้ |
| role_user | TINYINT(1) | สถานะบทบาทผู้ใช้ |
| school | VARCHAR(255) | ชื่อสถานศึกษา |
| name | VARCHAR(255) | ชื่อ |
| lastname | VARCHAR(255) | นามสกุล |
| username | VARCHAR(255) | ชื่อผู้ใช้ (UNIQUE) |
| password | VARCHAR(255) | รหัสผ่าน (Hashed) |
| created_at | TIMESTAMP | วันที่สร้าง |
| updated_at | TIMESTAMP | วันที่อัปเดต |

```sql
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `role` VARCHAR(255) NOT NULL,
    `role_user` TINYINT(1) NOT NULL DEFAULT 0,
    `school` VARCHAR(255) NULL,
    `name` VARCHAR(255) NOT NULL,
    `lastname` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Table: safe_area
เก็บข้อมูลพื้นที่ปลอดภัย

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| voice_message | JSON | ข้อมูลข้อความเสียง |
| created_at | TIMESTAMP | วันที่สร้าง |
| updated_at | TIMESTAMP | วันที่อัปเดต |

```sql
CREATE TABLE `safe_area` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `voice_message` JSON NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `safe_area_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Table: cyberbullying_assessment
เก็บข้อมูลการประเมินการกลั่นแกล้งทางไซเบอร์

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| assessment_data | JSON | ข้อมูลการประเมิน |
| created_at | TIMESTAMP | วันที่สร้าง |
| updated_at | TIMESTAMP | วันที่อัปเดต |

```sql
CREATE TABLE `cyberbullying_assessment` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `assessment_data` JSON NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `cyberbullying_assessment_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Table: mental_health_assessment
เก็บข้อมูลการประเมินสุขภาพจิต

| Field | Type | Description |
|-------|------|-------------|
| id | BIGINT UNSIGNED | Primary Key |
| stress | JSON | ข้อมูลการประเมินความเครียด |
| anxiety | JSON | ข้อมูลการประเมินความวิตกกังวล |
| depression | JSON | ข้อมูลการประเมินภาวะซึมเศร้า |
| created_at | TIMESTAMP | วันที่สร้าง |
| updated_at | TIMESTAMP | วันที่อัปเดต |

```sql
CREATE TABLE `mental_health_assessment` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `stress` JSON NOT NULL,
    `anxiety` JSON NOT NULL,
    `depression` JSON NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    INDEX `mental_health_assessment_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```


### Table: behavioral_report
เก็บข้อมูลการรายงานพฤติกรรม

```sql
CREATE TABLE `behavioral_report` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `who` varchar(255) NOT NULL,
  `school` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `voice` varchar(255) DEFAULT NULL,
  `image` longtext DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## API Endpoints

### Authentication
- `POST /api/login` - เข้าสู่ระบบ
- `POST /api/logout` - ออกจากระบบ
- `POST /api/register` - สมัครสมาชิก

### Mental Health Assessment
- `GET /api/mental-health-assessments` - ดึงข้อมูลการประเมิน
- `POST /api/mental-health-assessments` - สร้างการประเมินใหม่
- `GET /api/mental-health-assessments/{id}` - ดึงข้อมูลการประเมินตาม ID

### Cyberbullying Assessment
- `GET /api/cyberbullying-assessments` - ดึงข้อมูลการประเมิน
- `POST /api/cyberbullying-assessments` - สร้างการประเมินใหม่
- `GET /api/cyberbullying-assessments/{id}` - ดึงข้อมูลการประเมินตาม ID

### Safe Area
- `GET /api/safe-area` - ดึงข้อมูลพื้นที่ปลอดภัย
- `POST /api/safe-area` - สร้างข้อความใหม่ในพื้นที่ปลอดภัย

## การพัฒนา

### Code Style
โปรเจคนี้ใช้ Laravel coding standards และ PSR-12

### Testing
```bash
# รันการทดสอบ
php artisan test

# รันการทดสอบพร้อม coverage
php artisan test --coverage
```

### Linting
```bash
# PHP CS Fixer
./vendor/bin/php-cs-fixer fix

# PHPStan
./vendor/bin/phpstan analyse
```

## การ Deploy

### สำหรับ Production
```bash
# อัปเดต dependencies
composer install --optimize-autoloader --no-dev

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Compile assets
npm run build
```

## การสนับสนุน

หากมีปัญหาหรือข้อสงสัย สามารถติดต่อได้ผ่าน:
- GitHub Issues
- Email: [your-email@example.com]

## ใบอนุญาต

โปรเจคนี้อยู่ภายใต้ใบอนุญาต [MIT License](https://opensource.org/licenses/MIT)

## ผู้พัฒนา

- **ชื่อทีมพัฒนา** - พัฒนาเริ่มต้น
- ดูรายชื่อผู้ร่วมพัฒนาทั้งหมดได้ที่ [contributors](https://github.com/your-repo/contributors)

## เวอร์ชัน

ดูประวัติการเปลี่ยนแปลงได้ที่ [CHANGELOG.md](CHANGELOG.md)

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.
