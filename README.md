# Leave Management System

Welcome to the Leave Management System, designed to efficiently allocate and track employee leaves. Our system operates on a pro rata basis, dynamically adjusting leave balances based on an employee's start date and adhering to company policies.

## System Overview

The Leave Management System offers the following functionalities:

1. **System Requirements**:
    - The session runs from April to March of the following year.
    - Each employee is entitled to 12 Paid Leaves (PL), 2 Casual Leaves (CL), and 4 Sick Leaves (SL) per year.
    - PL increases by 1 each month, CL increases by 1 every 6 months, and SL increases by 1 each quarter.
    - Leaves are allocated on a pro rata basis. For instance, if someone joins on August 15th, they'll receive 7.5 PL, 0.25 CL, and 2.5 SL.
    - The leave allocation system is dynamic and can be modified yearly.
    - A monthly leave balance view is available for employees to track their leave balances.

## Installation Steps

Follow these steps to set up the Leave Management System:

1. **Clone the Project**:
   - Clone the project repository to your local machine.

2. **Install Dependencies**:
   - Run `composer install` to install PHP dependencies.
   - After that, run `composer dumpautoload` to optimize the autoloader.

3. **Compile Assets**:
   - Run `npm install && npm run dev` to install and compile front-end assets.

4. **Database Setup**:
   - Ensure proper database configuration.
   - Run `php artisan migrate --seed` to migrate and seed the database.

5. **Run the Application**:
   - Execute `php artisan serve` to start the application.
   - Login with the default credentials: `user@user.com` | `password: 12345`.

6. **Automate Leave Allocations**:
   - Execute `php artisan app:automate-leave-allocations` to calculate employee leaves from their joining date to the current month end.

7. **Customization**:
   - Customize the system according to your specific requirements.

## Usage

Here's how you can use the Leave Management System effectively:

1. **System Setup**:
   - Configure the system and ensure all dependencies are installed correctly.
   - Verify the database connection and migration.

2. **User Authentication**:
   - Implement secure user authentication mechanisms to safeguard system access.

3. **Leave Allocation**:
   - Develop algorithms to accurately calculate leave allocations based on company policies.

4. **Leave Balance View**:
   - Design an intuitive interface for employees to monitor their leave balances on a monthly basis.

5. **Leave Application System**:
   - Create a user-friendly module for employees to apply for leaves.

## Contributors

- [Vikas Kumar]

## License

This project is licensed under the [License Name] License. Refer to the LICENSE.md file for details.

Feel free to contribute to this project by providing feedback, suggestions, or code contributions! Your contributions are highly appreciated.

Let's make managing leaves easier and more efficient together!
