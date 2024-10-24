<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostHistoryRequest;
use App\Http\Requests\UpdatePostHistoryRequest;
use App\Models\History;

class PostHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostHistoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(History $revision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(History $revision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostHistoryRequest $request, History $revision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(History $revision)
    {
        //
    }
}
