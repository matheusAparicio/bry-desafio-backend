<?php

namespace App\Http\Controllers;

use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with(['employees', 'customers'])->get();

        return response()->json($companies);
    }

    public function store($id)
    {
        // return $this->model::findOrFail($id);
    }

    public function show($id)
    {
        return Company::findOrFail($id);
    }

    public function update($id)
    {
        // return Company::findOrFail($id);
    }

    public function destroy($id)
    {
        return Company::findOrFail($id)->delete();
    }
}
