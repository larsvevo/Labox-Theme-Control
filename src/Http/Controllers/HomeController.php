<?php

namespace Atom\Theme\Http\Controllers;

use Atom\Core\Models\CameraWeb;
use Atom\Core\Models\WebsiteArticle;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

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


        $achievementScore = $user->settings()
            ->select('achievement_score')
            ->value('achievement_score');
        return view('home', compact('articles', 'article', 'friends', 'referrals', 'photos', 'achievementScore'));
    }
}

