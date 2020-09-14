<!DOCTYPE html>
<html lang="en">

<head>
    <base href="../../">
    <meta charset="utf-8" />
    <title>
        @hasSection('title') @yield('title') |
        @endif
        PK2021 BKKBN
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="description" content="Page with empty content">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ url('assets/css/paper.css') }}" rel="stylesheet" type="text/css" />
    <style>
        @page {
            size: @yield('pageformat')
        }

    </style>
</head>

<body class="@yield('pageformat')">
    @yield('content')
</body>

</html>
