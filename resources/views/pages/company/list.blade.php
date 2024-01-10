@extends('layouts.user')

@section('head')
    <style>
        /* Existing styles remain unchanged */

        /* New styles for header */
        .header {
            padding: 1rem;
            color: #fff;
            text-align: center;
        }

        /* New styles for filter options */
        .filter-options {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-bottom: 1rem;
            color: black;
        }

        /* New styles for card list layout */
        .company-card {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        /* Additional responsive styles for card list layout */
        @media (min-width: 768px) {
            .company-card {
                flex-direction: row;
            }
        }
    </style>
@endsection

@section('content')
    <x-user.bread-crumb :data="['Home', 'Company', 'List']"/>
    <div class="container flex items-center justify-between my-8">
        {{-- Category Filter--}}
        <div class="">
            <label for="product-category-filter" class="text-gray-500">Filter by Category</label>
            <select name="category" id="product-category-filter" class="border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm text-gray-500 w-[150px]" onchange="doFilter()">
                <option value="all" selected>All</option>
                @foreach($categories as $category)
                    @if(request()->get('category') == $category->name)
                        <option selected value="{{ $category->name }}">{{ $category->name }}</option>
                    @else
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        {{-- Total Products --}}
        <div class="">
            <p>
                Showing {{ $companies->firstItem() }} - {{ $companies->lastItem() }} of {{ $companies->total() }} results
            </p>
        </div>
        {{-- Sort By --}}
        <div class="">
            <label for="product-sort-by" class="text-gray-500">Sort By</label>
            <select name="sort" id="product-sort-by" class="border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm text-gray-500 w-[150px]" onchange="doSort()">
                <option value="name" @if(request()->get('sort') == 'name') selected @endif>Name</option>
                <option value="price-low-to-high" @if(request()->get('sort') == 'price-low-to-high') selected @endif>Price low to high</option>
                <option value="price-high-to-low" @if(request()->get('sort') == 'price-high-to-low') selected @endif>Price high to low</option>
            </select>
        </div>
    </div>
    <script>
        function doSort() {
            window.location.href = '{{route('products')}}?sort=' + document.getElementById('product-sort-by').value;
        }

        function doFilter() {
            let value = document.getElementById('product-category-filter').value;
            if (value !== 'all'){
                window.location.href = '{{route('products')}}?category=' + document.getElementById('product-category-filter').value;
            }else{
                window.location.href = '{{route('products')}}'
            }
        }
    </script>
    <div class="container py-6 mx-auto">
        <!-- Existing content remains unchanged -->
        @forelse($companies as $company)
            <div class="company-card bg-white rounded-lg shadow-md overflow-hidden transform transition-transform duration-300 ease-in-out hover:-translate-y-2 flex items-center justify-center p-2">
                <div class="mb-4 p-2">
                    <img class="w-full h-40 object-contain overflow-hidden" src="{{ url('storage/' . $company->logo) }}"
                         alt="">
                </div>
                <ul class="w-full mx-3">
                    <li class="flex flex-nowrap items-center">
                        <span class="text-2xl mr-3">{{$company->name}}</span>
                        @if($company->is_featured)
                            <span>
                                        <button
                                            class="inline-flex items-center bg-neutral-100 mr-1 text-white border border-solid-400 rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor"
                                                 class="w-4 h-4 text-white bg-green-500">
                                              <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                                            </svg>
                                            <span class="mx-1 text-gray-500 text-xs">Featured</span>
                                        </button>
                                    </span>
                        @endif
                        @if(!$company->is_active)
                            <span>
                                        <button
                                            class="inline-flex items-center bg-neutral-100 mr-1 text-white border border-solid-400 rounded">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor"
                                                 class="w-4 h-4 text-white bg-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                            </svg>
                                            <span class="mx-1 text-gray-500 text-xs">In Active</span>
                                        </button>
                                    </span>
                        @endif
                    </li>
                    <li class="text-base text-gray-500">
                        <i class='bx bx-been-here text-red-500'></i> {{$company->address->state->name}}, {{$company->address->country->name}}
                    </li>
                    <li class="w-full flex items-center">
                        <button class="inline-flex items-center mr-1 text-gray-500">
                            <i class='bx bxs-star text-green-400 text-sm'></i>
                            <span class="mx-1 text-gray-500 text-sm">4.5</span>
                            <span class="mx-1 text-gray-500 text-sm">(1 Review)</span>
                        </button>
                    </li>
                    <li>
                        <p class="text-gray-500 text-sm"><span class="font-bold">Deals In</span>:
                            @forelse($company->extra_things as $item)
                                @php
                                    $limitedText = Str::limit($item, 80, '...');
                                @endphp
                                <span class="text-gray-500 text-sm">
                                            {{ $limitedText }}
                                    @if (strlen($item) > 80)
                                        <a href="#" class="text-blue-500"
                                           onclick="showFullText(this)">...More</a>
                                        <span class="full-text" style="display: none;">{{ $item }}</span>
                                    @endif
                                    @if (!$loop->last)
                                        |
                                    @endif
                                        </span>
                            @empty
                                <span class="text-gray-500 text-sm">No Products</span>
                            @endforelse
                            <script>
                                function showFullText(link) {
                                    let fullTextSpan = link.nextElementSibling;
                                    link.style.display = 'none';
                                    fullTextSpan.style.display = 'inline';
                                }
                            </script>
                        </p>
                    </li>
                </ul>
                <div class="w-[calc(20%-1rem)]">
                    <a href="{{ route('view.company', [$company->slug]) }}"
                       class="text-purple-500 bg-purple-100 hover:bg-purple-500 hover:text-white rounded-full p-1 mt-1 transition duration-300 ease-in-out flex items-center justify-center transform hover:-translate-y-1 hover:scale-60 text-center">
                        View Profile &nbsp;
                        <i class='bx bx-link-external text-2xl mr-2'></i>
                    </a>
                </div>
            </div>
        @empty
            <h1 class="text-gray-500 text-4xl text-center mt-10">No Company Found</h1>
        @endforelse
        <!-- Pagination -->
        {{ $companies->links() }}
    </div>
@endsection
