<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\PutFile;
use Illuminate\Support\Facades\Artisan;

class DemoController extends Controller
{

    protected $user;
    public function __construct()
    {
        $this->user = [
            'name' => 'name12',
            'password' => 'pw'
        ];
    }
    public function store(Request $request)
    {
        $name = $request->file->getClientOriginalName();
        $request->file->storeAs('/', $name, 'public');
        Artisan::call('cache:clear');
        
        PutFile::dispatch($name);
        $exitCode = Artisan::call('queue:work --stop-when-empty', []);

        return 'File was saved to Google Drive';
    }

    public function save(Request $request){
        // $test = \Storage::disk('google')->putFileAs('', $request->file('thing'), "file_name2.jpg");

        \Storage::disk('google')->put("file_name4.jpg", file_get_contents($request->file('thing')));
        $details = \Storage::disk("google")->getMetadata("file_name4.jpg");

        // $files = \Storage::disk('google')->allFiles();
        // dd($details);

        // return response(['mess' => $this->user['name']], 200);
    }
}
