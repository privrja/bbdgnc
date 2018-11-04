<?php

class AssetHelper {

    public static function assetUrl() {
        return base_url() . 'assets/';
    }

    public static function nodeModulesUrl() {
        return base_url() . 'node_modules/';
    }

    public static function cssUrl() {
        return AssetHelper::assetUrl() . "css/";
    }

    public static function jsUrl() {
        return AssetHelper::assetUrl() . "js/";
    }

    public static function imgUrl() {
        return AssetHelper::assetUrl() . "img/";
    }

    public static function jsSmilesDrawer() {
        return AssetHelper::nodeModulesUrl() . 'smiles-drawer/dist/smiles-drawer.js';
    }

}