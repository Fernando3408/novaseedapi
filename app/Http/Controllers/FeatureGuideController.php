<?php

namespace App\Http\Controllers;

use App\Support\FeatureGuideBuilder;

class FeatureGuideController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $families = (new FeatureGuideBuilder())->build();

        return view('feature-guide.index', compact('families'));
    }
}
