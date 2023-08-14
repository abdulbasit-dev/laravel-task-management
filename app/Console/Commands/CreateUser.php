<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
    protected $signature = 'user:create
                            {name? : The name of the user}
                            {email? : The email address of the user}
                            {password? : The password for the user}
                            {--role= : The role of the user}
    ';

    protected $description = 'Create New User';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $role = $this->option('role');

        // Ask the user for the name if it's not provided
        if (!$name) {
            $name = $this->ask('Enter the name of the user:');
        }

        // Ask the user for the email if it's not provided or if it's not a valid email format
        while (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if (!$email) {
                $email = $this->ask('Enter the email address of the user:');
            } else {
                $this->error('Invalid email address');
                $email = $this->ask('Enter a valid email address of the user:');
            }
        }

        // Check if the email is already registered
        while (User::where('email', $email)->exists()) {
            $this->error('Email address is already registered');
            $email = $this->ask('Enter a different email address of the user:');
        }

        // Ask the user for the password if it's not provided
        if (!$password) {
            $password = $this->secret('Enter the password for the user:');
        }

        // Ask the user for the role if it's not provided
        if (!$role) {
            $role = $this->choice('Enter the role (e.g., "Developer", "Tester", "Product Owner"):', ['Developer', 'Tester', 'Product Owner']);
            // transform role to lowercase and replace space with underscore
            $role = strtolower(str_replace(' ', '_', $role));
        }

        // create new user
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ])->assignRole($role);


        $this->info('User created successfully!');
    }
}
