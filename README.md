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
```mermaid
erDiagram
    users {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        string postal_code
        text address
        string building
        string avatar_path
        string remember_token
        timestamps created_at
        timestamps updated_at
    }
    
    products {
        bigint id PK
        bigint user_id FK
        string name
        string brand
        text description
        bigint condition_id FK
        decimal price
        string image_path
        boolean is_sold
        timestamps created_at
        timestamps updated_at
    }
    
    categories {
        bigint id PK
        string name
        timestamps created_at
        timestamps updated_at
    }
    
    conditions {
        bigint id PK
        string name
        timestamps created_at
        timestamps updated_at
    }
    
    product_categories {
        bigint id PK
        bigint product_id FK
        bigint category_id FK
    }
    
    likes {
        bigint id PK
        bigint user_id FK
        bigint product_id FK
        timestamps created_at
        timestamps updated_at
    }
    
    comments {
        bigint id PK
        bigint user_id FK
        bigint product_id FK
        text comment
        timestamps created_at
        timestamps updated_at
    }
    
    orders {
        bigint id PK
        bigint user_id FK
        bigint product_id FK
        decimal total_amount
        string status
        string payment_method
        string postal_code
        text address
        string building
        timestamps created_at
        timestamps updated_at
    }

    users ||--o{ products : "出品"
    users ||--o{ likes : "いいね"
    users ||--o{ comments : "コメント"
    users ||--o{ orders : "購入"
    products ||--o{ product_categories : "カテゴリ"
    categories ||--o{ product_categories : "商品"
    products ||--o{ likes : "いいね"
    products ||--o{ comments : "コメント"
    products ||--o{ orders : "注文"
    conditions ||--o{ products : "状態"
