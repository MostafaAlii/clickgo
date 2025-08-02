<!DOCTYPE html>
@if (app()->getLocale() == 'ar')
    <html direction="rtl" dir="rtl" style="direction: rtl">
@else
    <html lang="en">
@endif
<!--begin::Head-->

<head>
    <base href="">
    <title>@yield('pageTitle')</title>
    <!--begin::Fonts-->
    <link href="https://fonts.googleapis.com/css?family=Cairo:300,400&amp;subset=arabic,latin-ext" rel="stylesheet">
    <!--end::Fonts-->
    @if (app()->getLocale() == 'ar')
        <!-- Default Css Link for arabic Language RTL -->
    @else
        <!-- Default Css Link for english Language LTR -->
    @endif
    <style>
        html,
        body,
        a,
        i,
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        table,
        .btn,
        .alert,
        .dt-button {
            font-family: 'Cairo', sans-serif;
        }
    </style>
    @stack('css')
</head>
<!--end::Head-->
<!--begin::Body-->
