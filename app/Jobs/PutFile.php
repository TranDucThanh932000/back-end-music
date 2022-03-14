<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PutFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // //lưu vào public
        $filePut = Storage::disk('public')->get('/' . $this->user['avatar']);
        // Storage::disk('google')->put($nameFile . '.' . $nameExtension, file_get_contents($this->user['avatar']));
        // //lưu lên drive
        Storage::cloud()->put('/' . $this->user['avatar'], $filePut);
        $details = Storage::disk("google")->getMetadata($this->user['avatar']);
        Storage::disk('google')->setVisibility($details['path'], 'public');

        User::where('username', $this->user['username'])->update([
            'fullname' => $this->user['fullname'],
            'email' => $this->user['email'],
            'avatar' => $details['path']
        ]);

        // //xóa ở public
        Storage::disk('public')->delete('/' . $this->user['avatar']);
    }
}
