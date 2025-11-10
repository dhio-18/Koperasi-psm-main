<?php

namespace App\Http\Controllers;

class InformationController extends Controller
{
    /**
     * Display the ordering guide page
     */
    public function orderingGuide()
    {
        return view('pages.information.ordering-guide');
    }
}
