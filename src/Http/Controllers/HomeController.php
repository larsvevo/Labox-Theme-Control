<?php

namespace Atom\Theme\Http\Controllers;

use Atom\Core\Models\CameraWeb;
use Atom\Core\Models\WebsiteArticle;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;
class HomeController extends Controller
{
    /**
     * Handle an incoming request.
     */
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $referrals = $user->referrals;

        $friends = $user->friends()
            ->whereRelation('friend', 'online', '1')
            ->get();

        $article = WebsiteArticle::with('user')
            ->where('is_published', true)
            ->latest('id')
            ->limit(6)
            ->get();

        $articles = WebsiteArticle::with('user')
            ->where('is_published', true)
            ->latest('id')
            ->limit(6)
            ->get(); 

        $photos = CameraWeb::latest('id')
                ->take(4)
                ->with('user:id,username,look')
                ->get();


        $achievementScore = DB::table('users_settings')
            ->where('user_id', $user->id)
            ->value('achievement_score');
        
            $lastOnlineTimestamp = DB::table('users')
            ->where('id', $user->id)
            ->value('last_online');


        $lastOnline = Carbon::createFromTimestamp($lastOnlineTimestamp)
            ->locale('it') 
            ->diffForHumans(); 


        return view('home', compact('articles', 'article', 'friends', 'referrals', 'photos', 'achievementScore', 'lastOnline'));
    }
}
