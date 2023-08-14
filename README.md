<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# TaskWise

Welcome to TaskWise! This project is designed to simplify task management and streamline team collaboration. The application provides a robust task tracking system with features like due date monitoring, role-based status transitions, and notifications.

## Getting Started

To start using TaskWise, follow these steps:

1. Clone the repository to your local machine:
   ```shell
   git clone https://github.com/your-username/TaskWise.git
   cd TaskWise
   ```

2. Install project dependencies using Composer:
   ```shell
   composer install
   ```

3. Copy the `.env.example` file and configure your environment variables:
   ```shell
   cp .env.example .env
   php artisan key:generate
   ```

4. Create and seed the database with fake data using the following command (optional):
   ```shell
   php artisan migrate --seed
   ```

5. Start the Laravel development server:
   ```shell
   php artisan serve
   ```

6. Base Url For the api will be:
   ```
   http://localhost:8000/api/v1
   ```  

## Dependencies

TaskWise utilizes the following dependencies:

- [Laravel Framework](https://laravel.com) Laravel Framework
- [laravel/sanctum](https://laravel.com/docs/10.x/sanctum): A lightweight package for API authentication using Laravel's built-in features.
- [maatwebsite/excel](https://docs.laravel-excel.com/3.1/getting-started/): A package for importing and exporting Excel and CSV files in Laravel applications.
- [spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v5/introduction): A package for managing role-based permissions and authorization in Laravel applications.

Make sure to install and configure these dependencies according to the provided links and documentation.


## Test Email Sending with MailTrap

We have already set up email sending in this project, and we're using MailTrap, a testing environment for catching and inspecting emails. You can check the emails we send from the application without signing up for MailTrap. We've provided the MailTrap credentials, so follow the steps below:

1. **Visit MailTrap Inbox:** Open [MailTrap Inbox](https://mailtrap.io/inboxes/1432104/messages/3650415878) to view the emails sent from the application. This link will take you directly to the inbox where you can see the emails.

2. **Login to MailTrap:** Use the following MailTrap login credentials to access the inbox:
   - Email: basit99dev@gmail.com
   - Password: 12345678

3. **Check Received Emails:** In the MailTrap inbox, you'll find the emails that have been sent from the application. You can inspect the content, headers, and attachments of these emails.

4. **Queue Worker (Important):** To ensure that emails are sent correctly and asynchronously, Open your `.env` file and set the `QUEUE_CONNECTION` to `database`, and then run the following command in your terminal:
   ```shell
   php artisan queue:work
   ```
   This command starts the Laravel queue worker, which processes jobs in the background. It's essential for sending emails and performing other queued tasks.
