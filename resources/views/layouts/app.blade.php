<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Luxury Boutique')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --soft-beige: #F5EFE6;
            --off-white: #FAF9F6;
            --dusty-rose: #D4A6A6;
            --slate-gray: #6C757D;
            --deep-navy: #2D3E50;
            --terracotta: #B4604D;
        }

        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-poppins { font-family: 'Poppins', sans-serif; }

        .hover-underline {
            position: relative;
            &::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                width: 0;
                height: 1px;
                background-color: var(--dusty-rose);
                transition: width 0.3s ease;
            }
            &:hover::after {
                width: 100%;
            }
        }
    </style>
</head>

<body class="bg-off-white font-poppins">
<!-- Header -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-5">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="font-playfair text-2xl text-deep-navy font-medium hover:scale-105 transition-transform">
                YourBrand
            </a>

            <!-- Navigation Menu (TIDAK DIUBAH STRUCTURNYA) -->
            <nav class="space-x-8 flex items-center">
                <a href="{{ route('home') }}" class="text-slate-gray hover-underline">Home</a>
                <a href="{{ route('products.index') }}" class="text-slate-gray hover-underline">Products</a>
                <a href="{{ route('cart.index') }}" class="text-slate-gray hover-underline">Cart</a>
                <a href="{{ route('checkout.index') }}" class="text-slate-gray hover-underline">Checkout</a>
                <a href="{{ route('orders.index') }}" class="text-slate-gray hover-underline">Orders</a>

                @guest
                    <a href="{{ route('login') }}" class="text-slate-gray hover-underline">Login</a>
                    <a href="{{ route('register') }}" class="text-slate-gray hover-underline">Register</a>
                @else
                    <span class="ml-4 text-slate-gray">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="text-slate-gray hover-underline ml-2">
                            Logout
                        </button>
                    </form>
                @endguest
            </nav>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="max-w-7xl mx-auto px-6 py-12">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-soft-beige border-t border-dusty-rose/20">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div class="space-y-4">
                <h4 class="font-playfair text-deep-navy text-lg font-medium">YourBrand</h4>
                <p class="text-slate-gray text-sm">Elevating everyday luxury through curated collections</p>
            </div>

            <!-- Links -->
            <div>
                <h4 class="font-playfair text-deep-navy text-lg font-medium mb-4">Quick Links</h4>
                <ul class="space-y-2 text-slate-gray text-sm">
                    <li><a href="{{ route('products.index') }}" class="hover:text-dusty-rose">New Arrivals</a></li>
                    <li><a href="{{ route('cart.index') }}" class="hover:text-dusty-rose">Shopping Cart</a></li>
                    <li><a href="{{ route('orders.index') }}" class="hover:text-dusty-rose">Order Tracking</a></li>
                </ul>
            </div>

            <!-- Policies -->
            <div>
                <h4 class="font-playfair text-deep-navy text-lg font-medium mb-4">Policies</h4>
                <ul class="space-y-2 text-slate-gray text-sm">
                    <li><a href="#" class="hover:text-dusty-rose">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-dusty-rose">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-dusty-rose">Return Policy</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-playfair text-deep-navy text-lg font-medium mb-4">Connect</h4>
                <div class="space-y-2 text-slate-gray text-sm">
                    <p>support@yourbrand.com</p>
                    <p>+1 (234) 567-890</p>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-dusty-rose/20 mt-8 pt-6 text-center text-slate-gray text-sm">
            © {{ date('Y') }} YourBrand. All rights reserved.
        </div>
    </div>
</footer>
</body>
</html>
