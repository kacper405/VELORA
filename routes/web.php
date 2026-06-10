<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

$products = [
    'stylowa_koszulka' => [
        'name' => 'Stylowa koszulka',
        'description' => 'Klasyczna koszulka w modne wzory, idealna na co dzień.',
        'price' => 59.00,
        'image' => 'images/stylowa_koszulka.png',
    ],
    'ciepla_bluza' => [
        'name' => 'Ciepła bluza',
        'description' => 'Miękka bluza z kapturem, wygodny fason na chłodniejsze dni.',
        'price' => 129.00,
        'image' => 'images/ciepla_bluza.png',
    ],
    'modne_spodnie' => [
        'name' => 'Modne spodnie',
        'description' => 'Wygodne spodnie o nowoczesnym kroju, pasują do wielu stylizacji.',
        'price' => 99.00,
        'image' => 'images/modne_spodnie.png',
    ],
    'letnia_sukienka' => [
        'name' => 'Letnia sukienka',
        'description' => 'Zwiewna sukienka w jasnych kolorach, idealna na letnie dni.',
        'price' => 89.00,
        'image' => 'images/letnia_sukienka.png',
    ],
    'jeansowa_kurtka' => [
        'name' => 'Jeansowa kurtka',
        'description' => 'Klasyczna kurtka jeansowa pasująca do wielu casualowych stylizacji.',
        'price' => 149.00,
        'image' => 'images/jeansowa_kurtka.png',
    ],
    'wygodne_trampki' => [
        'name' => 'Wygodne trampki',
        'description' => 'Sportowe trampki w uniwersalnym stylu, wygodne do chodzenia codziennego.',
        'price' => 79.00,
        'image' => 'images/wygodne_trampki.png',
    ],
];

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect()->route('shop');
    }

    return back()->withErrors(['email' => 'Nieprawidłowy email lub hasło.'])->onlyInput('email');
})->name('login.submit');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', function (Request $request) {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $isAdmin = User::count() === 0;

    User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'is_admin' => $isAdmin,
    ]);

    return redirect()->route('home')->with('status', 'Rejestracja zakończona. Możesz się teraz zalogować.');
})->name('register.submit');

Route::middleware('auth')->group(function () use ($products) {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/shop', function () use ($products) {
        return view('shop', [
            'products' => $products,
        ]);
    })->name('shop');

    Route::post('/cart/add', function (Request $request) use ($products) {
        $data = $request->validate([
            'product_key' => ['required', 'string'],
        ]);

        if (!array_key_exists($data['product_key'], $products)) {
            abort(404);
        }

        $product = $products[$data['product_key']];
        $cart = session('cart', []);

        if (isset($cart[$data['product_key']])) {
            $cart[$data['product_key']]['quantity']++;
        } else {
            $cart[$data['product_key']] = [
                'product_key' => $data['product_key'],
                'product_name' => $product['name'],
                'price' => $product['price'],
                'quantity' => 1,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('status', 'Dodano do koszyka.');
    })->name('cart.add');

    Route::get('/cart', function () {
        $cart = session('cart', []);

        return view('cart', [
            'cart' => $cart,
        ]);
    })->name('cart');

    Route::post('/cart/clear', function (Request $request) {
        $request->session()->forget('cart');

        return redirect()->route('cart')->with('status', 'Koszyk wyczyszczony.');
    })->name('cart.clear');

    Route::post('/cart/checkout', function (Request $request) {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->withErrors(['cart' => 'Koszyk jest pusty.']);
        }

        $total = array_reduce($cart, function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $order = $user->orders()->create([
            'total' => $total,
            'status' => 'pending',
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_key' => $item['product_key'],
                'product_name' => $item['product_name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        $request->session()->forget('cart');

        return redirect()->route('cart')->with('status', 'Zamówienie zostało złożone.');
    })->name('cart.checkout');

    Route::get('/admin/orders', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->is_admin) {
            abort(403);
        }

        $orders = Order::with(['user', 'items'])->latest()->get();

        return view('admin.orders', [
            'orders' => $orders,
        ]);
    })->name('admin.orders');

    Route::post('/admin/orders/{order}/complete', function (Order $order) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->is_admin) {
            abort(403);
        }

        $order->update(['status' => 'completed']);

        return back()->with('status', 'Zamówienie zostało oznaczone jako zrealizowane.');
    })->name('admin.orders.complete');

    Route::post('/admin/orders/{order}/reject', function (Order $order) {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->is_admin) {
            abort(403);
        }

        $order->delete();

        return back()->with('status', 'Zamówienie zostało odrzucone i usunięte.');
    })->name('admin.orders.reject');

    Route::post('/logout', function (Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    })->name('logout');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');
