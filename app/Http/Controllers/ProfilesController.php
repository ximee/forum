<?php

namespace App\Http\Controllers;

use App\User;
use App\Activity;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser' => $user,
            'activities' => Activity::feed($user)
//            'threads' => $user->threads()->paginate(1)
        ]);
    }
//
//    /**
//     * @param User $user
//     * @return mixed
//     */
//    protected function getActivity(User $user)
//    {
//        return $user->activity()->latest()->with('subject')->get()->groupBy(function ($activity) {
//            return $activity->created_at->format('Y-m-d');
//        });
//    }
}
