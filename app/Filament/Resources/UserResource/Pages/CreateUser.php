<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Http\Controllers\MailController;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Hashids\Hashids;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        // Runs after the form fields are saved to the database.

        ///get the user
        $user = User::where('email', $this->record['email'])->first();
        $email = $user->email;


        //create a random id btwn 1 an 1000000 that will be used for confirmation
        $confirmation_id = rand(1, 10000000000);

        $hash = new Hashids(env('APP_KEY'),  20);
        $hashed = $hash->encode($confirmation_id);


        // dd($hashed, $confirmation_id);
        $user->confirmation_id = $confirmation_id;
        //save the user
        $user->save();

        //send the user an email with their account details
        $email_details = [

            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'confirmation_string' => $hashed

        ];

        // dd($email_details, $user);

        // dd($email_details, $user);
        //call the MailController to send the email
        $mail_controller = new MailController();
        $mail_controller->sendEmailVerification($email_details);
    }
}
