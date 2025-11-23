<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    protected string $model = User::class;

    public function index()
    {
        return response()->json($this->model::with('companies')->get());
    }

    public function store($id)
    {
        // return $this->model::findOrFail($id);
    }

    public function show($id)
    {
        return $this->model::findOrFail($id);
    }

    public function update($id)
    {
        // return $this->model::findOrFail($id);
    }

    public function destroy($id)
    {
        return $this->model::findOrFail($id)->delete();
    }
}
