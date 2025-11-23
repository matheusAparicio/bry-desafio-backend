<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Models\Company;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class CompanyUserController extends Controller
{
    public function attach(Request $request)
    {
        $auth = Auth::user();

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
        ]);

        $targetUser = User::findOrFail($data['user_id']);
        $company = Company::findOrFail($data['company_id']);

        $authIsSelf = ($auth->id === $targetUser->id);

        if (!$authIsSelf) {

            if ($auth->type === 'customer') {
                throw new ForbiddenException("Customers cannot attach other users to companies.");
            }

            $isMember = $auth->companies()->where('companies.id', $company->id)->exists();

            if (!$isMember) {
                throw new ForbiddenException("You must be a member of this company to attach other users.");
            }
        }

        if ($company->users()->where('users.id', $targetUser->id)->exists()) {
            return response()->json([
                'message' => 'User is already attached to this company.'
            ], 200);
        }

        $company->users()->attach($targetUser->id);

        return response()->json([
            'message' => 'User attached successfully.'
        ], 201);
    }


    public function detach(Request $request)
    {
        $auth = Auth::user();

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
        ]);

        $targetUser = User::findOrFail($data['user_id']);
        $company = Company::findOrFail($data['company_id']);

        $authIsSelf = ($auth->id === $targetUser->id);

        if (!$authIsSelf) {

            if ($auth->type === 'customer') {
                throw new ForbiddenException("Customers cannot detach other users from companies.");
            }

            $isMember = $auth->companies()->where('companies.id', $company->id)->exists();

            if (!$isMember) {
                throw new ForbiddenException("You must be a member of this company to detach users.");
            }
        }

        if (! $company->users()->where('users.id', $targetUser->id)->exists()) {
            return response()->json([
                'message' => "This user is not attached to the company."
            ], 404);
        }

        $company->users()->detach($targetUser->id);

        return response()->json([
            'message' => 'User detached successfully.'
        ]);
    }
}
