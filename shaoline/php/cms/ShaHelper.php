<?php

/**
 * ShaHelper
 *
 * @category   Helper
 * @package    Shaoline
 * @subpackage Component
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    Bastien DUHOT copyright
 * @link       No link
 *
 */
class ShaHelper
{

    const CONST_TYPE_ADD = 0;
    const CONST_TYPE_DELETE = 1;

    private $_type;

    /**
     * Disable external construction
     */
    private function __construct($type)
    {
        $this->_type = $type;
    }

    /**
     * Create add mode CmsHlper instance
     *
     * @return ShaHelper
     */
    public static function add()
    {
        $instance = new ShaHelper(ShaHelper::CONST_TYPE_ADD);

        return $instance;
    }

    /**
     * Create delete mode ShaHelper instance
     *
     * @return ShaHelper
     */
    public static function delete()
    {
        $instance = new ShaHelper(ShaHelper::CONST_TYPE_DELETE);

        return $instance;
    }

    /**
     * Create/Delete new group
     *
     * @param string $name
     * @param string $description
     *
     * @return ShaHelper
     */
    public function group($name, $description)
    {

        $name        = ShaUtilsString::cleanForSQL($name);
        $description = ShaUtilsString::cleanForSQL($description);

        if ($this->_type == ShaHelper::CONST_TYPE_ADD) {

            ShaContext::bddInsert(
                "
                INSERT INTO shaoline_group
                (group_key, group_description)
                VALUES
                ('$name','$description')
            "
            );

        } else {

            ShaContext::bddDelete(
                "
                DELETE FROM shaoline_group
                WHERE
                group_key = '$name' AND
                group_description = '$description'
                "
            );

        }

        return $this;
    }

    /**
     * Add new group/permission mapping
     * 
     * @param unknown $name
     * @param unknown $description
     *
     * @return ShaHelper
     */
    public function groupPermission($groupKey, $permissionKey)
    {

    	$groupKey  = ShaUtilsString::cleanForSQL($groupKey);
    	$permissionKey  = ShaUtilsString::cleanForSQL($permissionKey);
    	
    	if ($this->_type == ShaHelper::CONST_TYPE_ADD) {
    	
    		ShaContext::bddInsert(
    			"
    			INSERT INTO shaoline_group_permission
    			(group_key, permission_key)
    			VALUES
    			('$groupKey','$permissionKey')
    			"
    		);
    	
    	} else {
    	
    	ShaContext::bddDelete(
    		"
    			DELETE FROM shaoline_group_permission
    			WHERE
    			group_key = '$groupKey' AND
    			permission_key = '$permissionKey'
    			"
    		);
    	
    	}
    	 
    	return $this;
    }
    
    /**
     * Add new group/permission mapping
     *
     * @param unknown $name
     * @param unknown $description
     *
     * @return ShaHelper
     */
    public function groupApplication($groupKey, $applicationKey)
    {

    	$groupKey  = ShaUtilsString::cleanForSQL($groupKey);
    	$applicationKey  = ShaUtilsString::cleanForSQL($applicationKey);
    	
    	if ($this->_type == ShaHelper::CONST_TYPE_ADD) {
    		 
    		ShaContext::bddInsert(
    				"
    				INSERT INTO shaoline_group_application
    				(group_key, application_key)
    				VALUES
    				('$groupKey','$applicationKey')
    				"
    		);
    		 
    	} else {
    	 
    	ShaContext::bddDelete(
    			"
    			DELETE FROM shaoline_group_permission
    			WHERE
    			group_key = '$groupKey' AND
    			application_key = '$applicationKey'
    			"
    	);
    			 
    	}
    
    	return $this;
    	}
    
