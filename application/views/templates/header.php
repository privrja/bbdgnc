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
                <a href="<?= site_url("sequence") ?>" class="a-menu">
                    <i class="fa fa-database"></i> Sequence
                </a>
            </li>
            <li class="main-menu">
                <a href="<?= site_url("block") ?>" class="a-menu">
                    <i class="fa fa-puzzle-piece"></i> Block
                </a>
            </li>
            <li class="main-menu">
                <a href="<?= site_url("modification") ?>" class="a-menu">
                    <i class="fa fa-filter"></i> Modification
                </a>
            </li>
        </ul>
        <ul class="main-menu-right">
            <li class="main-menu">
                <a href="<?= site_url("import") ?>" class="a-menu">
                    <i class="fa fa-upload"></i> Import
                </a>
            </li>
            <li class="main-menu">
                <a href="<?= site_url("export") ?>" class="a-menu">
                    <i class="fa fa-download"></i> Export
                </a>
            </li>
            <li class="main-menu">
                <a href="<?= site_url("settings") ?>" class="a-menu"><i class="fa fa-cogs"></i> Settings</a>
            </li>
        </ul>
    </nav>

    <!--    LogIn | Registration-->
</header>
<section>
