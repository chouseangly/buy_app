<?php

namespace App\Services\Profile;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Profile\UserProfileRepo;

class UserProfileService{
    public function __construct(private UserProfileRepo $repo) {}

    public function update($id,$request){
        return DB::transaction(function() use ($id,$request){
            $profile = $this->repo->update($id,[
                'address' => $request->address,
                'phone' => $request->phone,
                'bio' => $request->bio,
                'birthday' => $request->birthday,
                'gender' => $request->gender
            ]);

            if($request->hasFile('profile')){
               $this->repo->addImage($profile, $request->file('profile'));
            }
            return $profile->fresh();
        });
    }

    public function getProfile(){
        $user = Auth::user();
        if(!$user){
            return null;
        }

        return $this->repo->getProfile($user);

    }
}
