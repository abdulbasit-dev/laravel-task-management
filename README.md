<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


## TaskWise


## Test Email Sending with MailTrap

We have already set up email sending in this project, and we're using MailTrap, a testing environment for catching and inspecting emails. You can check the emails we send from the application without signing up for MailTrap. We've provided the MailTrap credentials, so follow the steps below:

1. **Visit MailTrap Inbox:** Open [MailTrap Inbox](https://mailtrap.io/inboxes/1432104/messages/3650415878) to view the emails sent from the application. This link will take you directly to the inbox where you can see the emails.

2. **Login to MailTrap:** Use the following MailTrap login credentials to access the inbox:
   - Email: basit99dev@gmail.com
   - Password: 12345678

3. **Check Received Emails:** In the MailTrap inbox, you'll find the emails that have been sent from the application. You can inspect the content, headers, and attachments of these emails.

4. **Queue Worker (Important):** To ensure that emails are sent correctly and asynchronously, run the following command in your terminal:
   ```shell
   php artisan queue:work
   ```
   This command starts the Laravel queue worker, which processes jobs in the background. It's essential for sending emails and performing other queued tasks.

