# 📄 Content Management System (Laravel 10)

A role-based **Content Management System (CMS)** built with **Laravel 10**, featuring user authentication, role-based permissions, category & article management, and comment moderation.

---

## 🚀 Features

- **User Roles & Permissions**
  - **Admin** – Manage users, categories, articles, and comments.
  - **Editor** – Create & edit articles within assigned categories.
  - **Guest** – View published articles & post comments.

- **Category Management**
  - Create, edit, delete categories.
  - Supports nested/hierarchical categories.

- **Article Management**
  - Create, edit, delete, and publish/unpublish articles.
  - Associate articles with categories.
  - Search articles by title, content, or category.

- **Comment System**
  - Guests can post comments on published articles.
  - Comments require admin approval before being visible.

- **Admin Dashboard**
  - Manage all user activities and content.
  - View pending comments for moderation.

- **Responsive Design**
  - Built with **CSS** for mobile-first, responsive layouts.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 10.x (PHP 8+)
- **Frontend**: CSS
- **Optional Interactivity**: Livewire / Alpine.js
- **Database**: MySQL / MariaDB
- **Testing**: PHPUnit (Unit & Feature Tests)

---

## 📂 Installation

```bash
# Clone repository
git clone https://github.com/Sandip-Debnath/Content-Management-System.git

cd Content-Management-System

# Install dependencies
composer install

# Copy .env file and configure database
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations and seed initial data
php artisan migrate --seed

# Start development server
php artisan serve





Role	Email	                Password
-----------------------------------------
Admin	admin@admin.com	        password
Editor	editor@example.com	    password
Guest	guest@example.com	    password
