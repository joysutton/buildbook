# BuildBook

A comprehensive sewing project management application designed for competition entries. BuildBook helps users create detailed project documentation, track tasks and materials, and generate professional shareable web pages with PDF export for competition judges.

## Features

- **Project Management**: Create and organize sewing projects with detailed descriptions, series, and versions
- **Task Tracking**: Manage project tasks with due dates, completion tracking, and progress images
- **Material Management**: Track materials, costs, sources, and acquisition status
- **Notes System**: Add contextual notes to projects, tasks, and materials
- **Media Management**: Upload and organize reference images and progress photos
- **Public Sharing**: Generate clean, professional share pages for competition submissions
- **PDF Export**: Create print-ready PDFs optimized for competition documentation
- **Accessibility**: Full 508 compliance with keyboard navigation and screen reader support

## Tech Stack

- **Backend**: Laravel 11 (PHP) with API-first architecture
- **Frontend**: TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire)
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Sanctum
- **Testing**: Pest PHP with 100% test coverage
- **Media**: Spatie Media Library
- **PDF Generation**: Spatie Browsershot
- **Development**: Built with Herd on Windows

## Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- [Herd](https://herd.laravel.com/) (recommended) or local PHP environment

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/buildbook.git
   cd buildbook
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   ```bash
   # Update .env with your database credentials
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=buildbook
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Configure Browsershot (for PDF generation)**
   ```bash
   # Add Browsershot environment variables to .env
   # See BROWSERSHOT_SETUP.md for detailed instructions
   NODE_BINARY_PATH="path/to/your/node.exe"
   NPM_BINARY_PATH="path/to/your/npm.cmd"
   CHROME_PATH="path/to/your/chrome.exe"
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

9. **Visit the application**
   - Open `http://localhost:8000`
   - Register a new account or use seeded test data

### Seeded Data

The application comes with sample data for testing:
- Test user: `test@example.com` / `password`
- Sample projects with tasks, materials, and notes
- Reference images and progress photos

## Testing

Run the comprehensive test suite:
```bash
php artisan test
```

All tests should pass with 100% coverage.

## API Documentation

Complete API documentation is available in [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

## PDF Generation Setup

PDF generation requires Browsershot configuration. See [BROWSERSHOT_SETUP.md](BROWSERSHOT_SETUP.md) for detailed setup instructions.

## Development

### Key Features
- **TDD Approach**: All features developed with tests first
- **Component Architecture**: Reusable Blade components
- **Accessibility First**: 508 compliance throughout
- **API-First**: Complete REST API with comprehensive testing

### File Structure
```
app/
├── Http/Controllers/Api/     # API controllers
├── Livewire/                 # Livewire components
├── Models/                   # Eloquent models
└── Requests/                 # Form request validation

resources/views/
├── components/               # Reusable Blade components
├── livewire/                 # Livewire views
└── projects/                 # Project-specific views

tests/
├── Feature/Api/              # API tests
└── Feature/                  # Feature tests
```

## License

Copyright (c) 2025 Joy Sutton

All rights reserved. This software is proprietary and confidential.
Unauthorized copying, distribution, or use is strictly prohibited. 