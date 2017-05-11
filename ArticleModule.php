<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

namespace maks757\articlesdata;

use yii\base\Module;
/**
 * @property boolean $showFields
*/
class ArticleModule extends Module
{
    public $defaultRoute = 'post/index';

    public $showFields = true;
}