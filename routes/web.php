<?php

use App\classes\HelperFunctions;
use App\Http\Controllers\UserBlogController;
use App\Http\Controllers\UserCompanyController;
use App\Http\Controllers\UserDealController;
use App\Http\Controllers\UserEventController;
use App\Http\Controllers\UserForumController;
use App\Http\Controllers\UserJobController;
use App\Http\Controllers\UserProductController;
use App\Models\BlockedDomain;
use App\Models\BlogComments;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\Event;
use App\Models\Requirement;
use App\Models\Subscribe;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::prefix('test')->group(function () {
    Route::get('/', function () {
        $item = Product::find(1);
        return $item->getReviews();
    });
});


Route::get('/', function () {
    // Forgot session
    Session::forget('menu');
    // Store Session for Home Menu Active
    Session::put('menu', 'home');
    $p = Product::where('is_approved', 1)->where('is_active', 1)->where('is_featured', 1)->get();
    $c = Company::where('is_approved', 1)->where('is_active', 1)->where('is_featured', 1)->get();
    $e = Event::where('is_approved', 1)->where('is_active', 1)->where('is_featured', 1)->get();
    $categories = Category::where('is_active', 1)
        ->whereIn('type', ['company', 'product', 'event', 'blog', 'job', 'forum'])
        ->orderByDesc('is_featured') // Prioritize featured categories
        ->orderByDesc(function ($query) {
            $query->selectRaw('COUNT(*)')
                ->from('products')
                ->whereColumn('categories.id', 'products.category_id');
        })
        ->take(5) // Retrieve at least six categories
        ->get();

// If the retrieved categories are less than six, fetch additional categories as needed.
    $remainingCategoriesCount = 6 - $categories->count();
    if ($remainingCategoriesCount > 0) {
        $remainingCategories = Category::where('is_active', 1)
            ->whereIn('type', ['company', 'product', 'event', 'blog', 'job', 'forum'])
            ->orderByDesc('is_featured')
            ->orderByDesc(function ($query) {
                $query->selectRaw('COUNT(*)')
                    ->from('products')
                    ->whereColumn('categories.id', 'products.category_id');
            })
            ->take($remainingCategoriesCount)
            ->get();

        $categories = $categories->merge($remainingCategories);
    }

    $category = $categories;
    $searchList = [];

    foreach ($p as $item) {
        $searchList[] = $item->name;
    }
    foreach ($c as $item) {
        $searchList[] = $item->name;
    }
    // Get only products which have a company
    $products = $p->filter(function ($value, $key) {
        return $value->company != null;
    });
    // get the 10-random records from the database if is less than 10 then it will return all
    if (count($p) > 8) {
        $products = $products->random(8);
    }

    if (count($c) > 8) {
        $companies = $c->random(8);
    } else {
        $companies = $c;
    }

    if (count($e) > 4) {
        $events = $e->random(4);
    } else {
        $events = $e;
    }

    $data = compact('products', 'companies', 'events', 'category', 'searchList');
    return view('welcome')->with($data);
})->name('home');

Route::prefix('auth')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('auth.login');
    Route::get('register', function () {
        return view('auth.register');
    })->name('auth.register');
    Route::post('login', function (Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/user');
        }
        return redirect()->back()->with('error', 'Invalid credentials');
    })->name('auth.login');
    Route::post('logout', function () {
        Auth::logout();
        return redirect()->back();
    });
    Route::post('register', function (Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);
        // Spam Email Check
        $spamEmails = BlockedDomain::where('status', 1)->pluck('domain')->toArray();
        foreach ($spamEmails as $spamEmail) {
            if (str_contains(strtolower($request->email), strtolower($spamEmail))) {
                return redirect()->back()->with('error', 'Invalid email');
            }
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->saveOrFail();
        Auth::login($user);
        return redirect()->back()->with('success', 'Registered successfully');
    })->name('auth.register');
});

Route::prefix('company')->group(function () {
    Route::get('/', [UserCompanyController::class, 'viewCompanyList'])->name('company');
    Route::get('/{slug}', [UserCompanyController::class, 'viewCompanyDetails'])->name('view.company');
});