    /**
     * Add/Delete new parameter
     *
     * @param $key
     * @param $value
     * @param $description
     *
     * @return ShaHelper
     */
    public function parameter($key, $value, $description)
    {

        $key         = ShaUtilsString::cleanForSQL($key);
        $value       = ShaUtilsString::cleanForSQL($value);
        $description = ShaUtilsString::cleanForSQL($description);


        if ($this->_type == ShaHelper::CONST_TYPE_ADD) {

            ShaContext::bddInsert(
                "
                INSERT INTO shaoline_parameter (parameter_key,parameter_value,parameter_description)
                VALUES
                ('$key','$value','$description');
                "
            );

        } else {

            ShaContext::bddDelete(
                "DELETE FROM shaoline_parameter WHERE parameter_key = '$key'"
            );

        }

        return $this;
    }

    /**
     * Add/Delete group to user
     *
     * @param string $userName  User name
     * @param string $groupName Group name
     *
     * @return $this
     */
    public function userGroup($userName, $group)
    {

        $userName  = ShaUtilsString::cleanForSQL($userName);
        $group  = ShaUtilsString::cleanForSQL($group);
        
        $user  = ShaContext::bddSelectValue("SELECT user_id FROM shaoline_user WHERE user_login = '" . $userName . "' ");

        if ($this->_type == ShaHelper::CONST_TYPE_ADD) {

            ShaContext::bddInsert(
                "INSERT INTO shaoline_user_group
                (user_id, group_key)
                VALUES
                ($user, '$group');
                "
            );

        } else {

            ShaContext::bddDelete(
                "
                DELETE FROM shaoline_user_group
                WHERE
                user_id = '$user'
                AND
                group_key = '$group'
            "
            );


        }

        return $this;
    }

    /**
     * Add/Delete language
     *
     * @param int    $languageId
     * @param string $languageLib
     * @param string $languageFlag
     * @param string $languageAbr
     * @param string $languageLocale
     *
     * @return ShaHelper
     */
    public function language(
        $languageId, $languageLib = "", $languageFlag = "", $languageAbr = "", $languageLocale = "", $url="", $active=1
    ) {

        $languageLib = ShaUtilsString::cleanForSQL($languageLib);

        if ($this->_type == ShaHelper::CONST_TYPE_ADD) {

            ShaContext::bddInsert(
                "
                INSERT INTO shaoline_language (language_id,language_lib,language_flag,language_abr,language_locale, language_url)
                VALUES
                ($languageId,'$languageLib','$languageFlag','$languageAbr','$languageLocale','$url');
              "
            );

        } else {

            ShaContext::bddDelete(
                "DELETE FROM shaoline_language WHERE language_id = $languageId"
            );

        }

        return $this;
    }

    /**
     * Add application
     *
     * @param string $name        Application name
     * @param string $icon        Application icone
     * @param string $function    Application function
     * @param string $path        Application path
     * @param string $description Application path
     * @param string $desktop     Application on desctop
     * @param string $menu        Application in menu
     * @param int    $order       Application order in menu
     *
     * @return ShaHelper
     */
    public function application ($key,$name, $icon, $function, $path, $description, $desktop, $menu, $order)
    {

        $name        = ShaUtilsString::cleanForSQL($name);
        $function    = ShaUtilsString::cleanForSQL($function);
        $path        = ShaUtilsString::cleanForSQL($path);
        $description = ShaUtilsString::cleanForSQL($description);

        if ($this->_type == ShaHelper::CONST_TYPE_ADD) {
        	
            ShaContext::bddInsert(
                "
              INSERT INTO shaoline_application (application_key, application_name,application_icon,application_function,application_menu_position,application_description,application_on_desktop,application_on_menu,application_menu_order)
              VALUES
              ('$key', '$name','$icon','$function','$path','$description', $desktop, $menu, $order);
            "
            );
        }

        return $this;

    }

    /**
     * Give application to user
     * TODO : is it used somewhere ?
     *
     * @param string $userName        name
     * @param string $applicationName name
     *
     * @return ShaHelper
     */
    public function userApplication($userName, $applicationKey)
    {

        $userName        = ShaUtilsString::cleanForSQL($userName);
        $applicationName = ShaUtilsString::cleanForSQL($applicationName);

        $user        = ShaContext::bddSelectValue("SELECT user_id FROM shaoline_user WHERE user_name = '" . $userName . "' ");

        if ($this->_type == ShaHelper::CONST_TYPE_ADD) {

            ShaContext::bddInsert(
                "INSERT INTO shaoline_user_application
             (user_id, application_key)
             VALUES
             ($user, '$applicationKey');
            "
            );

        }

        return $this;
    }

