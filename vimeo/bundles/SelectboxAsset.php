<?php
/**
 * @copyright Copyright (c) 2014 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 */

namespace dlds\wrappers\vimeo\bundles;

use yii\web\AssetBundle;

/**
 * SpinnerAsset for spinner widget.
 */
class SelectboxAsset extends AssetBundle
{
    public $sourcePath = '@dlds/wrappers/vimeo/assets';
    public $js = [
        'plugins/select2/select2.min.js',
    ];

    public $css = [
        'plugins/select2/select2.css',
        'plugins/select2/select2-bootstrap.css',
    ];


    public $depends = [
        'dlds\metronic\bundles\CoreAsset',
    ];
}
