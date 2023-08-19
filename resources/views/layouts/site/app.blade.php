<!doctype html>
@php $locale = \App::getLocale(); @endphp
<html class="no-js" lang="{{$locale}}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Zoolife-زوولايف</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
        <!-- Place favicon.ico in the root directory -->

        <!-- Google font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        <!-- CSS here -->
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/css/owl.carousel.min.css">
        <link rel="stylesheet" href="/assets/css/animate.min.css">
        <link rel="stylesheet" href="/assets/css/magnific-popup.css">
        <link rel="stylesheet" href="/assets/css/line-awesome.min.css">
        <link rel="stylesheet" href="/assets/css/meanmenu.css">
        <link rel="stylesheet" href="/assets/css/slick.css">
        <link rel="stylesheet" href="/assets/css/default.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="stylesheet" href="/assets/css/responsive.css">
        @yield('styles')
    </head>

    <body>
        <div class="toast toast-notify bg-success" style="">
            <div class="toast-header"></div>
            <div class="toast-body"></div>
        </div>

        <!-- add new post -->
        <div class="modal fade" id="commonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    love
                </div>
            </div>
        </div>
        <div class="modal fade" id="popupModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header pt-45">
                        <!-- <h5 class="modal-title">Feature is only available in App</h5> -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <h5 class="mb-4">{{ __('This feature available only for app') }}</h5>
                        <div class="footer-widget">
                            <div class="app-download">
                                <a target="_blank" href="https://play.google.com/store/apps/details?id=com.zoolife.app"><img src="/assets/img/playstore.png" alt=""></a>
                                <a target="_blank" href="https://apps.apple.com/sa/app/%D8%B2%D9%88%D9%88%D9%84%D8%A7%D9%8A%D9%81-zoolife/id1549373638"><img src="/assets/img/appstore.png" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- add ne auction -->
        <!-- <div class="modal fade" id="commonModal2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header pt-45">
                        <h5 class="modal-title">{{ __('Add New Auction') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="upload-box mb-40">
                            <div class="img-upload">
                                <label for="uploadIMG">
                                    <div class="upload-icon">
                                        <i class="las la-cloud-upload-alt"></i>
                                    </div>
                                    <p class="upload-text">{{ __('Select a file or drag and drop here') }}</p>
                                    <p class="upload-file-format mb-20">{{ __('JPG, PNG or PDF, file size no more than 10MB') }}</p>
                                    <div class="btn theme-btn m-auto">{{ __('Upload') }}</div>
                                </label>
                                <input type="file" id="uploadIMG" class="d-none">
                            </div>
                            <div class="show-uploaded-img">
                                <div class="single-img">
                                    <img src="/assets/img/dog.png" alt="">
                                    <span class="remove-img-btn"><i class="las la-times"></i></span>
                                </div>
                                <div class="single-img">
                                    <img src="/assets/img/dog.png" alt="">
                                    <span class="remove-img-btn"><i class="las la-times"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 mb-30">
                                <div class="popup-form-field">
                                    <label for="">{{ __('Add Location') }}</label>
                                    <select id="">
                                        <option value="">Select</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 mb-30">
                                <div class="popup-form-field">
                                    <label for="">{{ __('Age') }}</label>
                                    <input type="text" placeholder="Enter here">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 mb-30">
                                <div class="popup-form-field">
                                    <label for="">{{ __('Choose Category') }}</label>
                                    <select id="">
                                        <option value="">Select</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 mb-30">
                                <div class="popup-form-field">
                                    <label for="">{{ __('Choose Subcategory') }}</label>
                                    <select id="">
                                        <option value="">Select</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 mb-30">
                                <div class="popup-form-field">
                                    <label for="">{{ __('Add Auction Title') }}</label>
                                    <input type="text" placeholder="Enter here">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 mb-30">
                                <div class="popup-form-field">
                                    <label for="">{{ __('Vaccine Details') }}</label>
                                    <input type="text" placeholder="Enter here">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 mb-30">
                                <div class="popup-form-field">
                                    <label for="">{{ __('Add Starter Price') }}</label>
                                    <input type="text" placeholder="Enter here">
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 mb-30">
                                <div class="popup-form-field">
                                    <label for="">{{ __('Total Days') }}</label>
                                    <select id="">
                                        <option value="">Select Day</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 mb-30">
                                <div class="popup-form-field">
                                    <label for="">{{ __('Hours') }}</label>
                                    <select id="">
                                        <option value="">Select Hours</option>
                                        <option value="">1</option>
                                        <option value="">2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 mb-30">
                                <div class="radio-option">
                                    <label for="">{{ __('Sex') }}</label>
                                    <div class="d-flex align-items-center gap-5">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="female" value="option1">
                                            <label class="form-check-label" for="female">Female</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="male" value="option2">
                                            <label class="form-check-label" for="male">Male</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 mb-30">
                                <div class="radio-option">
                                    <label for="">{{ __('Passport') }}</label>
                                    <div class="d-flex align-items-center gap-5">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="pass-yes" value="option1">
                                            <label class="form-check-label" for="pass-yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="pass-no" value="option2">
                                            <label class="form-check-label" for="pass-no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-30">
                                <div class="popup-form-field">
                                    <label for="">{{ __('Auction Description') }}</label>
                                    <textarea cols="10" rows="5" placeholder="Enter here"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer pb-35">
                        <button type="button" class="btn theme-btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="button" class="btn theme-btn">{{ __('Post') }}</button>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- login -->
        <div class="modal fade" id="login" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pt-45">
                        <h5 class="modal-title"><img src="/assets/img/logo.png" alt=""></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="login-frm">
                            <div class="welcome-text mb-20">
                                <h2>{{ __('Welcome Back') }}...</h2>
                                <p>{{ __('We are happy to see you again! Please, Let’s Login First!') }}</p>
                            </div>
                            <div class="row">
                                @csrf
                                <div class="popup-form-field mb-20">
                                    <label for="">{{ __('Phone Number') }}</label>
                                    <input type="text" placeholder="+96655xxxxxxxx" name="phone">
                                    <div class="error text-danger" id="phone_error"></div>
                                </div>
                                <div class="popup-form-field mb-20">
                                    <label for="">{{ __('Password') }}</label>
                                    <input type="password" placeholder="{{ __('Enter Your Password')}}" name="password">
                                    <div class="error text-danger" id="password_error"></div>
                                </div>
                            </div>
                            <div class="login-action-link text-end mb-30">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#popupModal">{{ __('Forgot Password?') }}</a>
                            </div>
                            <a href="#" class="btn theme-btn btn-block w-auto" id="login-btn" data-url="{{route('user-login')}}">{{ __('Login') }}</a>
                            <div class="submit_notification"></div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-center pb-35">
                        <div class="login-action-link text-center">
                            <span>{{ __('Don’t have an account?') }} <a href="#" style="color: var(--theme-color);" data-bs-toggle="modal" data-bs-target="#popupModal">{{ __('Register Now?') }}</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- signup -->
        <div class="modal fade" id="signup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header pt-45">
                        <h5 class="modal-title"><img src="/assets/img/logo.png" alt=""></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="welcome-text mb-20">
                            <h2>{{ __('Hi There!') }}</h2>
                            <p>{{ __('We are happy to see you want to join you Please, Let’s sign up first!') }}</p>
                        </div>
                        <div class="row">
                            <div class="popup-form-field mb-20">
                                <label for="">{{ __('Phone Number') }}</label>
                                <input type="text" placeholder="+96655xxxxxxxx">
                            </div>
                            <div class="popup-form-field mb-20">
                                <label for="">{{ __('Username') }}</label>
                                <input type="text" placeholder="Enter Your Username">
                            </div>
                            <div class="popup-form-field mb-20">
                                <label for="">{{ __('Password') }}</label>
                                <input type="text" placeholder="Enter Your Password">
                            </div>
                        </div>
                        <a href="#" class="btn theme-btn btn-block w-auto">{{ __('Sign up') }}</a>
                    </div>
                    <div class="modal-footer justify-content-center pb-35">
                        <div class="login-action-link text-center">
                            <span>{{ __('You already have account?') }} <a href="#" style="color: var(--theme-color);" data-bs-toggle="modal" data-bs-target="#login">{{ __('Log In') }}</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- header -->
        <header class="header-area">
            <div class="main-header">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-2">
                            <div class="logo">
                                <a href="{{route('home')}}" class="d-inline-block brand-logo">
                                    <img src="/assets/img/logo.png" alt="">
                                </a>
                                <div class="user-action-btn">
                                    <nav>
                                        <ul>
                                            <li>
                                                <a href="javascript:void(0)" class="small_search">
                                                    <img src="/assets/img/icons/Search.svg" alt="">
                                                </a>
                                                <div class="search-form show-small-search">
                                                    <input type="text" placeholder="{{ __('Search') }}" name="q" class="common-search">
                                                </div>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-type="post" data-url="{{route('load-post-auction-modal')}}" data-bs-target="{{ (\Auth::user()) ? '#commonModal' : '#login'}}">
                                                    <img src="/assets/img/icons/Plus-dark.svg" alt="">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('posts', ['type'=>'auction'])}}">
                                                    <img src="/assets/img/icons/Auction-dark.svg" alt="">
                                                </a>
                                            </li>
                                            <li>
                                                <div class="lang-switcher">
                                                    <div class="lang text-uppercase">{{$locale}}<i class="las la-angle-down"></i></div>
                                                    <div class="lang-dropdown">
                                                        <div class="lang-option" id="eng">
                                                            <a class="{{$locale =='en' ? 'active' : ''}}" id="eng" href="{{route('site.change-locale', ['locale'=>'en'])}}" >EN</a>
                                                        </div>
                                                        <div class="lang-option" id="ar">
                                                            <a class=" {{$locale =='ar' ? 'active' : ''}}" id="ar" href="{{route('site.change-locale', ['locale'=>'ar'])}}" >Ar</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#popupModal">
                                                    <img src="/assets/img/icons/Chat.svg" alt="">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#popupModal">
                                                    <img src="/assets/img/icons/Notification.svg" alt="">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" class="main-menu-toggle">
                                                    <img src="/assets/img/icons/Menu.svg" alt="">
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-10">
                            <div class="header-menu-content">
                                <nav>
                                    <ul>
                                        <li>
                                            <div class="search-form">
                                                <div class="search-icon">
                                                    <img src="/assets/img/icons/search.svg" alt="">
                                                </div>
                                                <input type="text" placeholder="{{ __('Search') }}" name="q" class="common-search">
                                            </div>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="theme-bg btn btn-outline" data-bs-toggle="modal" data-type="normal" data-url="{{route('load-post-auction-modal')}}" data-bs-target="{{ (\Auth::user()) ? '#commonModal' : '#login'}}">
                                                <img src="/assets/img/icons/plus.svg" alt="" class="me-2">
                                                {{ __('Add Post') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{route('posts', ['type'=>'auction'])}}" class="theme-bg">
                                                <img src="/assets/img/icons/Auction.svg" alt="" class="me-2">
                                                {{ __('Auction') }}
                                            </a>
                                        </li>
                                        <li>
                                            <div class="lang-switcher">
                                                <div class="lang text-uppercase">{{$locale}}<i class="las la-angle-down"></i></div>
                                                <div class="lang-dropdown">
                                                    <div class="lang-option" id="eng">
                                                        <a class=" {{$locale =='en' ? 'active' : ''}}" id="eng" href="{{route('site.change-locale', ['locale'=>'en'])}}" >EN</a>
                                                    </div>
                                                    <div class="lang-option" id="ar">
                                                        <a class="{{$locale =='ar' ? 'active' : ''}}" id="ar" href="{{route('site.change-locale', ['locale'=>'ar'])}}" >AR</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#popupModal">
                                                <img src="/assets/img/icons/Chat.svg" alt="">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#popupModal">
                                                <img src="/assets/img/icons/Notification.svg" alt="">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="main-menu-toggle">
                                                <img src="/assets/img/icons/Menu.svg" alt="">
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- sidebar -->
            <div class="off-canvas-menu off-canvas-main-menu custom-scrollbar-styled">
                <div class="off-canvas-menu-close">
                    <i class="las la-times-circle"></i>
                </div>
                <ul class="off-canvas-menu-list pt-90">
                    <li>
                        <a href="#">
                            <div class="off-canvas-icon"><i class="lar la-heart"></i></div>
                            {{ __('Favorites') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{route('my-posts')}}">
                            <div class="off-canvas-icon"><i class="lar la-user"></i></div>
                            {{ __('My Posts') }}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-type="normal" data-url="{{route('load-post-auction-modal')}}" data-bs-target="{{ (\Auth::user()) ? '#commonModal' : '#login'}}">
                            <div class="off-canvas-icon"><i class="las la-plus"></i></div>
                            {{ __('Add New Post') }}
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="off-canvas-icon"><i class="lar la-user"></i></div>
                            {{ __('My Auction') }}
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-type="auction" data-url="{{route('load-post-auction-modal')}}" data-bs-target="{{ (\Auth::user()) ? '#commonModal' : '#login'}}">
                            <div class="off-canvas-icon"><i class="las la-plus"></i></div>
                            {{ __('Add New Auction') }}
                        </a>
                    </li>
                    <li>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#popupModal">
                            <div class="off-canvas-icon"><i class="las la-truck"></i></div>
                            {{ __('Delivery Post') }}
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="off-canvas-icon"><i class="las la-bullhorn"></i></div>
                            {{ __('Banned Ads') }}
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="off-canvas-icon"><i class="las la-user-shield"></i></div>
                            {{ __('Terms & Policy') }}
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="off-canvas-icon"><i class="las la-phone"></i></div>
                            {{ __('Contact Us') }}
                        </a>
                    </li>
                    <li>
                        @if (\Auth::user())
                        <a href="{{route('logout')}}">
                            <div class="off-canvas-icon"><i class="las la-sign-in-alt"></i></div>
                            {{ __('Log Out') }}
                        </a>
                        @else
                        <a href="#" data-bs-toggle="modal" data-bs-target="#login">
                            <div class="off-canvas-icon"><i class="las la-sign-in-alt"></i></div>
                            {{ __('Log In') }}
                        </a>
                        @endif
                    </li>
                </ul>
            </div>
        </header>

        <div class="page-body">
            @yield('content')
        </div>

        <!-- footer -->
        <footer class="footer-area">
            <div class="main-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-5 mb-30">
                            <div class="footer-widget">
                                <div class="footer-logo mb-30">
                                    <a href="#"><img src="/assets/img/logo.png" alt=""></a>
                                </div>
                                <p class="footer-text">{{ __('Meet your new animal-friendly place!') }}</p>
                                <div class="social-icons">
                                    <a target="_blank" href="https://www.facebook.com/zoolife.mooh"><i class="lab la-facebook-f"></i></a>
                                    <a target="_blank" href="https://twitter.com/zoolife2030"><i class="lab la-twitter"></i></a>
                                    <a target="_blank" href="https://www.instagram.com/zoolife.sa"><i class="lab la-instagram"></i></a>
                                    <a target="_blank" href="https://www.snapchat.com/add/zoolife.sa"><i class="lab la-snapchat"></i></a>
                                    <a target="_blank" href="https://t.me/ZooLife2030"><i class="lab la-telegram"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-3 offset-xl-1 mb-30">
                            <div class="footer-widget">
                                <h4 class="widget-title">{{ __('Help') }}</h4>
                                <ul class="site-map">
                                    <li><a href="#">{{ __('Terms & Conditions') }}</a></li>
                                    <li><a href="#">{{ __('Content Policy') }}</a></li>
                                    <li><a href="#">{{ __('Privacy Policy') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-4 mb-30">
                            <div class="footer-widget">
                                <h4 class="widget-title">{{ __('More') }}</h4>
                                <ul class="site-map">
                                    <li><a href="#">{{ __('About') }}</a></li>
                                    <li><a href="#">{{ __('Help') }}</a></li>
                                    <li><a href="#">{{ __('FAQs') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-12">
                            <div class="footer-widget">
                                <div class="app-download">
                                    <a target="_blank" href="https://play.google.com/store/apps/details?id=com.zoolife.app"><img src="/assets/img/playstore.png" alt=""></a>
                                    <a target="_blank" href="https://apps.apple.com/sa/app/%D8%B2%D9%88%D9%88%D9%84%D8%A7%D9%8A%D9%81-zoolife/id1549373638"><img src="/assets/img/appstore.png" alt=""></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom-footer">
                <span>{{ __('Company Name Inc @2022. All Rights Reserved') }}</span>
            </div>
        </footer>

        <!-- JS here -->
        <!-- <script src="/assets/js/vendor/jquery-1.12.4.min.js"></script> -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="/assets/js/vendor/modernizr-3.5.0.min.js"></script>
        <script src="/assets/js/popper.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/owl.carousel.min.js"></script>
        <script src="/assets/js/isotope.pkgd.min.js"></script>
        <script src="/assets/js/one-page-nav-min.js"></script>
        <script src="/assets/js/slick.min.js"></script>
        <script src="/assets/js/jquery.meanmenu.min.js"></script>
        <script src="/assets/js/ajax-form.js"></script>
        <script src="/assets/js/wow.min.js"></script>
        <script src="/assets/js/jquery.scrollUp.min.js"></script>
        <script src="/assets/js/imagesloaded.pkgd.min.js"></script>
        <script src="/assets/js/jquery.magnific-popup.min.js"></script>
        <script src="/assets/js/plugins.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js
"></script>
        <script src="//cdn.rawgit.com/hilios/jQuery.countdown/2.2.0/dist/jquery.countdown.min.js"></script>
        <script type="text/javascript">
            var favouritUrl = "{{route('do-favourite')}}";
            var likeUrl     = "{{route('do-like')}}";
            var removePostUrl = "{{route('delete-post')}}";
            var removePostImageUrl = "{{route('remove-post-image')}}";
            var commentUrl  = "{{route('do-comment')}}";
            var bidUrl  = "{{route('place-bid')}}";
        </script>

        <script src="/assets/js/main.js"></script>
        
        <script type="text/javascript">
            let lang = '{{$locale}}';
            setLocale(lang);

            $(".small_search").click(function(event) {
                $(".show-small-search").toggle();
            });

            $(".popup-search").keyup(function(event) {
                if (event.keyCode === 13) {
                    let q = $(this).val();
                    window.location.href = "{{route('posts')}}?q="+q
                }
            });

            function loadSubCategory(selected) {
                // $("#sub_category").select2({
                //     dropdownParent: $('#commonModal'),
                //     placeholder: "Select Sub Category",
                //     dropdownPosition: 'auto',
                //     ajax: {
                //         url: "<?php echo route("get_sub_category") ?>",
                //         dataType: 'json',
                //         type: 'post',
                //         delay: 250,
                //         data: function (params) {
                //             var query = {
                //                 cat_id: $('#category').val(),
                //                 search: params.term,
                //                 page: params.page || 1
                //             }

                //           // Query parameters will be ?search=[term]&type=public
                //           return query;
                //         },
                //         processResults: function(data, params) {
                //             params.page = params.page || 1;
                //             return {
                //                 results: $.map(data.results, function(obj) {
                //                     let result = { id: obj.id, text: obj.title };
                //                     return result;
                //                 })
                //                 // pagination: {
                //                 //     more: (params.page * 30) < data.total_count
                //                 // }
                //             };
                //         },
                //         cache: true
                //     },
                //     escapeMarkup: function(markup) {
                //         return markup;
                //     }, // let our custom formatter work
                // });
                $("#sub_category").children().remove();
                $("#sub_category").append('<option value="">Select Sub Category</option>');
                $.ajax({
                    url: "{{route('get_sub_category')}}",
                    type: 'post',
                    dataType: 'json',
                    data: {cat_id: $('#category').val()},
                    success: function(data) {
                        $.each(data.results, function(){
                            $("#sub_category").append('<option value="'+ this.id +'"'+ ((selected == this.id) ? "selected" : "") +' >'+ this.title +'</option>')
                        });
                    }
                });
            }
        </script>
        
        @yield('scripts')
    </body>

</html>
