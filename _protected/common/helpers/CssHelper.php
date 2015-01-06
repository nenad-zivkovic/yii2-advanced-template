<?php
namespace common\helpers;

use Yii;

/**
 * Css helper class.
 */
class CssHelper
{
    /**
     * Returns the appropriate css class based on the value of user $status.
     * NOTE: used in user/index view.
     *
     * @param  string $status User status.
     * @return string         Css class.
     */
    public static function statusCss($status)
    {
        if ($status === 'Active')
        {
            return "boolean-true";
        } 
        else 
        {
            return "boolean-false";
        }      
    }

    /**
     * Returns the appropriate css class based on the value of role $item_name.
     * NOTE: used in user/index view.
     *
     * @param  string $role Role name.
     * @return string       Css class.
     */
    public static function roleCss($role)
    {
        return "role-".$role."";    
    }

    /**
     * Returns the appropriate css class based on the value of Article $status.
     * NOTE: used in article/admin view.
     *
     * @param  string $status Article status.
     * @return string         Css class.
     */
    public static function articleStatusCss($status)
    {
        if ($status === Yii::t('app', 'Published'))
        {
            return "boolean-true";
        } 
        else 
        {
            return "boolean-false";
        }      
    }  

    /**
     * Returns the appropriate css class based on the value of Article $category.
     * NOTE: used in article/admin view.
     *
     * @param  string $category Article category.
     * @return string           Css class.
     */
    public static function articleCategoryCss($category)
    {
        if ($category === Yii::t('app', 'Economy'))
        {
            return "blue";
        }
        elseif ($category === Yii::t('app', 'Sport')) 
        {
            return "green";
        }
        else 
        {
            return "gold";
        }      
    }       
}