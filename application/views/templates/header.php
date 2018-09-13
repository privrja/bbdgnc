<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bbdgnc</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Styles -->
    <link href="<?= AssetHelper::cssUrl() . "styles.css" ?>" rel="stylesheet"/>

</head>
<body>
<header>

    <nav>
        <ul>
            <a class="a-menu" href="<?= site_url("land") ?>">BBDGNC</a>
            <li class="main-menu">
                <i class="fa fa-database"></i> My Database
            </li>
            <li class="main-menu">
                <i class="fa fa-upload"></i> Import
            </li>
            <li class="main-menu">
                <i class="fa fa-download"></i> Export
            </li>
        </ul>
        <ul class="main-menu-right">
            <li class="main-menu">
                <a href="<?= site_url("settings") ?>" class="a-menu"><i class="fa fa-cogs"></i> Settings</a>
            </li>
            <li class="main-menu">
                <i class="fa fa-sign-out"></i> Sign Out
            </li>
        </ul>
    </nav>

    <!--    LogIn | Registration-->
</header>
<section>
