<?php

namespace frontend\helpers;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class MainAsset extends AssetBundle
{
    public $basePath = '@frontend';
    public $baseUrl = '@web';
    public $css = [
        'css/normalize.css', 'css/style.css'
    ];
    public $js = [
        'js/main.js'
    ];

}