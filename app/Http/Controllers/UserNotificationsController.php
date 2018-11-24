<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserNotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function destroy(User $user, $notificationId)
    {
        $user->notifications()->findOrFail($notificationId)->markAsRead();
    }

    public function index()
    {
        return auth()->user()->unreadNotifications;
    }
}
