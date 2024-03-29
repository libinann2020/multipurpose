<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        $users = User::query()
                ->when(request('query'), function($query, $searchQuery){
                    $query->where('name', 'like', "%{$searchQuery}%");
                })
                ->latest()
                ->paginate(setting('pagination_limit'));
        // $users = User::latest()->paginate(5);
        // ->map(function($user) {
        //     return [
        //         'id' => $user->id,
        //         'name' => $user->name,
        //         'email' => $user->email,
        //         'created_at' => $user->created_at->format(config('app.date_format')),
        //         // 'created_at' => $user->created_at->format('Y-m-d'),
        //     ];
        // });
        return $users;
    }
    public function store() {
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
        ]);
        return User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
        ]);
    }
    public function update(User $user) {
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:8'
        ]);
        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('password') ? bcrypt(request('password')) : $user->password
        ]);
        return $user;
    }
    public function destroy(User $user) {
        $user->delete();
        return response()->noContent();
    }
    public function changeRole(User $user) {
        $user->update([
            'role' => request('role'),
        ]);
        return response()->json(['success' => true]);
    }
    // public function search(){
    //     $searchQuery = request('query');
    //     $users = User::where('name', 'like', "%{$searchQuery}%")->paginate();
    //     return response()->json($users);
    // }
    public function bulkDelete() {
        User::whereIn('id', request('ids'))->delete();
        return response()->json(['message' => 'Users deleted successfully']);
    }
}
