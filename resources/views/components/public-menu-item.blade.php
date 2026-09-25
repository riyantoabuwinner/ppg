@props(['item'])

@php
    $hasChildren = $item->activeChildren && $item->activeChildren->isNotEmpty();
    $isActive = $item->isActiveUrl();
@endphp

@if(!$hasChildren)
    <a href="{{ $item->getUrl() }}" 
       target="{{ $item->target }}" 
       class="nav-link text-sm font-medium transition inline-flex items-center space-x-1.5 py-1.5 px-3 rounded-xl {{ $isActive ? 'text-purple-700 font-bold bg-purple-50 dark:text-purple-300 dark:bg-purple-950/50' : 'text-gray-700 dark:text-slate-200 hover:text-purple-700 dark:hover:text-purple-300 hover:bg-purple-50/60 dark:hover:bg-slate-800/60' }}">
        @if($item->icon)
            <i class="{{ $item->icon }} text-xs opacity-80"></i>
        @endif
        <span>{{ $item->title }}</span>
        @if($item->target === '_blank')
            <i class="fas fa-external-link-alt text-[9px] opacity-60"></i>
        @endif
    </a>
@else
    {{-- Dropdown Container with Hover and Click Support --}}
    <div class="relative dropdown-group">
        <button type="button" 
                class="dropdown-toggle nav-link text-sm font-medium transition inline-flex items-center space-x-1.5 py-1.5 px-3 rounded-xl text-gray-700 dark:text-slate-200 hover:text-purple-700 dark:hover:text-purple-300 hover:bg-purple-50/60 dark:hover:bg-slate-800/60 focus:outline-none cursor-pointer"
                aria-haspopup="true"
                aria-expanded="false">
            @if($item->icon)
                <i class="{{ $item->icon }} text-xs opacity-80 text-purple-600 dark:text-purple-400"></i>
            @endif
            <span>{{ $item->title }}</span>
            <svg class="dropdown-chevron w-3.5 h-3.5 ml-0.5 text-gray-400 transition-transform duration-200" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        {{-- Dropdown Menu Panel --}}
        <div class="dropdown-menu absolute left-0 mt-1 w-64 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-purple-100 dark:border-slate-800 py-2.5 z-50">
            @foreach($item->activeChildren as $child)
                @php
                    $childHasSub = $child->activeChildren && $child->activeChildren->isNotEmpty();
                @endphp
                @if(!$childHasSub)
                    <a href="{{ $child->getUrl() }}" 
                       target="{{ $child->target }}"
                       class="flex items-center justify-between px-4 py-2.5 text-xs text-gray-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-slate-800/80 hover:text-purple-700 dark:hover:text-purple-300 transition group/item">
                        <span class="flex items-center space-x-2.5">
                            @if($child->icon)
                                <i class="{{ $child->icon }} text-purple-600 dark:text-purple-400 text-xs w-4 text-center"></i>
                            @else
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>
                            @endif
                            <span class="font-medium group-hover/item:font-semibold">{{ $child->title }}</span>
                        </span>
                        @if($child->target === '_blank')
                            <i class="fas fa-external-link-alt text-[9px] text-gray-400"></i>
                        @endif
                    </a>
                @else
                    {{-- Sub-submenu nested (Level 2) --}}
                    <div class="relative dropdown-subgroup">
                        <a href="{{ $child->getUrl() }}" 
                           class="flex items-center justify-between px-4 py-2.5 text-xs text-gray-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-slate-800/80 hover:text-purple-700 dark:hover:text-purple-300 transition">
                            <span class="flex items-center space-x-2.5">
                                @if($child->icon)
                                    <i class="{{ $child->icon }} text-purple-600 dark:text-purple-400 text-xs w-4 text-center"></i>
                                @endif
                                <span class="font-medium">{{ $child->title }}</span>
                            </span>
                            <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
                        </a>
                        <div class="dropdown-submenu absolute left-full top-0 ml-1.5 w-56 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-purple-100 dark:border-slate-800 py-2">
                            @foreach($child->activeChildren as $subChild)
                            <a href="{{ $subChild->getUrl() }}" 
                               target="{{ $subChild->target }}"
                               class="flex items-center space-x-2 px-4 py-2 text-xs text-gray-700 dark:text-slate-200 hover:bg-purple-50 dark:hover:bg-slate-800/80 hover:text-purple-700 dark:hover:text-purple-300 transition">
                                @if($subChild->icon)
                                    <i class="{{ $subChild->icon }} text-purple-600 dark:text-purple-400 text-xs w-3 text-center"></i>
                                @else
                                    <span class="w-1 h-1 rounded-full bg-purple-400"></span>
                                @endif
                                <span>{{ $subChild->title }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif
