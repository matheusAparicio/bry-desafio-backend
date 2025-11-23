<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected string $model = User::class;

    public function index()
    {
        return response()->json(
            $this->model::with(['companies', 'documentFile'])->get()
        );
    }

    public function store(Request $request)
    {
        $auth = Auth::user();

        // ACL Rules
    
        // 1. Unlogged user -> can just create customer.
        if (!$auth) {
            if ($request->type !== 'customer') {
                throw new UnauthorizedException("Unauthenticated users can only create customers.");
            }
        }
    
        // 2. Logged customer -> cannot create other users.
        if ($auth && $auth->type === 'customer') {
            throw new UnauthorizedException("Customers cannot create other users.");
        }

        $data = $request->validate([
            'login' => 'required|string|unique:users,login',
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|unique:users,cpf|size:11',
            'email' => 'required|email|unique:users,email',
            'type' => 'required|in:employee,customer',
            'address' => 'required|string|max:255',
            'document_file' => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = $this->model::create($data);
    
        return response()->json([
            'message' => 'User created successfully.',
            'user' => $user,
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $auth = Auth::user();
        $target = $this->model::findOrFail($id);
    
        // ACL Rules
        
        // 1. Customers can only update their own.
        if ($auth->type === 'customer' && $auth->id !== $target->id) {
            throw new UnauthorizedException("Customers can only update their own profile.");
        }

        // 2. Only employees can change user type. 
        if ($request->has('type') && $auth->type !== 'employee') {
            throw new UnauthorizedException("Only employees can change user type.");
        }
    
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:employee,customer',
            'address' => 'sometimes|required|string|max:255',
            'password' => 'sometimes|required|string|min:6',
        ]);
    
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
    
        $target->update($data);
    
        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $target
        ]);
    }

    public function destroy($id)
    {
        $auth = Auth::user();
        $target = $this->model::findOrFail($id);

        // ACL Rules

        // 1. Customer -> can only delete itself.
        if ($auth->type === 'customer' && $auth->id !== $target->id) {
            throw new UnauthorizedException("Customers can only delete themselves.");
        }

        $target->delete();

        return response()->json([
            'message' => 'User deleted successfully.'
        ]);
    }
}
