# Pterodactyl Custom by Ibra Decode

Auto-deployment script dan custom Pterodactyl panel dengan:

## ✨ Features

- **Custom Branding**: Ibra Decode branding
- **NodeJS Bot Egg**: Auto-import WhatsApp & Telegram bot egg
- **Protection System**: Built-in protection tools
- **Auto Setup**: One-command deployment

## 🚀 Quick Deploy

### Option 1: Auto Setup Script

```bash
# Clone repo
git clone https://github.com/your-username/pterodactyl-custom.git
cd pterodactyl-custom

# Run auto setup
sudo ./setup.sh
```

### Option 2: Manual Setup

```bash
# Clone and install
git clone https://github.com/your-username/pterodactyl-custom.git
cd pterodactyl-custom
composer install --no-dev --optimize-autoloader

# Setup environment
cp .env.example .env
php artisan key:generate

# Edit .env with your settings
nano .env

# Run migrations and seeds
php artisan migrate --force --seed

# Setup permissions
php artisan storage:link
chown -R www-data:www-data .
```

## 📦 What's Included

### Custom Eggs

- **Bot WhatsApp & Telegram**: Node.js 15-23 support
- Auto Git clone & npm install
- Support private repositories
- Custom environment variables

### Custom Features

- **Protection System**: Admin panel protection tools
- **Custom Branding**: All copyrights changed to Ibra Decode
- **Auto Seeds**: NodeJS nest and egg auto-imported

## 🔧 Configuration

### Environment Variables

```bash
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pterodactyl
DB_USERNAME=pterodactyl
DB_PASSWORD=your_password

# Panel
APP_URL=https://your-domain.com
APP_ENV=production
APP_DEBUG=false

# Redis (recommended)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/pterodactyl/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

## 🎯 After Setup

1. **Create Admin Account**:

    ```bash
    php artisan p:user:make
    ```

2. **Setup Wings Daemon**:
    - Install Wings on separate server
    - Configure in admin panel

3. **Setup SSL** (recommended):

    ```bash
    certbot --nginx -d your-domain.com
    ```

4. **Setup Queue Worker**:
    ```bash
    systemctl enable pterodactyl-worker
    systemctl start pterodactyl-worker
    ```

## 🔄 Updates

To update to latest version:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:clear
php artisan view:clear
```

## 📁 File Structure

```
pterodactyl-custom/
├── app/
│   ├── Http/Controllers/Admin/
│   │   └── ProtectionController.php
│   └── Providers/
├── database/
│   └── Seeders/
│       ├── eggs/nodejs/
│       │   └── egg-bot-whatsapp-telegram.json
│       ├── EggSeeder.php
│       └── NestSeeder.php
├── resources/
│   ├── views/
│   └── lang/
├── setup.sh
└── README.md
```

## 🤝 Support

- **Telegram**: [Ibra Decode](https://t.me/ibradecode)
- **Email**: ibradecode@gmail.com

## 📄 License

MIT License - Customized by Ibra Decode
