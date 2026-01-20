<?php

namespace App\Repositories\Profile;

use App\Models\User;
use App\Models\UserProfile;
use App\Repositories\ImageStorage\ImageStorageInterface;

class UserProfileRepo{
    public function __construct(private ImageStorageInterface $imageStorage){ }

     public function addImage(UserProfile $userProfile ,  $image){
        $stored = $this->imageStorage->store($image);

        $userProfile->update([
            'profile' => $stored['url']
        ]);

    }

    public function update($id,array $data){
        $profile = UserProfile::findOrFail($id);
        $profile->update($data);
        return $profile;
    }

    public function getProfile(User $user){
        return $user->load('profile');
    }
}
