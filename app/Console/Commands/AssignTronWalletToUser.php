<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class AssignTronWalletToUser extends Command
{
    protected $signature = 'wallet:assign {user_id} {address} {private_key}';

    protected $description = 'Assign TRC20 wallet to a user';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $address = $this->argument('address');
        $privateKey = $this->argument('private_key');

        $user = User::find($userId);

        if (!$user) {
            $this->error('User not found');
            return;
        }

        if (User::where('deposit_trc20_address', $address)->exists()) {
            $this->error('This address is already used by another user');
            return;
        }

        $user->deposit_trc20_address = $address;
        $user->deposit_trc20_private_key = Crypt::encryptString($privateKey);
        $user->deposit_trc20_active = true;
        $user->save();

        $this->info('Wallet assigned successfully to user ID ' . $userId);
    }
}