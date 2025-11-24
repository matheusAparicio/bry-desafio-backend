<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Exceptions\UnauthorizedException;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with(['employees', 'customers'])->get();

        return response()->json($companies);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'cnpj' => 'required|string|size:14|unique:companies,cnpj',
            'address' => 'required|string|max:255',
        ]);
    
        Company::create($data);
    
        return response()->json([
            'message' => 'Company created successfully.'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $auth = Auth::user();

        if ($auth->type !== 'employee') {
            throw new ForbiddenException("Only employees can update companies.");
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
        ]);
    
        $company = Company::findOrFail($id);
        $company->update($data);
    
        return response()->json([
            'message' => 'Company updated successfully.'
        ]);
    }

    public function destroy($id)
    {
        $auth = Auth::user();

        if ($auth->type !== 'employee') {
            throw new ForbiddenException("Only employees can delete companies.");
        }
    
        $isMember = $auth->companies()->where('companies.id', $id)->exists();
    
        if (!$isMember) {
            throw new ForbiddenException("You don't have permission to delete this company.");
        }
    
        Company::findOrFail($id)->delete();
    
        return response()->json([
            'message' => 'Company deleted successfully.'
        ]);
    }
}
