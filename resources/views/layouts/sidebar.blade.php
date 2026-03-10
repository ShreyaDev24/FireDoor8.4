<?php
  $loginUser = Auth::user();
  ?>
<style>
    li.mm-active>a {
        color: white !important;
        -webkit-text-stroke: white !important;
    }

    li.mm-active>a .metismenu-icon i {
        color: white !important;
        -webkit-text-stroke: white !important;
    }

    li.submm-active>a {
        color: white !important;
        -webkit-text-stroke: white !important;
    }

    li.submm-active>a .metismenu-icon i {
        color: white !important;
        -webkit-text-stroke: white !important;
    }
</style>
<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                    data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">

                <li class="submm-{{ (Request::segment(2) == '') ? 'active' : ''}}">
                    <a href="{{route('Dashboard')}}">
                        <i class="metismenu-icon">
                            <i class="fa fa-dashboard"></i>
                        </i>
                        Dashboards
                    </a>
                </li>



                @if($loginUser->UserType=='1')

                <li class="mm-{{ (Request::segment(1) == 'company') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-building"></i>
                        </i>
                        Company
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'add') ? 'active' : ''}}">
                            <a href="{{route('company/add')}}">
                                <i class="metismenu-icon"></i>
                                Add Company
                            </a>
                        </li>

                        <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                            <a href="{{route('company/list')}}">
                                <i class="metismenu-icon"></i>
                                Company List
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                <li class="mm-{{ (Request::segment(1) == 'items') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-table"></i>
                        </i>
                        Architect
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('Architect/add')}}">
                                <i class="metismenu-icon"></i>
                                Add Architect
                            </a>
                        </li>
                        <li>
                            <a href="{{route('Architect/list')}}">
                                <i class="metismenu-icon"></i>
                                Architect List
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-table"></i>
                        </i>
                        Main Contractors
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('customer/add')}}">
                                <i class="metismenu-icon"></i>
                                Add New
                            </a>
                        </li>
                        <li>
                            <a href="{{route('contractor/list')}}">
                                <i class="metismenu-icon"></i>
                                List
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <li class="mm-{{ (Request::segment(1) == 'items') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-sitemap"></i>
                        </i>
                        Items
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'add') ? 'active' : ''}}">
                            <a href="{{route('items/add')}}">
                                <i class="metismenu-icon"></i>
                                Add item
                            </a>
                        </li>
                        <li class="submm-{{ (Request::segment(2) == 'non-configural-items') ? 'active' : ''}}">
                            <a href="{{route('non-configural-items/list')}}">
                                <i class="metismenu-icon"></i>
                                Non-Configural Items
                            </a>
                        </li>
                    </ul>
                </li> --}}


                <li class="mm-{{ (Request::segment(1) == 'project') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fas fa-project-diagram"></i>
                        </i>
                        Projects
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                            <a href="{{route('project/list')}}">
                                <i class="metismenu-icon"></i>
                                Project List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="mm-{{ (Request::segment(1) == 'quotation') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-building"></i>
                        </i>
                        Quotation
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                            <a href="{{route('quotation/list')}}">
                                <i class="metismenu-icon"></i>
                                Quotation List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="mm-{{ (Request::segment(1) == 'user') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-users"></i>
                        </i>
                        Users
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                            <a href="{{route('user/list')}}">
                                <i class="metismenu-icon"></i>
                                User List
                            </a>
                        </li>
                    </ul>
                </li>



                <li class="mm-{{ (Request::segment(1) == 'order') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-table"></i>
                        </i>
                        Orders
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'orderlist') ? 'active' : ''}}">
                            <a href="{{route('orderlist')}}">
                                <i class="metismenu-icon"></i>
                                Order List
                            </a>
                        </li>
                    </ul>
                </li>
                <li
                    class="mm-{{ (Request::segment(1) == 'setting') ? 'active' : ''}}{{ (Request::segment(1) == 'options') ? 'active' : ''}}{{ (Request::segment(1) == 'non-configural-items') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-cog"></i>
                        </i>
                        Setting
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'tooltip') ? 'active' : ''}}">
                            <a href="{{route('tooltip')}}">
                                <i class="metismenu-icon"></i>
                                Tooltip
                            </a>
                        </li>
                        <li class="submm-{{ (Request::segment(1) == 'non-configural-items') ? 'active' : ''}}">
                            <a href="{{route('non-configural-items/list')}}">
                                <i class="metismenu-icon"></i>
                                Non Configurable Items
                            </a>
                        </li>
                        <li class="mm-{{ (Request::segment(2) == 'ironmongery-info') ? 'active' : ''}}">
                            <a href="#" aria-expanded="true">
                                Ironmongery Info
                                <i class="metismenu-state-icon">
                                    <i class="fa fa-caret-down"></i>
                                </i>
                            </a>
                            <ul>
                                <li class="submm-{{ (Request::segment(3) == 'create') ? 'active' : ''}}">
                                    <a href="{{route('ironmongery-info/create')}}">Create</a>
                                </li>
                                <li class="submm-{{ (Request::segment(3) == 'records') ? 'active' : ''}}">
                                    <a href="{{route('ironmongery-info/records',[0])}}">List</a>
                                </li>

                            </ul>
                        </li>

                        {{-- Accoustics --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'accoustics') ? 'active' : '' }}">
                            <a href="{{ route('accoustics.index') }}">Accoustics</a>
                        </li>

                        {{-- Architrave Type --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Architrave-Type') ? 'active' : '' }}">
                            <a href="{{ route('Architrave-Type.index') }}">Architrave Type</a>
                        </li>

                        {{-- Colour List --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Colour-List') ? 'active' : '' }}">
                            <a href="{{ route('Colour-List.index') }}">Colour List</a>
                        </li>

                        {{-- Door Dimension --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Door-Dimension-Custom') ? 'active' : '' }}">
                            <a href="{{ route('Door-Dimension-Custom.index') }}">Door Dimension Custom</a>
                        </li>

                        {{-- Door Dimension --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Door-Dimension') ? 'active' : '' }}">
                            <a href="{{ route('Door-Dimension.index') }}">Door Dimension Standard</a>
                        </li>

                        {{-- Door Leaf Facing Value --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'door-leaf-facing') ? 'active' : '' }}">
                            <a href="{{ route('door-leaf-facing.index') }}">Door Leaf Facing Value</a>
                        </li>

                        {{-- Door Leaf Facing Value --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Finish-Cost') ? 'active' : '' }}">
                            <a href="{{ route('Finish-Cost.index') }}">Finish Cost</a>
                        </li>

                        {{-- Glass Type --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Glass-type') ? 'active' : '' }}">
                            <a href="{{ route('Glass-type.index') }}">Glass Type</a>
                        </li>

                        {{-- Glazing System --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Glazing-System') ? 'active' : '' }}">
                            <a href="{{ route('Glazing-System.index') }}">Glazing System</a>
                        </li>

                        {{-- Glass Glazing System --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Glass-Glazing-System') ? 'active' : '' }}">
                            <a href="{{ route('Glass-Glazing-System.index') }}">Glass Glazing System</a>
                        </li>

                        {{-- Intumescent Seal Arrangement --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Intumescent-Seal-Arrangement') ? 'active' : '' }}">
                            <a href="{{ route('Intumescent-Seal-Arrangement.index') }}">Intumescent Seal Arrangement</a>
                        </li>

                        {{-- Intumescent Seal Color --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Intumescent-Seal-Color') ? 'active' : '' }}">
                            <a href="{{ route('Intumescent-Seal-Color.index') }}">Intumescent Seal Colour</a>
                        </li>

                        {{-- Leaf Type --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'leaf-type') ? 'active' : '' }}">
                            <a href="{{ route('leaf-type.index') }}">Leaf Type</a>
                        </li>

                        {{-- Side Screen --}}
                        <li
                            class="mm-{{ (Request::segment(1) == 'options' && Request::segment(2) == 'Screen-Glass-Type') ? 'active' : ''}}">
                            <a href="#">
                                Side Screen
                                <i class="metismenu-state-icon"><i class="fa fa-caret-down"></i></i>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('Screen-Glass-Type.index') }}">Glass Type</a>
                                </li>
                                <li>
                                    <a href="{{ route('Screen-Glazing-Type.index') }}">Glazing System</a>
                                </li>
                            </ul>
                        </li>

                        {{-- Side Light / Fanlight --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Overpanel-Glass-Type') ? 'active' : '' }}">
                            <a href="{{ route('Overpanel-Glass-Type.index') }}">Side Light / Fanlight</a>
                        </li>

                        {{-- Timber Species --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Lipping-Species') ? 'active' : '' }}">
                            <a href="{{ route('Lipping-Species.index') }}">Timber Species</a>
                        </li>
                    </ul>
                </li>

                <li class="{{ request()->is('admin/support*') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.support.index') }}">
                        <i class="metismenu-icon">
                            <i class="fa fa-info-circle"></i>
                        </i>
                        Help Center
                    </a>
                </li>

                @endif



                @if($loginUser->UserType=='2' || $loginUser->UserType=='3')

                {{-- @if($loginUser->UserType=='2')
                <li class="mm-{{ (Request::segment(1) == 'items') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-table"></i>
                        </i>
                        Architect
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('Architect/add')}}">
                                <i class="metismenu-icon"></i>
                                Add Architect
                            </a>
                        </li>
                        <li>
                            <a href="{{route('Architect/list')}}">
                                <i class="metismenu-icon"></i>
                                Architect List
                            </a>
                        </li>
                    </ul>
                </li>
                @endif --}}

                {{-- @if(Auth::user()->UserType=='2')
                <li class="mm-{{ (Request::segment(1) == 'admins') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-building"></i>
                        </i>
                        Admin
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'add') ? 'active' : ''}}">
                            <a href="{{route('admins/add')}}">
                                <i class="metismenu-icon"></i>
                                Add Admin
                            </a>
                        </li>

                        <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                            <a href="{{route('admins/list')}}">
                                <i class="metismenu-icon"></i>
                                Admin List
                            </a>
                        </li>
                    </ul>
                </li>

                @endif --}}
                <li class="mm-{{ (Request::segment(1) == 'customer') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-users"></i>
                        </i>
                        Main Contractors
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'add') ? 'active' : ''}}">
                            <a href="{{route('customer/add')}}">
                                <i class="metismenu-icon"></i>
                                Add Main Contractors
                            </a>
                        </li>
                        <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                            <a href="{{route('contractor/list')}}">
                                <i class="metismenu-icon"></i>
                                Main Contractors List
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mm-{{ (Request::segment(1) == 'project') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fas fa-project-diagram"></i>
                        </i>
                        Projects
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'create') ? 'active' : ''}}">
                            <a href="{{route('project/create')}}">
                                <i class="metismenu-icon"></i>
                                Add Project
                            </a>
                        </li>
                        <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                            <a href="{{route('project/list')}}">
                                <i class="metismenu-icon"></i>
                                Project List
                            </a>
                        </li>
                        <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                            <a href="{{route('assign-projects')}}">
                                <i class="metismenu-icon">
                                    <i class="fa fa-table"></i>
                                </i>
                                Assigned Projects

                            </a>

                        </li>
                    </ul>
                </li>
                <li class="mm-{{ (Request::segment(1) == 'quotation') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-building"></i>
                        </i>
                        Quotation
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>

                        {{-- <li class="submm-{{ (Request::segment(2) == 'generate') ? 'active' : ''}}">
                            <a href="{{url('quotation/generate/0/0')}}">
                                <i class="metismenu-icon"></i>
                                New Quotation
                            </a>
                        </li> --}}
                        <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                            <a href="{{route('quotation/list')}}">
                                <i class="metismenu-icon"></i>
                                Quotation List
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mm-{{ Request::segment(1) == 'favorites' ? 'active' : '' }}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-heart"></i>
                        </i>
                        Favourite
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ Request::routeIs('favorites.index') ? 'active' : '' }}">
                            <a href="{{ route('favorites.index') }}">
                                <i class="metismenu-icon"></i>
                                Favourite List
                            </a>
                        </li>
                    </ul>
                </li>



                @if(Auth::user()->UserType=='2')

                <li class="mm-{{ (Request::segment(1) == 'survey') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-users"></i>
                        </i>
                        Survey
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'add') ? 'active' : ''}}">
                            <a href="{{route('survey/add')}}">
                                <i class="metismenu-icon"></i>
                                Survey User
                            </a>
                        </li>
                        <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                            <a href="{{route('survey/list')}}">
                                <i class="metismenu-icon"></i>
                                Survey Users
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mm-{{ (Request::segment(1) == 'user' ||Request::segment(1) == 'admins') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-users"></i>
                        </i>
                        Users
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <!-- <li class="submm-{{ (Request::segment(2) == 'add') ? 'active' : ''}}">
                                    <a href="{{route('user/add')}}">
                                        <i class="metismenu-icon"></i>
                                        Add User
                                    </a>
                                </li> -->

                        <li class="mm-{{ (Request::segment(2) == 'add') ? 'active' : ''}}">
                            <a href="#" aria-expanded="true">
                                Add
                                <i class="metismenu-state-icon">
                                    <i class="fa fa-caret-down"></i>
                                </i>
                            </a>
                            <ul>
                                <li
                                    class="submm-{{ (Request::segment(1) == 'admins' && Request::segment(2) == 'add') ? 'active' : ''}}">
                                    <a href="{{route('admins/add')}}">Add Admin</a>
                                </li>

                                <li
                                    class="submm-{{ (Request::segment(1) == 'user' && Request::segment(2) == 'add') ? 'active' : ''}}">
                                    <a href="{{route('user/add')}}">Add User</a>
                                </li>
                            </ul>
                        </li>

                        <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                            <a href="{{route('user/list')}}">
                                <i class="metismenu-icon"></i>
                                User List
                            </a>
                        </li>
                    </ul>
                </li>

                <li
                    class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected' || Request::segment(2) == 'select' || Request::segment(2) == 'selected1') || Request::segment(1) == 'options') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon"><i class="fa fa-check"></i></i>
                        Selected Option
                        <i class="metismenu-state-icon"><i class="fa fa-caret-down"></i></i>
                    </a>

                    <ul>

                        {{-- Accoustics --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'accoustics') ? 'active' : '' }}">
                            <a href="{{ route('accoustics.index') }}">Accoustics</a>
                        </li>

                        {{-- Architrave Type --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Architrave-Type') ? 'active' : '' }}">
                            <a href="{{ route('Architrave-Type.index') }}">Architrave Type</a>
                        </li>

                        {{-- Colour List --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Colour-List') ? 'active' : '' }}">
                            <a href="{{ route('Colour-List.index') }}">Colour List</a>
                        </li>

                        {{-- Door Dimension --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Door-Dimension-Custom') ? 'active' : '' }}">
                            <a href="{{ route('Door-Dimension-Custom.index') }}">Door Dimension Custom</a>
                        </li>

                        {{-- Door Dimension --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Door-Dimension') ? 'active' : '' }}">
                            <a href="{{ route('Door-Dimension.index') }}">Door Dimension Standard</a>
                        </li>

                        {{-- Door Leaf Facing Value --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'door-leaf-facing') ? 'active' : '' }}">
                            <a href="{{ route('door-leaf-facing.index') }}">Door Leaf Facing Value</a>
                        </li>

                        {{-- Door Leaf Facing Value --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Finish-Cost') ? 'active' : '' }}">
                            <a href="{{ route('Finish-Cost.index') }}">Finish Cost</a>
                        </li>

                        {{-- Glass Type --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Glass-type') ? 'active' : '' }}">
                            <a href="{{ route('Glass-type.index') }}">Glass Type</a>
                        </li>

                        {{-- Glazing System --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Glazing-System') ? 'active' : '' }}">
                            <a href="{{ route('Glazing-System.index') }}">Glazing System</a>
                        </li>

                        {{-- Glass Glazing System --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Glass-Glazing-System') ? 'active' : '' }}">
                            <a href="{{ route('Glass-Glazing-System.index') }}">Glass Glazing System</a>
                        </li>

                        {{-- Intumescent Seal Arrangement --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Intumescent-Seal-Arrangement') ? 'active' : '' }}">
                            <a href="{{ route('Intumescent-Seal-Arrangement.index') }}">Intumescent Seal Arrangement</a>
                        </li>

                        {{-- Intumescent Seal Color --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Intumescent-Seal-Color') ? 'active' : '' }}">
                            <a href="{{ route('Intumescent-Seal-Color.index') }}">Intumescent Seal Colour</a>
                        </li>

                        {{-- Leaf Type --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'leaf-type') ? 'active' : '' }}">
                            <a href="{{ route('leaf-type.index') }}">Leaf Type</a>
                        </li>

                        {{-- Side Screen --}}
                        <li
                            class="mm-{{ (Request::segment(1) == 'options' && Request::segment(2) == 'Screen-Glass-Type') ? 'active' : ''}}">
                            <a href="#">
                                Side Screen
                                <i class="metismenu-state-icon"><i class="fa fa-caret-down"></i></i>
                            </a>
                            <ul>
                                <li>
                                    <a href="{{ route('Screen-Glass-Type.index') }}">Glass Type</a>
                                </li>
                                <li>
                                    <a href="{{ route('Screen-Glazing-Type.index') }}">Glazing System</a>
                                </li>
                            </ul>
                        </li>

                        {{-- Side Light / Fanlight --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Overpanel-Glass-Type') ? 'active' : '' }}">
                            <a href="{{ route('Overpanel-Glass-Type.index') }}">Side Light / Fanlight</a>
                        </li>

                        {{-- Timber Species --}}
                        <li
                            class="submm-{{ (Request::segment(2) == 'options' && Request::segment(3) == 'Lipping-Species') ? 'active' : '' }}">
                            <a href="{{ route('Lipping-Species.index') }}">Timber Species</a>
                        </li>
                    </ul>
                </li>

                <li class="mm-{{ (Request::segment(1) == 'setting') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-cog"></i>
                        </i>
                        Setting
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'general') ? 'active' : ''}}">
                            <a href="{{route('generalSetting')}}">
                                <i class="metismenu-icon"></i>
                                General
                            </a>
                        </li>
                        <li class="submm-{{ (Request::segment(2) == 'mail-Format') ? 'active' : ''}}">
                            <a href="{{route('settingpdf')}}">
                                <i class="metismenu-icon"></i>
                                Mail Format
                            </a>
                        </li>
                        <li class="submm-{{ (Request::segment(2) == 'DoorFrameConstruction') ? 'active' : ''}}">
                            <a href="{{route('DoorFrameConstruction')}}">
                                <i class="metismenu-icon"></i>
                                Manufacturing Settings
                            </a>
                        </li>
                        {{-- <li class="submm-{{ (Request::segment(2) == 'quotation-prefix') ? 'active' : ''}}">
                            <a href="{{route('QuotationPrefix')}}">
                                <i class="metismenu-icon"></i>
                                Quotation Prefix
                            </a>
                        </li>
                        <li class="submm-{{ (Request::segment(2) == 'order-prefix') ? 'active' : ''}}">
                            <a href="{{route('OrderPrefix')}}">
                                <i class="metismenu-icon"></i>
                                Order Prefix
                            </a>
                        </li> --}}

                        <li class="mm-{{ (Request::segment(2) == 'buildofmaterial') ? 'active' : ''}}">
                            <a href="#" aria-expanded="true">
                                Bill Of Material
                                <i class="metismenu-state-icon">
                                    <i class="fa fa-caret-down"></i>
                                </i>
                            </a>
                            <ul>
                                <li class="submm-{{ (Request::segment(3) == 'generalsetting') ? 'active' : ''}}">
                                    <a href="{{route('settingbuildofmaterial')}}">General Setting</a>
                                </li>
                                {{-- <li class="submm-{{ (Request::segment(3) == 'costsetting') ? 'active' : ''}}">
                                    <a href="{{route('costsetting')}}">Cost Setting</a>
                                </li> --}}
                                <li
                                    class="submm-{{ (Request::segment(3) == 'general_labour_cost_setting') ? 'active' : ''}}">
                                    <a href="{{route('general_labour_cost_setting')}}">General Labour Cost Setting</a>
                                </li>
                            </ul>
                        </li>
                        <li class="mm-{{ (Request::segment(2) == 'non-configural-items') ? 'active' : ''}}">
                            <a href="{{route('non-configural-items/list')}}">
                                <i class="metismenu-icon"></i>
                                Non Configurable Items
                            </a>
                        </li>
                        {{-- <li class="mm-{{ (Request::segment(2) == 'ironmongery-list') ? 'active' : ''}}">
                            <a href="{{route('ironmongery-list')}}">
                                <i class="metismenu-icon"></i>
                                Ironmongery Set
                            </a>
                        </li> --}}

                        <li class="mm-{{ (Request::segment(2) == 'folders.index') ? 'active' : ''}}">
                            <a href="{{route('folders.index')}}">
                                <i class="metismenu-icon"></i>
                                Ironmongery Folder
                            </a>
                        </li>

                        <li class="mm-{{ (Request::segment(2) == 'ironmongery-info') ? 'active' : ''}}">
                            <a href="#" aria-expanded="true">
                                Ironmongery Info
                                <i class="metismenu-state-icon">
                                    <i class="fa fa-caret-down"></i>
                                </i>
                            </a>
                            <ul>
                                <li class="submm-{{ (Request::segment(3) == 'create') ? 'active' : ''}}">
                                    <a href="{{route('ironmongery-info/create')}}">Create</a>
                                </li>
                                <li class="submm-{{ (Request::segment(3) == 'records') ? 'active' : ''}}">
                                    <a href="{{route('ironmongery-info/records',[0])}}">List</a>
                                </li>
                                {{-- <li
                                    class="submm-{{ (Request::segment(3) == 'add-miscellaneous') ? 'active' : ''}}">
                                    <a href="{{route('ironmongery-info/add-miscellaneous')}}">Add Miscellaneous</a>
                                </li>
                                <li class="submm-{{ (Request::segment(3) == 'records-miscellaneous') ? 'active' : ''}}">
                                    <a href="{{route('ironmongery-info/records-miscellaneous',[0])}}">List
                                        Miscellaneous</a>
                                </li> --}}
                            </ul>
                        </li>

                    </ul>
                </li>
                @endif
                <li class="mm-{{ (Request::segment(1) == 'order') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-table"></i>
                        </i>
                        Orders
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'orderlist') ? 'active' : ''}}">
                            <a href="{{route('orderlist')}}">
                                <i class="metismenu-icon"></i>
                                Order List
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ request()->routeIs('help.center') ? 'mm-active' : '' }}">
                    <a href="{{ route('help.center') }}">
                        <i class="metismenu-icon">
                            <i class="fa fa-info-circle"></i>
                        </i>
                        Help Center
                    </a>
                </li>
                @endif

                @if($loginUser->UserType=='4')


                <li class="mm-{{ (Request::segment(1) == 'project') ? 'active' : ''}}">
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fas fa-project-diagram"></i>
                        </i>
                        Projects
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li class="submm-{{ (Request::segment(2) == 'create') ? 'active' : ''}}">
                            <a href="{{route('project/create')}}">
                                <i class="metismenu-icon"></i>
                                Add Project
                            </a>
                        </li>
                        <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                            <a href="{{route('project/list')}}">
                                <i class="metismenu-icon"></i>
                                Project List
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-table"></i>
                        </i>
                        Main Contractors
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('customer/add')}}">
                                <i class="metismenu-icon"></i>
                                Add New
                            </a>
                        </li>
                        <li>
                            <a href="{{route('contractor/list')}}">
                                <i class="metismenu-icon"></i>
                                List
                            </a>
                        </li>
                    </ul>
                </li>

                @endif


                @if($loginUser->UserType=='5')

                <li>
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-table"></i>
                        </i>
                        Assign door
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('form/item')}}">
                                <i class="metismenu-icon"></i>
                                Assign Door Comapny
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="metismenu-icon"></i>
                                Assigned Door list
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon">
                            <i class="fa fa-table"></i>
                        </i>
                        Project invitations
                        <i class="metismenu-state-icon">
                            <i class="fa fa-caret-down"></i>
                        </i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{route('project/invitation/list')}}">
                                <i class="metismenu-icon"></i>
                                Invitation List
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{route('project/list')}}">
                        <i class="metismenu-icon">
                            <i class="fa fa-table"></i>
                        </i>
                        Assigned Projects

                    </a>

                </li>


                @endif

            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    function company_details() {
        document.getElementById("companydetails").click();
    }

</script>
