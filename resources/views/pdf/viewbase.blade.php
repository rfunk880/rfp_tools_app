<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="Content-Type"
          content="text/html; charset=utf-8" />
    <meta name="description"
          content="">
    <title>
        @section('title')
            Print Report
        @show
    </title>
    <style>
        html {
            /* margin-top: 5px; */
            background-color: #ffffff;
        }

        body {
            margin: 0; /* Important! Let @page control margins */
    padding: 0;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        .data {
            font-size: 14px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }

        .imgbox {
            width: 70% vertical-align: bottom;
        }

        .imgbox img {
            text-align: center;
        }

        @page {
    margin: 50px 25px; /* top, left/right, bottom */
}

header {
    position: fixed;
    top: -55px; /* pulls it into the top margin space */
    left: 0;
    right: 0;
    height: 50px;
    text-align: center;
    line-height: 50px;
}

footer {
    position: fixed;
    bottom: -15px; /* match this with bottom margin */
    left: 0px;
    right: 0px;
    height: 80px;
    text-align: center;
    line-height: 50px;
}

.content {
    margin-top: 0px; /* remove this, no need */
}

        .footerlist {
            position: fixed;
            bottom: -35px;
            left: 0px;
            right: 0px;
            height: 55px;
            text-align: center;
            line-height: 50px;
        }

        table.gridtable {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #333333;
            border-width: 1px;
            border-color: #0c1318;
            border-collapse: collapse;
            width: 100%;
            margin-left: 2px;
            margin-top: 2px;
            margin-bottom: 3px;
        }

        table.gridtable th {
            font-size: 13px;
            border-width: 1px;
            padding: 3px;
            border-style: solid;
            border-color: #0c1318;
            text-align: center;
            text-transform: uppercase;
        }

        table.gridtable td {
            font-size: 13px;
            border-width: 1px;
            padding: 3px;
            border-style: solid;
            border-color: #0c1318;
            background-color: #ffffff;
        }
    </style>
</head>

<body>
    <header>@yield('header')</header>
    <div class="content">
        @yield('body')
    </div>
</body>

</html>
