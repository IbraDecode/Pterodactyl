#!/bin/bash

# Auto Setup Script for Pterodactyl Custom by Ibra Decode
# Run this on fresh server to auto-configure everything

echo "🚀 Starting Pterodactyl Custom Setup by Ibra Decode..."

# Update system
echo "📦 Updating system packages..."
apt update && apt upgrade -y

# Install required packages
echo "🔧 Installing dependencies..."
apt install -y nginx mariadb-server php8.2-fpm php8.2-cli php8.2-gd php8.2-mysql php8.2-pdo php8.2-mbstring php8.2-tokenizer php8.2-bcmath php8.2-xml php8.2-curl php8.2-zip php8.2-intl redis-server unzip curl git

# Install Composer
echo "🎵 Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Create pterodactyl database
echo "🗄️ Setting up database..."
mysql -u root -e "CREATE DATABASE pterodactyl;"
mysql -u root -e "CREATE USER 'pterodactyl'@'localhost' IDENTIFIED BY 'your_password_here';"
mysql -u root -e "GRANT ALL PRIVILEGES ON pterodactyl.* TO 'pterodactyl'@'localhost';"
mysql -u root -e "FLUSH PRIVILEGES;"

# Setup Nginx
echo "🌐 Configuring Nginx..."
cat > /etc/nginx/sites-available/pterodactyl << 'EOF'
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/pterodactyl/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

ln -s /etc/nginx/sites-available/pterodactyl /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx

# Clone and setup Pterodactyl
echo "🐉 Setting up Pterodactyl Custom..."
cd /var/www/
git clone https://github.com/your-username/pterodactyl-custom.git pterodactyl
cd pterodactyl

# Install dependencies
echo "📚 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Setup environment
echo "⚙️ Configuring environment..."
cp .env.example .env
php artisan key:generate --force

# Edit .env file
echo "📝 Please edit .env file with your database and other settings..."
echo "Database: pterodactyl"
echo "Username: pterodactyl"
echo "Password: your_password_here"
echo "Press ENTER to continue..."
read

# Run migrations and seeds
echo "🗃️ Running database migrations..."
php artisan migrate --force --seed

# Setup storage and permissions
echo "🔐 Setting up permissions..."
php artisan storage:link
chown -R www-data:www-data /var/www/pterodactyl
chmod -R 755 /var/www/pterodactyl/storage

# Setup queue worker
echo "⏰ Setting up queue worker..."
cat > /etc/systemd/system/pterodactyl-worker.service << 'EOF'
[Unit]
Description=Pterodactyl Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/pterodactyl/artisan queue:work --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
EOF

systemctl enable pterodactyl-worker
systemctl start pterodactyl-worker

# Setup cron job
echo "⏰ Setting up scheduled tasks..."
(crontab -l 2>/dev/null; echo "* * * * * php /var/www/pterodactyl/artisan schedule:run >> /dev/null 2>&1") | crontab -

# Setup SSL (optional)
echo "🔒 Setting up SSL certificate..."
echo "Do you want to setup SSL with Let's Encrypt? (y/n)"
read ssl_choice
if [ "$ssl_choice" = "y" ]; then
    apt install -y certbot python3-certbot-nginx
    certbot --nginx -d your-domain.com
fi

echo "✅ Setup Complete!"
echo "🌐 Your panel should be available at: http://your-domain.com"
echo "📧 Don't forget to:"
echo "   1. Update your-domain.com in Nginx config"
echo "   2. Set your database password in .env"
echo "   3. Create admin account with: php artisan p:user:make"
echo ""
echo "🎉 Pterodactyl Custom by Ibra Decode is ready!"