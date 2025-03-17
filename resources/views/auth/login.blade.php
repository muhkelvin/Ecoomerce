@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-off-white py-12">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 hover-scale transition-all duration-300">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <span class="font-playfair text-3xl text-deep-navy">Éclat</span>
            </div>

            <h1 class="font-playfair text-2xl text-center text-deep-navy mb-8">Welcome Back</h1>

            @if($errors->any())
                <div class="bg-terracotta/10 text-terracotta px-4 py-3 rounded-lg mb-6 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-slate-gray text-sm mb-2">Email</label>
                    <input type="email" name="email" required autofocus
                           class="w-full px-4 py-3 border border-soft-beige rounded-lg focus:ring-1 focus:ring-dusty-rose focus:border-dusty-rose placeholder-gray-300">
                </div>

                <div>
                    <label class="block text-slate-gray text-sm mb-2">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 border border-soft-beige rounded-lg focus:ring-1 focus:ring-dusty-rose focus:border-dusty-rose">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 text-sm text-slate-gray">
                        <input type="checkbox" name="remember" class="rounded border-soft-beige text-dusty-rose focus:ring-dusty-rose">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-terracotta hover:text-deep-navy">Forgot password?</a>
                </div>

                <button type="submit"
                        class="w-full bg-deep-navy text-off-white py-3 rounded-lg hover:bg-terracotta transition-colors font-medium">
                    Sign In
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-gray">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-terracotta hover:text-deep-navy font-medium">Create account</a>
            </p>
        </div>
    </div>
@endsection
