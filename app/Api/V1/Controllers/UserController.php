<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailRestorePasswordJob;
use App\Models\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class UserController extends Controller {

    use Helpers;

    /**
     * Restore password and send a notification to e-mail
     *
     * @route POST /api/user/restore_password
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function restorePassword(Request $request){

        // Validation
        $this->validate($request, [
            'email' => 'required|string|email|max:255|exists:users,email,deleted_at,NULL',
        ]);

        $user = User::where('email', $request->email)->first();
        $password = mb_substr(Uuid::uuid4(), -6);
        $user->password = Hash::make($password);
        $user->save();

        $this->dispatch(new SendEmailRestorePasswordJob($user->email, $password));

        return $this->response->accepted();

    }

    /**
     * Update profile photo of the specified user referring to his role
     *
     * @route POST /api/user/upload_photo
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function uploadPhoto(Request $request){

        // Validation
        $this->validate($request, [
            'file' => 'required|string',
        ]);

        // Parse input base64 file
        if(!preg_match('@^data\:image\/([\w]+)\;base64\,(.*)$@', $request->file, $match)){
            abort(400, 'Invalid file format');
        }

        $extension = $match[1];
        if(false === ($content = base64_decode($match[2], true))){
            abort(400, 'Cannot parse base64 buffer');
        }

        $user = Auth::user();
        $disk = Storage::disk('uploads');

        // Upload for clients
        if(null != $user->client){

            // Disk & DB actions
            $directory = "clients/{$user->client->id}/photos";
            $path = "{$directory}/{$user->client->photo}";

            if(in_array($path, $disk->files($directory))){
                $disk->delete($path);
            }

            $user->client->photo = Uuid::uuid4() . '.' . $extension;
            $path = "clients/{$user->client->id}/photos/{$user->client->photo}";
            $disk->put($path, $content);
            $user->client->save();

            // Response
            dd($user->client);
            return $this->response->item($user->client, new ClientTransformer());

        }

        // Upload for resellers
        else if(null != $user->professional){

            // Disk & DB actions
            $directory = "resellers/{$user->professional->id}/photos";
            $photo = $user->professional->photos()->where('default', true)->first();
            if(null == $photo){
                $photo = Media::create();
                $photo->professionals()->attach($user->professional, ['default' => true]);
            }
            $path = "{$directory}/{$photo->file}";

            if(in_array($path, $disk->files($directory))){
                $disk->delete($path);
            }

            $photo->file = Uuid::uuid4() . '.' . $extension;
            $path = "{$directory}/{$photo->file}";
            $disk->put($path, $content);

            $photo->save();

            // Response
            return $this->response->item($user->professional, new ProfessionalByIdTransformer());

        }

        // Fallback for unexpected users
        else {
            abort(403, 'Invalid user role');
        }

    }
	
	 public function notifications(){
		
		$user = Auth::user();
		
        return $this->response->collection($user->notifications, new NotificationTransformer());

    }
    // test comment

}