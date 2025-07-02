# coachtech フリマアプリ

## 環境構築

### Dockerビルド
1. `git clone git@github.com:coachtech-material/laravel-docker-template.git`
2. `docker-compose up -d --build`

### Laravel環境構築
1. `docker compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. `.env`に以下の環境変数を追加

\`\`\`
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="${APP_NAME}"
\`\`\`

5. `php artisan key:generate`
6. `php artisan migrate`
7. `php artisan db:seed`
8. `php artisan storage:link`

### 開発環境
- 動作確認用URL：http://localhost/
- ユーザー登録：http://localhost/register
- phpMyAdmin：http://localhost:8080/

## 使用技術(実行環境)
- PHP 8.1.0
- Laravel 8.83.8
- jQuery 3.7.1 min.js
- MySQL 8.0.26
- nginx 1.21.1

## ER図
![alt text](<スクリーンショット 2025-06-21 130421.png>)