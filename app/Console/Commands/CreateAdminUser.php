<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un utente admin per l\'applicazione';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Creazione utente admin');
        $name = $this->ask('Nome utente');
        $email = $this->ask('Email utente');
        $password = $this->secret('Password utente', 'password');

        $user = new \App\DTO\User\User($name, $email, $password);
        $user = (new \App\Actions\User\RegisterNewUser())->handle($user);
        $user->roles()->attach(
            \App\Models\Role::where('name', 'admin')->firstOrFail()->id
        );
        $this->info('Utente admin creato');
        return Command::SUCCESS;
    }
}
