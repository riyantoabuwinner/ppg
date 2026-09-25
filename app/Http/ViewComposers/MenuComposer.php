<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Menu;
use App\Models\MenuItem;

class MenuComposer
{
    /**
     * Bind data menu ke view.
     */
    public function compose(View $view): void
    {
        // 1. Menu Utama (Main Navbar)
        $mainMenu = Menu::location('main')->first();
        $mainMenuItems = collect();

        if ($mainMenu) {
            $mainMenuItems = MenuItem::where('menu_id', $mainMenu->id)
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->with(['activeChildren' => function ($query) {
                    $query->with(['page', 'category', 'activeChildren.page'])
                          ->orderBy('order_column');
                }, 'page', 'category'])
                ->orderBy('order_column')
                ->get();
        }

        // 2. Menu Atas (Top Utility Bar)
        $topMenu = Menu::location('top')->first();
        $topMenuItems = collect();

        if ($topMenu) {
            $topMenuItems = MenuItem::where('menu_id', $topMenu->id)
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->with(['activeChildren', 'page'])
                ->orderBy('order_column')
                ->get();
        }

        // 3. Menu Footer (Bawah)
        $footerMenu = Menu::location('footer')->first();
        $footerMenuItems = collect();

        if ($footerMenu) {
            $footerMenuItems = MenuItem::where('menu_id', $footerMenu->id)
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->with(['activeChildren', 'page'])
                ->orderBy('order_column')
                ->get();
        }

        $view->with([
            'mainMenuItems'   => $mainMenuItems,
            'topMenuItems'    => $topMenuItems,
            'footerMenuItems' => $footerMenuItems,
        ]);
    }
}
