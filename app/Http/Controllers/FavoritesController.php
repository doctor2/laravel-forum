<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;
use App\Favorite;

class FavoritesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        // \DB::table('favorites')->insert([
        // Favorite::create([
        //     'user_id' => auth()->id(),
        //     'favorited_id' => $reply->id,
        //     'favorited_type' => get_class($reply)
        // ]);
        $reply->favorite();

        return back();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        return back();
    }
}
