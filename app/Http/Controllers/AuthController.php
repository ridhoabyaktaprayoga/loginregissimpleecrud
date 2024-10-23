<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth/register');
    }

    public function registerSave(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ])->validate();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('login');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function loginAction(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ])->validate();

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed')
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        return redirect('/');
    }


    public function viewUser ($id)
{
    // Get the user with the given ID
    $user = User::find($id);

    // Check if the user exists
    if (!$user) {
        abort(404);
    }

    // Get the user's borrowed books
    $borrowedBooks = $user->borrowedBooks;

    // Return the view with the user data and borrowed books
    return view('user', compact('user', 'borrowedBooks'));
}


public function borrowBook(Request $request)
{
    // Get the user and book IDs from the request
    $userId = $request->input('user_id');
    $bookId = $request->input('book_id');

    // Get the user and book instances
    $user = User::find($userId);
    $book = Book::find($bookId);

    // Check if the user and book exist
    if (!$user || !$book) {
        return back()->withErrors(['error' => 'User  or book not found']);
    }

    // Borrow the book
    $user->borrowedBooks()->attach($book);

    // Return a success message
    return back()->withSuccess('Book borrowed successfully!');
}
    public function profile()
    {
        return view('profile');
    }
}

