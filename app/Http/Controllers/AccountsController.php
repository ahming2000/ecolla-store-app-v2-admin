<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AccountsController extends Controller
{

    /**
     * AccountsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

    }

    public function show(User $user){

    }

    public function edit(User $user){

    }

    public function store(){

    }

    public function update(User $user){

    }

    public function create(){

    }

    public function destroy(User $user){

    }

}
