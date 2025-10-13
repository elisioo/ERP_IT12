# ERP_KOREANDINERDAVAO

## Overview
ERP_KOREANDINERDAVAO is an all-in-one Enterprise Resource Planning (ERP) system designed specifically for Korean Diner Davao Restaurant. This system streamlines restaurant operations by integrating employee management, attendance tracking, payroll distribution, and inventory control into a single, user-friendly platform.

## Features

### Employee Management
- **Employee Records**: Maintain comprehensive employee profiles including personal details, contact information, and employment history.
- **Attendance Tracking**: Automated time-in and time-out logging with scheduled timeout management.
- **Payroll Processing**: Generate and distribute payroll based on attendance, hourly rates, and deductions.

### Inventory System
- **Order Management**: Track and manage customer orders, menu items, and inventory levels.
- **Product Catalog**: Organize products by categories with cost pricing and unit tracking.
- **Expense Tracking**: Monitor upcoming and recurring expenses for better financial planning.

### Reporting
- **Payroll Reports**: Generate detailed payroll reports for employees and management.
- **Analytics Dashboard**: Visualize inventory levels, sales data, and performance metrics.

## Technology Stack
- **Framework**: Laravel (PHP)
- **Database**: MySQL (via migrations)
- **Frontend**: Blade templates, CSS, JavaScript
- **Build Tool**: Vite

## Installation
1. Clone the repository: `git clone https://github.com/einzkie26/ERP_KOREANDINERDAVAO.git`
2. Navigate to the project directory: `cd ERP_KOREANDINERDAVAO`
3. Install PHP dependencies: `composer install`
4. Install Node.js dependencies: `npm install`
5. Copy environment file: `cp .env.example .env`
6. Generate application key: `php artisan key:generate`
7. Run database migrations: `php artisan migrate`
8. Seed the database (optional): `php artisan db:seed`
9. Build assets: `npm run build`
10. Start the development server: `php artisan serve`

## Usage
- Access the admin panel at `/admin` for system management.
- Employee portal available at `/employee` for attendance and payroll viewing.
- Inventory management through dedicated dashboard.

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request with your improvements.

## License
This project is licensed under the MIT License.
