<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('client.home.index');
});

Route::get('/branches', function () {
    return view('client.branches.index');
});

Route::get('/branches/{id}', function ($id) {
    return view('client.branches.show', compact('id'));
});

Route::get('/booking', function () {
    return view('client.bookings.create');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
});

Route::get('/reset-password', function () {
    return view('auth.reset-password');
});

Route::get('/profile/edit', function () {
    return view('client.profile.edit');
});

Route::get('/pets/create', function () {
    return view('client.pets.create');
});

Route::get('/pets/{id}/edit', function ($id) {
    return view('client.pets.edit', compact('id'));
});
Route::get('/profile', function () {
    return view('client.profile.index');
});

Route::get('/pets', function () {
    return view('client.pets.index');
});

Route::get('/bookings', function () {
    return view('client.bookings.index');
});

Route::get('/payment', function () {
    return view('client.payments.create');
});

Route::get('/rooms/dog', function () {
    return view('client.rooms.dog');
});

Route::get('/rooms/cat', function () {
    return view('client.rooms.cat');
});

Route::get('/policies', function () {
    return view('client.policies.index');
});

Route::get('/services/grooming', function () {
    return view('client.services.grooming');
});