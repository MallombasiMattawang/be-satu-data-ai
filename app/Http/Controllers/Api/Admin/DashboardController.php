<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\AboutUs;
use App\Models\Blog;
use App\Models\CoverageArea;
use App\Models\Package;
use App\Models\Partner;
use App\Models\Promotion;
use App\Models\Subscription;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //count aboutUs
        $aboutUs = AboutUs::count();

        //count blogs
        $blogs = Blog::count();

        //count coverageAreas
        $coverageAreas = CoverageArea::count();

        //count packages
        $packages = Package::count();

        //count partners
        $partners = Partner::count();

        //count promotions
        $promotions = Promotion::count();

        //count subscriptions
        $subscriptions = Subscription::count();

        //count testimonials
        $testimonials = Testimonial::count();

        //return response json
        return response()->json([
            'success'   => true,
            'message'   => 'List Data on Dashboard',
            'data'      => [
                'aboutUs' => $aboutUs,
                'blogs' => $blogs,
                'coverageAreas' => $coverageAreas,
                'packages' => $packages,
                'partners' => $partners,
                'promotions' => $promotions,
                'subscriptions' => $subscriptions,
                'testimonials' => $testimonials,
            ]
        ]);
    }
}
