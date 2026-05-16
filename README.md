## 🚀 Hướng dẫn cài đặt tại Local

Nếu bạn muốn chạy thử dự án này trên máy tính cá nhân, hãy làm theo các bước sau:

**1. Clone dự án về máy:**
```bash
git clone https://github.com/Nihe211/pet-hotel.git
cd ten-repo-cua-ban
```
**2. Cài đặt các thư viện PHP:**

```bash
composer install
```
**3. Cấu hình môi trường (Database):**

Copy file .env.example thành file .env:

```bash
cp .env.example .env
Mở file .env lên và điền thông tin Database ở máy bạn (ví dụ dùng XAMPP):

Đoạn mã
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=petholtel_db
DB_USERNAME=root
DB_PASSWORD=
```
**4. Khởi tạo Key bảo mật và Tạo bảng Database:**

```bash
php artisan key:generate
php artisan migrate --seed
```
**5. Chạy server Laravel:**

```bash
php artisan serve
Truy cập vào http://127.0.0.1:8000 trên trình duyệt để sử dụng hệ thống.
```
---
