<?php
namespace backend\helpers;

/**
 * -----------------------------------------------------------------------------
 * CssHelper class.
 * -----------------------------------------------------------------------------
 */
class CssHelper
{
    /**
     * =========================================================================
     * Returns the appropriate css class based on the value of user $status.
     * NOTE: used in user/index view.
     * =========================================================================
     *
     * @param  string  $status  User status.
     *
     * @return string           Css class.
     * _________________________________________________________________________
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
     * =========================================================================
     * Returns the appropriate css class based on the value of role $item_name.
     * NOTE: used in user/index view.
     * =========================================================================
     *
     * @param  string  $role  Role name.
     *
     * @return string         Css class.
     * _________________________________________________________________________
     */
    public static function roleCss($role)
    {
        if ($role === "The Creator") 
        {
            return "role-the-creator";
        }
        elseif ($role === "admin") 
        {
            return "role-admin";
        } 
        elseif($role === "premium")
        {
            return "role-premium-member";
        }
        else
        {
            return "role-member";
        }    
    }  

    /**
     * =========================================================================
     * Returns the appropriate css class based on the value of setting $value.
     * NOTE: used in setting/index view.
     * =========================================================================
     *
     * @param  string  $value  Setting value.
     *
     * @return string          Css class.
     * _________________________________________________________________________
     */
    public static function settingValueCss($value)
    {
        if ($value === "Yes") 
        {
            return "boolean-true";
        } 
        else 
        {
            return "boolean-false";
        }      
    } 
}