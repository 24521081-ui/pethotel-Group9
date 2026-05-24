## 🚀 Hướng dẫn cài đặt tại Local

Nếu bạn muốn chạy thử dự án này trên máy tính cá nhân, hãy làm theo các bước sau:

**1. Cài phần mềm cần thiết:**

- PHP 8.2 trở lên
- Composer
- Node.js và npm
- XAMPP hoặc Laragon để có MySQL/MariaDB
- Bật extension `pdo_mysql` trong PHP nếu máy chưa bật

> Project này đang dùng trigger và constraint theo cú pháp MySQL/MariaDB, không nên để `DB_CONNECTION=sqlite` khi chạy migration.

**2. Clone dự án về máy:**
```bash
git clone https://github.com/Nihe211/pet-hotel.git
cd pet-hotel
```
**3. Cài đặt các thư viện PHP:**

```bash
composer install
```

**4. Cài đặt và build frontend assets:**

```bash
npm install
npm run build
```

**5. Cấu hình môi trường (Database):**

Copy file .env.example thành file .env:

```bash
cp .env.example .env
```

Trên Windows PowerShell có thể dùng:

```powershell
Copy-Item .env.example .env
```

Tạo database tên `pethotel_db` trong phpMyAdmin hoặc MySQL, sau đó kiểm tra file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pethotel_db
DB_USERNAME=root
DB_PASSWORD=
```

**6. Khởi tạo key bảo mật và tạo bảng database:**

```bash
php artisan key:generate
php artisan config:clear
php artisan migrate --seed
```

**7. Chạy server Laravel:**

```bash
php artisan serve
```

Truy cập vào http://127.0.0.1:8000 trên trình duyệt để sử dụng hệ thống.
---