    /**
     * Add permisison
     *
     * @param $key         Permission key
     * @param $description Description
     *
     * @return ShaHelper
     */
    public function permission($key, $description)
    {

        $description = ShaUtilsString::cleanForSQL($description);

        if ($this->_type == ShaHelper::CONST_TYPE_ADD) {

            //Add root user
            ShaContext::bddInsert(
                "
                INSERT INTO shaoline_permission (permission_key,permission_description)
                VALUES
                ('$key','$description')
            "
            );
        }

        return $this;
    }

    /**
     * Add/Delete user
     *
     * @param $login
     * @param $password
     * @param $language
     * @param $isAdmin
     *
     * @return ShaHelper
     */
    public function user($login, $password, $language, $isAdmin)
    {

        $login    = ShaUtilsString::cleanForBalise($login);
        $login    = ShaUtilsString::cleanForSQL($login);
        $password = $password = ShaContext::securityEncode($password);

        if ($this->_type == ShaHelper::CONST_TYPE_ADD) {

            //Add root user
            ShaContext::bddInsert(
                "INSERT INTO shaoline_user
                    (user_login, user_pwd, user_language, user_admin, user_active, user_validated)
                    VALUES
                    ('$login', '$password', $language, $isAdmin, 1, 1)
                    "
            );
        } else {

            ShaContext::delete(
                "
                DELETE FROM shaoline_user WHERE user_login = '$login'
            "
            );

        }

        return $this;
    }

    /**
     * Add/Delete page
     *
     * @param string $key Unic key
     * @param int $languageId Language id
     * @param string $variables Variables (Ex: ShaUser;CmsApplication)
     * @param string $title Page title (Ex: "Page of user ;ShaUser@user_lib" => "Page of user bibi")
     * @param string $template Page template path
     * @param string $url Page url (Ex: "/user;Company@user_id;perso" => "/user/4/perso" )
     * @param bool $header True to add header
     * @param bool $footer True for add footer
     * @param bool $body True for add body div
     * @param string $js Additional JS
     * @param string $css Additional CSS
     * @param string $pageGetParam Additional GET param in htaccess
     *
     * @return ShaHelper
     */
    public function page($key, $languageId, $variables, $title, $template, $url, $header, $footer, $body, $js, $css, $redirect = true, $getParameters = "")
    {

        $header = ($header) ? "1" : "0";
        $footer = ($footer) ? "1" : "0";
        $body = ($body) ? "1" : "0";
        $redirect = ($redirect) ? "1" : "0";

        if ($this->_type == ShaHelper::CONST_TYPE_ADD) {
            ShaContext::bddInsert(
                "
					INSERT INTO shaoline_page (page_international_name, language_id, page_variables, page_title, page_template, page_url,page_add_header,page_add_footer,page_add_body, additional_js, additional_css, page_need_redirect, page_get_parameters)
					VALUES
					('$key', $languageId, '$variables', '$title', '$template', '$url' , $header, $footer, $body, '$js', '$css', $redirect, '$getParameters');
                "
            );
        }

        return $this;
    }

    
    /**
     * Add/Delete user
     *
     * @param $login
     * @param $password
     * @param $language
     * @param $isAdmin
     *
     * @return ShaHelper
     */
    public function content($key, $language, $value, $type)
    {
    
    	$value = ShaUtilsString::cleanForSQL($value);
    
    	if ($this->_type == ShaHelper::CONST_TYPE_ADD) {
    
    		//Add root user
    		ShaContext::bddInsert(
    			"INSERT INTO shaoline_content
    				(content_key, language_id, content_value, content_type)
    			VALUES
    				('$key', '$language', '$value', $type)
    			"
    		);
    	} else {
    
	    	ShaContext::delete(
	    		"DELETE FROM shaoline_user WHERE content_key = '$key' AND language_id = '$language'"
	    	);
    
    	}
    
    	return $this;
    	}

}