<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait TraitCRUD
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!isset($this->model) || !isset($this->viewPath)) {
            abort(500, 'Model or ViewPath is not defined.');
        }

        $items = $this->model::all();
        return view($this->viewPath . '.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!isset($this->viewPath)) {
            abort(500, 'ViewPath is not defined.');
        }

        return view($this->viewPath . '.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!isset($this->model)) {
            abort(500, 'Model is not defined.');
        }

        DB::transaction(function () use ($request) {
            $this->model::create($request->all());
        });

        return redirect()->route($this->routePath . '.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (!isset($this->model) || !isset($this->viewPath)) {
            abort(500, 'Model or ViewPath is not defined.');
        }

        $item = $this->model::findOrFail($id);

        return view($this->viewPath . '.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!isset($this->model) || !isset($this->viewPath)) {
            abort(500, 'Model or ViewPath is not defined.');
        }

        $item = $this->model::findOrFail($id);

        return view($this->viewPath . '.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!isset($this->model)) {
            abort(500, 'Model is not defined.');
        }

        DB::transaction(function () use ($request, $id) {
            $item = $this->model::findOrFail($id);
            $item->update($request->all());
        });

        return redirect()->route($this->routePath . '.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!isset($this->model)) {
            abort(500, 'Model is not defined.');
        }

        DB::transaction(function () use ($id) {
            $item = $this->model::findOrFail($id);
            $item->delete();
        });

        return redirect()->route($this->routePath . '.index')->with('success', 'Combo được xóa thành công.');
    }
}
