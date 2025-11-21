#!/bin/bash

# Git Setup Script for Pterodactyl Custom
# Push to GitHub repository

echo "🚀 Setting up Git repository for Pterodactyl Custom..."

# Check if git is initialized
if [ ! -d ".git" ]; then
    echo "📦 Initializing Git repository..."
    git init
fi

# Add all files
echo "📝 Adding files to Git..."
git add .

# Create .gitignore if not exists
if [ ! -f ".gitignore" ]; then
    echo "🚫 Creating .gitignore..."
    cat > .gitignore << 'EOF'
/vendor/
/node_modules/
/storage/*.key
/storage/app/*.tar.gz
/storage/logs/
/storage/framework/cache/
/storage/framework/sessions/
/storage/framework/testing/
/storage/framework/views/
/bootstrap/cache/
.env
.env.backup
.phpunit.result.cache
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
.DS_Store
Thumbs.db
/storage/framework/views/*
EOF
    git add .gitignore
fi

# Commit changes
echo "💾 Committing changes..."
git commit -m "Initial commit: Pterodactyl Custom by Ibra Decode

Features:
- Custom branding (Ibra Decode)
- NodeJS Bot Egg (WhatsApp & Telegram)
- Protection system
- Auto setup script
- All copyrights updated

Contact: https://t.me/ibradecode"

# Add remote (change this to your repo)
echo "🔗 Setting up remote repository..."
echo "Enter your GitHub repository URL (or press ENTER to skip):"
read repo_url

if [ ! -z "$repo_url" ]; then
    git remote add origin $repo_url
    git remote -v
    
    echo "📤 Pushing to GitHub..."
    git push -u origin main
else
    echo "⚠️  No repository URL provided."
    echo "To add repository later, run:"
    echo "git remote add origin https://github.com/username/repo.git"
    echo "git push -u origin main"
fi

echo "✅ Git setup complete!"
echo ""
echo "📋 Next steps:"
echo "1. Create repository on GitHub"
echo "2. Run: git remote add origin https://github.com/username/repo.git"
echo "3. Run: git push -u origin main"
echo ""
echo "🎉 Ready to deploy to new servers!"