# RideRent Pro - MVC Architecture Redesign

## Project Structure

The project has been redesigned following MVC (Model-View-Controller) architecture:

```
/app
  /config           - Configuration files (database.php, functions.php)
  /core            - Core MVC classes (Database, Model, Controller, View, Router)
  /models          - Database models (User, Vehicle, Booking, Driver, Review)
  /controllers     - Business logic controllers (Auth, Admin, Customer, Owner, Driver, Home)
  /views           - View templates organized by module
    /layouts       - Layout templates
    /auth          - Authentication views
    /admin         - Admin panel views
    /customer      - Customer portal views
    /driver        - Driver portal views
    /owner         - Owner portal views
    /home          - Home page views
/public
  index.php        - Application entry point
  .htaccess        - URL rewriting configuration
  /assets          - Static assets (CSS, JS, images)
```

## Key Changes

### 1. Routing System
- URL-based routing using `/public/index.php`
- Routes map to controller methods: `/controller/method`
- Example: `/admin/dashboard` → `AdminController::dashboard()`
- URL hyphens converted to underscores for method names: `/vehicle-approvals` → `vehicle_approvals()`

### 2. Database Layer
- Singleton Database class for connection management
- Model base class with CRUD operations
- Specific models for each entity (User, Vehicle, Booking, Driver, Review)

### 3. Controller Layer
- Business logic separated from presentation
- Authentication and authorization checks
- Request handling and response generation
- Flash message system for user feedback

### 4. View Layer
- Template-based rendering
- Layout system for consistent design
- Data separation from presentation
- Asset URL helpers

## Available Routes

### Authentication
- `/` - Home page
- `/auth/login` - Login page
- `/auth/register` - Registration page
- `/auth/forgot-password` - Password reset
- `/auth/logout` - Logout

### Admin
- `/admin/dashboard` - Admin dashboard
- `/admin/users` - User management
- `/admin/bookings` - Booking management
- `/admin/drivers` - Driver management
- `/admin/reviews` - Review management
- `/admin/reports` - Vehicle reports
- `/admin/vehicle-approvals` - Vehicle approval system
- `/admin/driver-assignment` - Driver assignment management
- `/admin/ratings` - Ratings overview

### Customer
- `/customer/dashboard` - Customer dashboard
- `/customer/vehicles` - Browse vehicles
- `/customer/book-vehicle` - Book a vehicle
- `/customer/bookings` - Booking history
- `/customer/add-review` - Add review
- `/customer/payment` - Payment processing
- `/customer/profile` - Profile management
- `/customer/compare` - Compare vehicles

### Driver
- `/driver/dashboard` - Driver dashboard
- `/driver/bookings` - Driver bookings
- `/driver/booking-details` - Booking details
- `/driver/availability` - Availability management
- `/driver/earnings` - Earnings tracking
- `/driver/performance` - Performance metrics
- `/driver/profile` - Profile management

### Owner
- `/owner/dashboard` - Owner dashboard
- `/owner/vehicles` - Vehicle management
- `/owner/add-vehicle` - Add new vehicle
- `/owner/edit-vehicle` - Edit vehicle
- `/owner/delete-vehicle` - Delete vehicle
- `/owner/vehicle-details` - View vehicle details
- `/owner/vehicle-performance` - Vehicle performance analytics
- `/owner/bookings` - Booking management
- `/owner/drivers` - Driver management
- `/owner/profile` - Profile management

## Testing

### Access the Application
- Home page: `http://localhost/RideRentPro/public/`
- Login: `http://localhost/RideRentPro/public/auth/login`
- Admin Dashboard: `http://localhost/RideRentPro/public/admin/dashboard`

### Prerequisites
- XAMPP/Apache server running
- MySQL database configured
- mod_rewrite enabled in Apache

### Database Configuration
- Update `app/config/database.php` with your database credentials
- Import the SQL schema from `config/database/riderent_prodb.sql`

### Important Notes
- The built-in PHP server (`php -S`) may not have all required extensions (like mysqli)
- Use XAMPP's Apache server for proper testing
- Ensure XAMPP Apache is running and pointing to the correct document root

## Development Notes

### Adding New Features
1. Create model in `app/models/`
2. Create controller in `app/controllers/`
3. Create views in `app/views/`
4. Add routes in `app/core/Router.php` if needed

### Session Management
- Sessions are managed through the Controller base class
- Role-based access control using `requireLogin($role)`
- Flash messages for user feedback

### Asset Management
- All assets are served from `/public/assets/`
- Use `$this->view->asset('path')` helper in controllers

## Migration from Old Structure

The old structure files have been successfully removed:
- ✅ `/admin/` - Removed
- ✅ `/auth/` - Removed
- ✅ `/customer/` - Removed
- ✅ `/driver/` - Removed
- ✅ `/owner/` - Removed
- ✅ `/includes/` - Removed
- ✅ `/config/` - Removed
- ✅ `index.php` (old root) - Removed

All functionality has been migrated to the new MVC structure in `/app/` and `/public/`.

## Completed Migration

### Authentication ✅
- Login with role-based authentication
- Registration with role selection
- Password reset functionality
- Logout with session destruction

### Admin Panel ✅
- Dashboard with statistics
- User management (Admin, Customer, Driver, Owner)
- Booking management with status updates
- Driver management with availability controls
- Review management with approval system
- Vehicle reports and analytics
- Vehicle approval system

### Customer Portal ✅
- Dashboard with booking statistics
- Vehicle browsing with search and filters
- Vehicle booking with driver options
- Booking history and management
- Profile management with image upload
- Review system for vehicles

### Driver Portal ✅
- Dashboard with earnings and statistics
- Booking management
- Availability controls
- Earnings tracking
- Performance metrics with charts
- Profile management

### Owner Portal ✅
- Dashboard with vehicle and booking statistics
- Vehicle management (add, edit, delete)
- Booking management
- Driver management
- Profile management

### Core MVC Components ✅
- Database singleton class
- Model base class with CRUD operations
- Controller base class with helpers
- View class with rendering and helpers
- Router with URL parsing and dispatching