Route::prefix('product')->group(function () {
    Route::get('/', [UserProductController::class, 'viewProductList'])->name('products');
    Route::get('/{slug}', [UserProductController::class, 'viewProductDetails'])->name('view.product');
});

Route::prefix('event')->group(function () {
    Route::get('/', [UserEventController::class, 'viewEventList'])->name('events');
    Route::get('/{slug}', [UserEventController::class, 'viewEventDetails'])->name('view.event');
});

Route::prefix('blog')->group(function () {
    Route::get('/', [UserBlogController::class, 'viewBlogList'])->name('blogs');
    Route::get('/{slug}', [UserBlogController::class, 'viewBlogDetails'])->name('view.blog');
});

Route::prefix('deal')->group(function () {
    Route::get('/', [UserDealController::class, 'viewDealList'])->name('deals');
    Route::get('/{slug}', [UserDealController::class, 'viewDealDetails'])->name('view.deal');
});

Route::prefix('job')->group(function () {
    Route::get('/', [UserJobController::class, 'viewJobList'])->name('jobs');
    Route::get('/{slug}', [UserJobController::class, 'viewJobDetails'])->name('view.job');
});

Route::prefix('forum')->group(function () {
    Route::get('/', [UserForumController::class, 'viewForumList'])->name('forum');
    Route::get('/{id}/{title}', [UserForumController::class, 'viewForumDetails'])->name('view.forum');
    Route::post('/answer-forum', [UserForumController::class, 'answerForum'])->name('forum.reply');
});

Route::post('/requirements/submit', function (Request $request) {
    // upload images to server and get the path
    $images = [];

    if ($request->has('b64_img_1')) {
        $images[] = HelperFunctions::storeBase64Image($request->input('b64_img_1'), 'requirements', time());
    }
    if ($request->has('b64_img_2')) {
        $images[] = HelperFunctions::storeBase64Image($request->input('b64_img_2'), 'requirements', time());
    }
    if ($request->has('b64_img_3')) {
        $images[] = HelperFunctions::storeBase64Image($request->input('b64_img_3'), 'requirements', time());
    }
    $requirement = new Requirement();
    $requirement->subject = $request->subject;
    $requirement->country = $request->country;
    $requirement->customer_name = $request->customer_name;
    $requirement->email = $request->email;
    $requirement->phone = $request->phone;
    $requirement->description = $request->description;
    $requirement->images = json_encode($images);
    $requirement->status = 'Pending';
    $requirement->saveOrFail();
    return redirect()->back()->with('success', 'Requirement submitted successfully');
})->name('requirements.submit');

Route::post('subscribe', function (Request $request) {
    $email = $request->email;
    $subscribe = new Subscribe();
    $subscribe->email = $email;
    $subscribe->saveOrFail();
    return redirect()->back()->with('success', 'Subscribed successfully');
})->name('subscribe');

Route::post('comment', function (Request $request) {
    $request->validate([
        'comment' => 'required',
        'name' => 'required',
        'user_id' => 'required',
        'blog_id' => 'required',
    ]);
    $comment = new BlogComments();
    $comment->comment = $request->comment;
    $comment->name = $request->name;
    $comment->email = "";
    $comment->user_id = $request->user_id;
    $comment->blog_id = $request->blog_id;
    $comment->saveOrFail();
    return redirect()->back()->with('success', 'Commented successfully');
})->name('blog.comment.submit');

Route::get('/search', function (Request $request) {
    $search = $request->q;
    $products = DB::table('products as p')
        ->join('seo as s', 'p.id', '=', 's.id')
        ->join('categories as c', 'p.category_id', '=', 'c.id')
        ->select('p.*', 'c.name as category_name')
        ->where('p.is_approved', 1)
        ->where('p.is_active', 1)
        ->where('s.meta_keywords', 'LIKE', '%' . $search . '%')
        ->paginate(10);

    $data = compact('products');
    return view('search')->with($data);
})->name('search');

Route::get('/categories', function (Request $request){
    // Clear session
    Session::forget('menu');
    $type = Category::pluck('type')->unique();
    return view('category', compact('type'));
})->name('categories');
