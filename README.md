# Bulk Product Import Application

A modern Laravel-based web application for managing products with bulk CSV import functionality, image management, and real-time progress tracking.

## Features

### 🛍️ Product Management
- View all products in a professional, paginated table
- Product details including SKU, name, description, and price
- Responsive design with hover effects and modern UI

### 📊 Bulk CSV Import
- Upload CSV files for bulk product import
- Real-time progress tracking with detailed statistics
- Import status monitoring (Total, Inserted, Updated, Invalid, Duplicates)
- Download sample CSV template for correct formatting
- Background job processing for large imports

### 🖼️ Image Gallery Management
- Upload multiple images per product
- Gallery view for product images
- Set primary images for products
- Chunked upload support for large files
- Image organization and management

### 🔐 Authentication & Security
- User authentication with Laravel Breeze
- Secure login/logout functionality
- Protected routes and middleware
- CSRF protection

### 🎨 Modern UI/UX
- Tailwind CSS for responsive design
- Professional modal dialogs
- Loading states and progress indicators
- Intuitive navigation with back buttons
- Success/error message styling

## Technology Stack

- **Backend**: Laravel 11.x
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: MySQL/PostgreSQL/SQLite
- **Queue System**: Database queues for background processing
- **File Storage**: Local file system with public disk
- **Authentication**: Laravel Breeze
- **Build Tool**: Vite for asset compilation

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL/PostgreSQL/SQLite database

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd bulk-import
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Configure your database settings in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bulk_import
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build Assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

7. **Start the Application**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

## Usage

### User Registration & Login
1. Visit the application URL
2. Register a new account or login with existing credentials
3. Access the dashboard

### Managing Products
1. Navigate to the "Products" section
2. View the paginated product list
3. Use the back button to navigate between pages

### Bulk Import Process
1. Click "Import CSV" button on the Products page
2. Download the sample CSV to understand the required format
3. Select your CSV file and click "Upload & Import"
4. Monitor the real-time progress in the modal
5. View import statistics and any errors

### CSV Format
The CSV file should contain the following columns:
- `sku` - Unique product identifier
- `name` - Product name
- `description` - Product description
- `price` - Product price (numeric)

Example:
```csv
SKU,Name,Description,Price
PROD-001,Sample Product 1,This is a sample product,29.99
PROD-002,Sample Product 2,Another sample product,49.99
```

### Image Management
1. Click "Gallery" on any product row
2. Upload multiple images for the product
3. Set primary images using the interface
4. View all product images in the gallery

## Queue Processing

For production use, configure queue processing:

```bash
php artisan queue:work
```

This ensures bulk imports are processed in the background efficiently.

## File Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── ProductController.php
│   │   ├── ProductImportController.php
│   │   └── UploadController.php
│   ├── Jobs/
│   │   ├── ImportProductsJob.php
│   │   └── ProcessImageJob.php
│   └── Models/
│       ├── Product.php
│       ├── Image.php
│       └── Upload.php
├── resources/views/
│   ├── products/
│   │   ├── index.blade.php
│   │   └── images.blade.php
│   └── layouts/
│       ├── app.blade.php
│       └── navigation.blade.php
├── routes/
│   └── web.php
├── storage/app/
│   ├── chunks/     # Temporary upload chunks
│   ├── public/     # Public files
│   └── uploads/    # Uploaded files
└── database/migrations/
    ├── create_products_table.php
    ├── create_uploads_table.php
    └── create_images_table.php
```

## API Endpoints

### Products
- `GET /products` - List all products
- `GET /products/sample/download` - Download sample CSV
- `POST /products/import` - Upload and import CSV
- `GET /products/import/{import}/progress` - Get import progress

### Images
- `GET /products/{product}/images` - View product images
- `POST /products/{product}/set-primary` - Set primary image
- `POST /upload/chunk` - Upload file chunk
- `POST /upload/{product}/complete` - Complete chunked upload

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Building Assets for Production
```bash
npm run build
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and questions, please open an issue on the GitHub repository.</content>
</xai:function_call name="attempt_completion">
<parameter name="result">I've created a comprehensive README.md file for the bulk import application. The README includes:

- Detailed feature descriptions
- Technology stack information
- Complete installation instructions
- Usage guide with examples
- CSV format specification
- File structure overview
- API endpoints documentation
- Development guidelines

This provides users with everything they need to understand, install, and use the application effectively.
