<?php
  $loginUser = Auth::user();
  ?>
<style>
    li.mm-active > a {
        color:white !important;
        -webkit-text-stroke:white !important;
    }
    li.mm-active > a .metismenu-icon i {
        color:white !important;
        -webkit-text-stroke:white !important;
    }
    li.submm-active > a {
        color:white !important;
        -webkit-text-stroke:white !important;
    }
    li.submm-active > a .metismenu-icon i {
        color:white !important;
        -webkit-text-stroke:white !important;
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
                                    Architect  List
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
                    {{--  <li class="mm-{{ (Request::segment(1) == 'items') ? 'active' : ''}}">
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
                    </li>  --}}


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
                    <li class="mm-{{ (Request::segment(1) == 'setting') ? 'active' : ''}}{{ (Request::segment(1) == 'options') ? 'active' : ''}}{{ (Request::segment(1) == 'non-configural-items') ? 'active' : ''}}">
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
                            {{--  <li class="submm-{{ (Request::segment(2) == 'edit-configurable-door-formula') ? 'active' : ''}}">
                                <a href="{{route('edit-configurable-door-formula')}}">
                                    <i class="metismenu-icon"></i>
                                    Configurable Door Formula
                                </a>
                            </li>  --}}
                            <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected')) ? 'active' : ''}}">
                                <a href="#">
                                    <i class="metismenu-icon">
                                        <i class="fa fa-check"></i>
                                    </i>
                                    intumescent Seal Arrangement
                                    <i class="metismenu-state-icon">
                                        <i class="fa fa-caret-down"></i>
                                    </i>
                                </a>
                                <ul>
                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'intumescentSealArrangementCustome') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['intumescentSealArrangementCustome'])}}">
                                            <i class="metismenu-icon"></i>
                                            Custom intumescent Seal Arrangement
                                        </a>
                                    </li>
                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'intumescentSealArrangement') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['intumescentSealArrangement'])}}">
                                            <i class="metismenu-icon"></i>
                                           Standard intumescent Seal Arrangement
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            {{--  <li class="mm-{{ (Request::segment(1) == 'colors') ? 'active' : ''}}">
                                <a href="#" aria-expanded="true">
                                    Colors
                                    <i class="metismenu-state-icon">
                                        <i class="fa fa-caret-down"></i>
                                    </i>
                                </a>
                                <ul>
                                    <li class="submm-{{ (Request::segment(2) == 'create-color') ? 'active' : ''}}">
                                        <a href="{{route('create-color')}}">Add Colour</a>
                                    </li>
                                    <li class="submm-{{ (Request::segment(2) == 'color-list') ? 'active' : ''}}">
                                        <a href="{{route('color-list')}}">Colour List</a>
                                    </li>
                                </ul>
                            </li>  --}}
                            {{--  <li class="mm-{{ (Request::segment(1) == 'options') ? 'active' : ''}}">
                                <a href="#" aria-expanded="true">
                                    Field Option
                                    <i class="metismenu-state-icon">
                                        <i class="fa fa-caret-down"></i>
                                    </i>
                                </a>
                                <ul>
                                    <li class="submm-{{ (Request::segment(2) == 'add') ? 'active' : ''}}">
                                        <a href="{{route('options/add',0)}}">Add Option</a>
                                    </li>
                                    <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                                        <a href="{{route('options/list',1)}}">Option List</a>
                                    </li>
                                </ul>
                            </li>  --}}
                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'leaf_type') ? 'active' : ''}}">
                                <a href="{{route('options/selected',['leaf_type'])}}">
                                    <i class="metismenu-icon"></i>
                                    Leaf Type
                                </a>
                            </li>
                            <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected')) ? 'active' : ''}}">
                                <a href="#">
                                    <i class="metismenu-icon">
                                        <i class="fa fa-check"></i>
                                    </i>
                                    Glass Type
                                    <i class="metismenu-state-icon">
                                        <i class="fa fa-caret-down"></i>
                                    </i>
                                </a>
                                <ul>
                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'leaf1_glass_type_custome') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['leaf1_glass_type_custome'])}}">
                                            <i class="metismenu-icon"></i>
                                            Custom Glass Type
                                        </a>
                                    </li>
                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'leaf1_glass_type') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['leaf1_glass_type'])}}">
                                            <i class="metismenu-icon"></i>
                                           Standard Glass Type
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'door_leaf_facing_value') ? 'active' : ''}}">
                                <a href="{{route('options/selected',['door_leaf_facing_value'])}}">
                                    <i class="metismenu-icon"></i>
                                    Door Leaf Facing Value
                                </a>
                            </li>
                            <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected')) ? 'active' : ''}}">
                                <a href="#">
                                    <i class="metismenu-icon">
                                        <i class="fa fa-check"></i>
                                    </i>
                                    Glazing System
                                    <i class="metismenu-state-icon">
                                        <i class="fa fa-caret-down"></i>
                                    </i>
                                </a>
                                <ul>
                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'leaf1_glazing_systems_custome') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['leaf1_glazing_systems_custome'])}}">
                                            <i class="metismenu-icon"></i>
                                            Custom Glazing System
                                        </a>
                                    </li>
                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'leaf1_glazing_systems') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['leaf1_glazing_systems'])}}">
                                            <i class="metismenu-icon"></i>
                                           Standard Glazing System
                                        </a>
                                    </li>
                                </ul>
                        </li>
                            <li class="submm-{{ (Request::segment(2) == 'filter' && Request::segment(3) == 'leaf1_glazing_systems') ? 'active' : ''}}">
                                <a href="{{route('options/filter',['leaf1_glazing_systems','Halspan'])}}">
                                    <i class="metismenu-icon"></i>
                                    Glass Glazing System
                                </a>
                            </li>
                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'Accoustics') ? 'active' : ''}}">
                                <a href="{{route('options/selected',['Accoustics'])}}">
                                    <i class="metismenu-icon"></i>
                                    Acoustics
                                </a>
                            </li>
                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'Architrave_Type') ? 'active' : ''}}">
                                <a href="{{route('options/selected',['Architrave_Type'])}}">
                                    <i class="metismenu-icon"></i>
                                    Architrave
                                </a>
                            </li>
                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'Intumescent_Seal_Color') ? 'active' : ''}}">
                                <a href="{{route('options/selected',['Intumescent_Seal_Color'])}}">
                                    <i class="metismenu-icon"></i>
                                    Intumescent Seal Color
                                </a>
                            </li>
                            <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected1' && Request::segment(3) == 'color_list')) ? 'active' : ''}}">
                                <a href="#">
                                    <i class="metismenu-icon">
                                        <i class="fa fa-check"></i>
                                    </i>
                                    Colour List
                                    <i class="metismenu-state-icon">
                                        <i class="fa fa-caret-down"></i>
                                    </i>
                                </a>
                                <ul>
                                    <li class="submm-{{ (Request::segment(2) == 'selected1' && Request::segment(3) == 'color_list') ? 'active' : ''}}">
                                        <a href="{{route('options/selected1',['color_list','Kraft_Paper'])}}">
                                            <i class="metismenu-icon"></i>
                                            Kraft Paper
                                        </a>
                                    </li>
                                    <li class="submm-{{ (Request::segment(2) == 'selected1' && Request::segment(3) == 'color_list') ? 'active' : ''}}">
                                        <a href="{{route('options/selected1',['color_list','Laminate'])}}">
                                            <i class="metismenu-icon"></i>
                                            Laminate
                                        </a>
                                    </li>
                                    <li class="submm-{{ (Request::segment(2) == 'selected1' && Request::segment(3) == 'color_list') ? 'active' : ''}}">
                                        <a href="{{route('options/selected1',['color_list','PVC'])}}">
                                            <i class="metismenu-icon"></i>
                                            PVC
                                        </a>
                                    </li>
                                    {{--  <li class="submm-{{ (Request::segment(2) == 'selected1' && Request::segment(3) == 'color_list') ? 'active' : ''}}">
                                        <a href="{{route('options/selected1',['color_list','Veneer'])}}">
                                            <i class="metismenu-icon"></i>
                                            Veneer
                                        </a>
                                    </li>
                                    <li class="submm-{{ (Request::segment(2) == 'selected1' && Request::segment(3) == 'color_list') ? 'active' : ''}}">
                                        <a href="{{route('options/selected1',['color_list','Painted'])}}">
                                            <i class="metismenu-icon"></i>
                                            Painted
                                        </a>
                                    </li>  --}}
                                </ul>
                            </li>
                            <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected')) ? 'active' : ''}}">
                                <a href="#">
                                    <i class="metismenu-icon">
                                        <i class="fa fa-check"></i>
                                    </i>
                                    Side Light / Fanlight
                                    <i class="metismenu-state-icon">
                                        <i class="fa fa-caret-down"></i>
                                    </i>
                                </a>
                                <ul>
                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'Door_Leaf_Facing') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['Overpanel_Glass_Type'])}}">
                                            <i class="metismenu-icon"></i>
                                            Glass Type
                                        </a>
                                    </li>
                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'door_leaf_finish') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['Overpanel_Glazing_System'])}}">
                                            <i class="metismenu-icon"></i>
                                            Glazing System
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="submm-{{ (Request::segment(2) == 'lipping-species') ? 'active' : ''}}">
                                <a href="{{route('lippingSpecies')}}">
                                    <i class="metismenu-icon"></i>
                                    Timber Species
                                </a>
                            </li>
                            {{--  <li class="mm-{{ (Request::segment(2) == 'ironmongery-list') ? 'active' : ''}}">
                                <a href="{{route('ironmongery-list')}}">
                                    <i class="metismenu-icon"></i>
                                    Ironmongery Set
                                </a>
                            </li>  --}}
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
                            {{--  <li>
                                <a href="{{route('door-dimension-list',1)}}">
                                    <i class="metismenu-icon">
                                        <i class="fa fa-table"></i>
                                    </i>
                                Door Dimension
                                    <i class="metismenu-state-icon">
                                        <i class="fa fa-caret-down"></i>
                                    </i>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{route('door-dimension-add')}}">
                                            <i class="metismenu-icon"></i>
                                            Add New
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('door-dimension-list',1)}}">
                                            <i class="metismenu-icon"></i>
                                        List
                                        </a>
                                    </li>
                                </ul>
                            </li>  --}}
                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'door_dimension') ? 'active' : ''}}">
                                <a href="{{route('options/selected',['door_dimension'])}}">
                                    <i class="metismenu-icon"></i>
                                    Door Dimension
                                </a>
                            </li>

                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'door_dimension_custome') ? 'active' : ''}}">
                                <a href="{{route('options/selected',['door_dimension_custome'])}}">
                                    <i class="metismenu-icon"></i>
                                    Door Dimension Custom
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#">
                            <i class="metismenu-icon">
                                <i class="fa fa-info-circle"></i>
                            </i>
                            Help Center
                        </a>
                    </li>
                @endif



                @if($loginUser->UserType=='2' || $loginUser->UserType=='3')

                    {{--  @if($loginUser->UserType=='2')
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
                                    Architect  List
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif  --}}

                 {{--  @if(Auth::user()->UserType=='2')
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

                            {{--  <li class="submm-{{ (Request::segment(2) == 'generate') ? 'active' : ''}}">
                                <a href="{{url('quotation/generate/0/0')}}">
                                    <i class="metismenu-icon"></i>
                                    New Quotation
                                </a>
                            </li>  --}}
                            <li class="submm-{{ (Request::segment(2) == 'list') ? 'active' : ''}}">
                                <a href="{{route('quotation/list')}}">
                                    <i class="metismenu-icon"></i>
                                    Quotation List
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
                                        <li class="submm-{{ (Request::segment(1) == 'admins' && Request::segment(2) == 'add') ? 'active' : ''}}">
                                            <a href="{{route('admins/add')}}">Add Admin</a>
                                        </li>

                                        <li class="submm-{{ (Request::segment(1) == 'user' && Request::segment(2) == 'add') ? 'active' : ''}}">
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


                        <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected' || Request::segment(2) == 'select' || Request::segment(2) == 'selected1')) ? 'active' : ''}}">
                            <a href="#">
                                <i class="metismenu-icon">
                                    <i class="fa fa-check"></i>
                                </i>
                                Selected Option
                                <i class="metismenu-state-icon">
                                    <i class="fa fa-caret-down"></i>
                                </i>
                            </a>
                            <ul>
                                <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'leaf_type') ? 'active' : ''}}">
                                    <a href="{{route('options/selected',['leaf_type'])}}">
                                        <i class="metismenu-icon"></i>
                                        Leaf Type
                                    </a>
                                </li>
                                <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected')) ? 'active' : ''}}">
                                        <a href="#">
                                            <i class="metismenu-icon">
                                                <i class="fa fa-check"></i>
                                            </i>
                                            Glass Type
                                            <i class="metismenu-state-icon">
                                                <i class="fa fa-caret-down"></i>
                                            </i>
                                        </a>
                                        <ul>
                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'leaf1_glass_type_custome') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['leaf1_glass_type_custome'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Custom Glass Type
                                                </a>
                                            </li>
                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'leaf1_glass_type') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['leaf1_glass_type'])}}">
                                                    <i class="metismenu-icon"></i>
                                                   Standard Glass Type
                                                </a>
                                            </li>
                                        </ul>
                                </li>
                                <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'door_leaf_facing_value') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['door_leaf_facing_value'])}}">
                                            <i class="metismenu-icon"></i>
                                            Door Leaf Facing Value
                                        </a>
                                </li>
                                 <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected')) ? 'active' : ''}}">
                                        <a href="#">
                                            <i class="metismenu-icon">
                                                <i class="fa fa-check"></i>
                                            </i>
                                            Glazing System
                                            <i class="metismenu-state-icon">
                                                <i class="fa fa-caret-down"></i>
                                            </i>
                                        </a>
                                        <ul>
                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'leaf1_glazing_systems_custome') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['leaf1_glazing_systems_custome'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Custom Glazing System
                                                </a>
                                            </li>
                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'leaf1_glazing_systems') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['leaf1_glazing_systems'])}}">
                                                    <i class="metismenu-icon"></i>
                                                   Standard Glazing System
                                                </a>
                                            </li>
                                        </ul>
                                </li>
                                <li class="submm-{{ (Request::segment(2) == 'filter' && Request::segment(3) == 'leaf1_glazing_systems') ? 'active' : ''}}">
                                        <a href="{{route('options/filter',['leaf1_glazing_systems','Halspan'])}}">
                                            <i class="metismenu-icon"></i>
                                            Glass Glazing System
                                        </a>
                                </li>
                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'Accoustics') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['Accoustics'])}}">
                                            <i class="metismenu-icon"></i>
                                            Acoustics
                                        </a>
                                    </li>
                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'Architrave_Type') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['Architrave_Type'])}}">
                                            <i class="metismenu-icon"></i>
                                            Architrave
                                        </a>
                                    </li>

                                    <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected')) ? 'active' : ''}}">
                                        <a href="#">
                                            <i class="metismenu-icon">
                                                <i class="fa fa-check"></i>
                                            </i>
                                            Door Leaf Finish
                                            <i class="metismenu-state-icon">
                                                <i class="fa fa-caret-down"></i>
                                            </i>
                                        </a>
                                        <ul>
                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'Door_Leaf_Facing') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['Door_Leaf_Facing'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Door Leaf Facing
                                                </a>
                                            </li>
                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'door_leaf_finish') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['door_leaf_finish'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Door Leaf Finish
                                                </a>
                                            </li>

                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'Architrave_Finish') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['Architrave_Finish'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Finish Cost
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'Intumescent_Seal_Color') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['Intumescent_Seal_Color'])}}">
                                            <i class="metismenu-icon"></i>
                                            Intumescent Seal Color
                                        </a>
                                    </li>

                                    <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected')) ? 'active' : ''}}">
                                        <a href="#">
                                            <i class="metismenu-icon">
                                                <i class="fa fa-check"></i>
                                            </i>
                                            intumescent Seal Arrangement
                                            <i class="metismenu-state-icon">
                                                <i class="fa fa-caret-down"></i>
                                            </i>
                                        </a>
                                        <ul>
                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'intumescentSealArrangementCustome') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['intumescentSealArrangementCustome'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Custom intumescent Seal Arrangement
                                                </a>
                                            </li>
                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'intumescentSealArrangement') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['intumescentSealArrangement'])}}">
                                                    <i class="metismenu-icon"></i>
                                                   Standard intumescent Seal Arrangement
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="submm-{{ (Request::segment(2) == 'select' && Request::segment(4) == 'lippingSpecies') ? 'active' : ''}}">
                                        <a href="{{route('options/select',[1,'lippingSpecies'])}}">
                                            <i class="metismenu-icon"></i>
                                            Timber Species
                                        </a>
                                    </li>
                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'door_dimension') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['door_dimension'])}}">
                                            <i class="metismenu-icon"></i>
                                            Door Dimension
                                        </a>
                                    </li>

                                    <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'door_dimension_custome') ? 'active' : ''}}">
                                        <a href="{{route('options/selected',['door_dimension_custome'])}}">
                                            <i class="metismenu-icon"></i>
                                            Door Dimension Custom
                                        </a>
                                    </li>

                                    <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected1' && Request::segment(3) == 'color_list')) ? 'active' : ''}}">
                                        <a href="#">
                                            <i class="metismenu-icon">
                                                <i class="fa fa-check"></i>
                                            </i>
                                            Colour List
                                            <i class="metismenu-state-icon">
                                                <i class="fa fa-caret-down"></i>
                                            </i>
                                        </a>
                                        <ul>
                                            <li class="submm-{{ (Request::segment(2) == 'selected1' && Request::segment(3) == 'color_list') ? 'active' : ''}}">
                                                <a href="{{route('options/selected1',['color_list','Kraft_Paper'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Kraft Paper
                                                </a>
                                            </li>
                                            <li class="submm-{{ (Request::segment(2) == 'selected1' && Request::segment(3) == 'color_list') ? 'active' : ''}}">
                                                <a href="{{route('options/selected1',['color_list','Laminate'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Laminate
                                                </a>
                                            </li>
                                            <li class="submm-{{ (Request::segment(2) == 'selected1' && Request::segment(3) == 'color_list') ? 'active' : ''}}">
                                                <a href="{{route('options/selected1',['color_list','PVC'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    PVC
                                                </a>
                                            </li>
                                            {{--  <li class="submm-{{ (Request::segment(2) == 'selected1' && Request::segment(3) == 'color_list') ? 'active' : ''}}">
                                                <a href="{{route('options/selected1',['color_list','Veneer'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Veneer
                                                </a>
                                            </li>
                                            <li class="submm-{{ (Request::segment(2) == 'selected1' && Request::segment(3) == 'color_list') ? 'active' : ''}}">
                                                <a href="{{route('options/selected1',['color_list','Painted'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Painted
                                                </a>
                                            </li>  --}}
                                        </ul>
                                    </li>

                                    <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected')) ? 'active' : ''}}">
                                        <a href="#">
                                            <i class="metismenu-icon">
                                                <i class="fa fa-check"></i>
                                            </i>
                                            Side Screen
                                            <i class="metismenu-state-icon">
                                                <i class="fa fa-caret-down"></i>
                                            </i>
                                        </a>
                                        <ul>
                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'Door_Leaf_Facing') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['SideScreen_Glass_Type'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Glass Type
                                                </a>
                                            </li>
                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'door_leaf_finish') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['SideScreen_Glazing_System'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Glazing System
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="mm-{{ (Request::segment(1) == 'options' && (Request::segment(2) == 'selected')) ? 'active' : ''}}">
                                        <a href="#">
                                            <i class="metismenu-icon">
                                                <i class="fa fa-check"></i>
                                            </i>
                                            Side Light / Fanlight
                                            <i class="metismenu-state-icon">
                                                <i class="fa fa-caret-down"></i>
                                            </i>
                                        </a>
                                        <ul>
                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'Door_Leaf_Facing') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['Overpanel_Glass_Type'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Glass Type
                                                </a>
                                            </li>
                                            <li class="submm-{{ (Request::segment(2) == 'selected' && Request::segment(3) == 'door_leaf_finish') ? 'active' : ''}}">
                                                <a href="{{route('options/selected',['Overpanel_Glazing_System'])}}">
                                                    <i class="metismenu-icon"></i>
                                                    Glazing System
                                                </a>
                                            </li>
                                        </ul>
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
                                        Door Frame Construction
                                    </a>
                                </li>
                                {{--  <li class="submm-{{ (Request::segment(2) == 'quotation-prefix') ? 'active' : ''}}">
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
                                </li>  --}}

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
                                        {{--  <li class="submm-{{ (Request::segment(3) == 'costsetting') ? 'active' : ''}}">
                                            <a href="{{route('costsetting')}}">Cost Setting</a>
                                        </li>  --}}
                                        <li class="submm-{{ (Request::segment(3) == 'general_labour_cost_setting') ? 'active' : ''}}">
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
                                <li class="mm-{{ (Request::segment(2) == 'ironmongery-list') ? 'active' : ''}}">
                                    <a href="{{route('ironmongery-list')}}">
                                        <i class="metismenu-icon"></i>
                                        Ironmongery Set
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
                    <li>
                        <a href="#">
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
