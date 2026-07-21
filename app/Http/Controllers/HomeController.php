<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slide;
use App\Models\TeamMember;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'slides' => Slide::active()->get(),
            'brands' => Brand::active()->get(),
            'featuredCategories' => Category::active()->where('is_featured', true)->get(),
            'rootCategories' => Category::active()->roots()->get(),
            'featuredProducts' => Product::active()->featured()->latest()->take(8)->get(),
            'team' => TeamMember::active()->get(),
        ]);
    }
}
