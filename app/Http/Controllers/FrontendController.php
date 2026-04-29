<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageMeta;
use App\Models\Company;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    public function home()
    {
        $pageData = Page::with('meta')->where('is_active', 1)
        ->where('slug', 'home')
        ->firstOrFail();

        return view('frontend.pages.home', compact('pageData'));
    }

    public function about()
    {
        $pageData = Page::with('meta')->where('is_active', 1)
        ->where('slug', 'about-us')
        ->firstOrFail();      
    
        return view('frontend.pages.about', compact('pageData'));
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }   
        
    public function default($slug)
    {
        $pageData = Page::with('meta')->where('is_active', 1)
        ->where('slug', $slug)
        ->firstOrFail();
    
        return view('frontend.pages.common', compact('pageData'));
    }     
        
}

