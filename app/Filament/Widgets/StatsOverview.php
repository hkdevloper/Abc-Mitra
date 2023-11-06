<?php

namespace App\Filament\Widgets;

use App\Filament\User\Resources\CompanyResource;
use App\Models\Blog;
use App\Models\Company;
use App\Models\Deal;
use App\Models\Event;
use App\Models\Job;
use App\Models\Product;
use Faker\Core\Color;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $prefix = '/user/dashboard';
        $companyUrl = '';
        $company = Company::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
        if ($company) {

            $companyUrl = "dashboard/companies/$company->id";
        }
        if (auth()->user()->type == 'Admin') {
            $companyUrl = "admin/companies";
            $prefix = 'admin';
        }
        return [
            Stat::make('Companies', function () {
                if (auth()->user()->type == 'Admin') {
                    return Company::count();
                } else {
                    return Company::where('user_id', auth()->user()->id)->count();
                }
            })
                ->color('#363062')
                ->url($companyUrl)
                ->icon('heroicon-o-building-office-2'),
            Stat::make('Products', function () {
                if (auth()->user()->type == 'Admin') {
                    return Product::count();
                } else {
                    return Product::where('user_id', auth()->user()->id)->count();
                }
            })
                ->url("$prefix/products")
                ->icon('heroicon-o-shopping-cart'),
            Stat::make('Events', function () {
                if (auth()->user()->type == 'Admin') {
                    return Event::count();
                } else {
                    return Event::where('user_id', auth()->user()->id)->count();
                }
            })
                ->url("$prefix/events")
                ->icon('heroicon-o-calendar'),
            Stat::make('Jobs', function () {
                if (auth()->user()->type == 'Admin') {
                    return Job::count();
                } else {
                    return Job::where('user_id', auth()->user()->id)->count();
                }
            })
                ->url("$prefix/jobs")
                ->icon('heroicon-o-briefcase'),
            Stat::make('Blogs', function () {
                if (auth()->user()->type == 'Admin') {
                    return Blog::count();
                } else {
                    return Blog::where('user_id', auth()->user()->id)->count();
                }
            })
                ->url("$prefix/blogs")
                ->icon('heroicon-o-newspaper'),
            Stat::make('Deals', function () {
                if (auth()->user()->type == 'Admin') {
                    return Deal::count();
                } else {
                    return Deal::where('user_id', auth()->user()->id)->count();
                }
            })
                ->url("$prefix/deals")
                ->icon('heroicon-o-tag'),
        ];
    }
}
