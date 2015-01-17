<?php

if ( ! function_exists('adminUrl'))
{
	function adminUrl($str)
	{
		$prefix = _ADMIN_BASE_;
		return $prefix . $str;
	}

}

if ( ! function_exists('currentUserName'))
{
	function currentUserName()
	{
		$user = Auth::getUser();
		if ( ! is_null($user))
		{
			return $user->username;
		}

		return null;
	}
}

if ( ! function_exists('currentUserId'))
{
	function currentUserId()
	{
		$user = Auth::getUser();
		if ( ! is_null($user))
		{
			return $user->id;
		}

		return null;
	}
}


if ( ! function_exists('is_moderator'))
{
	function is_moderator()
	{
		$Acl = app('AccessControl');
		return $Acl->is_moderator();
	}
}


if ( ! function_exists('apiUrl'))
{
	function apiUrl($str)
	{
		$prefix = _API_BASE_;
		return $prefix . $str;
	}
}

if ( ! function_exists('frontUrl'))
{
	function frontUrl($str)
	{
		$prefix = _FRONT_BASE_;
		return $prefix . $str;
	}
}

if ( ! function_exists('dd'))
{
	function dd($value)
	{
		die(call_user_func_array('var_dump', func_get_args()));
	}
}


if ( ! function_exists('admin_asset'))
{
	function admin_asset($path)
	{
		return URL::asset('packages/raftalks/ravel/'.$path);
	}
}

if ( ! function_exists('aw'))
{
	function aw($str)
	{
		return "{{ $str }}";
	}
}

if ( ! function_exists('showflag'))
{
	function showflag($ccode)
	{
		return "<img  class='flag flag-$ccode'/>";
	}
}

if ( ! function_exists('langflag'))
{
	function langflag($lang)
	{
		$ccode = Config::get("ravel::flags.$lang",'en');
		if ( ! is_null($ccode))
		{
			return showflag($ccode);
		}
	}
}

if ( ! function_exists('current_lang'))
{
	function current_lang()
	{
		return Config::get('app.locale');
	}
}

if ( ! function_exists('showActivated'))
{
	function showActivated()
	{
		return '<p>Activated</p>';
	}
}


if ( ! function_exists('showDeactivated'))
{
	function showDeactivated()
	{
		return '<p>Deactivated</p>';
	}
}


if ( ! function_exists('makeApiKey'))
{
	function makeApiKey()
	{
		$uniqid = uniqid();
		$rand = rand(1000,9999);
		$key = md5($rand . $uniqid);
		
		return $key;
	}
}

if ( ! function_exists('is_closure'))
{
	function is_closure($t) {
   		 return is_object($t) && ($t instanceof Closure);
	}
}


if ( ! function_exists('buildTree'))
{
	function buildTree(array $elements, $parentId = 0, $parentKey = 'parent_id', $childKey='children') {
	    
	    $branch = array();
	    foreach ($elements as $menu_id => $element) {
	        if ($element[$parentKey] == $parentId) {
	            $children = buildTree($elements, $menu_id, $parentKey, $childKey);
	            if ($children) {
	                $element[$childKey] = $children;
	            }
	            $branch[] = $element;
	        }
	    }

	    return $branch;
	}
}

if ( ! function_exists('array_column'))
{
	function array_column(array $input, $columnKey, $indexKey = null) {
        $result = array();
   
        if (null === $indexKey) {
            if (null === $columnKey) {
                // trigger_error('What are you doing? Use array_values() instead!', E_USER_NOTICE);
                $result = array_values($input);
            }
            else {
                foreach ($input as $row) {
                    $result[] = $row[$columnKey];
                }
            }
        }
        else {
            if (null === $columnKey) {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row;
                }
            }
            else {
                foreach ($input as $row) {
                    $result[$row[$indexKey]] = $row[$columnKey];
                }
            }
        }
   
        return $result;
    }
}

if ( ! function_exists('is_really_writable'))
{
    /**
     * Tests for file writability
     *
     * is_writable() returns TRUE on Windows servers when you really can't write to
     * the file, based on the read-only attribute. is_writable() is also unreliable
     * on Unix servers if safe_mode is on.
     *
     * @param   string
     * @return  void
     */
    function is_really_writable($file)
    {
        // If we're on a Unix server with safe_mode off we call is_writable
        if (DIRECTORY_SEPARATOR === '/' && (bool) @ini_get('safe_mode') === FALSE)
        {
            return is_writable($file);
        }

        /* For Windows servers and safe_mode "on" installations we'll actually
         * write a file then read it. Bah...
         */
        if (is_dir($file))
        {
            $file = rtrim($file, '/').'/'.md5(mt_rand());
            if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
            {
                return FALSE;
            }

            fclose($fp);
            @chmod($file, DIR_WRITE_MODE);
            @unlink($file);
            return TRUE;
        }
        elseif ( ! is_file($file) OR ($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE)
        {
            return FALSE;
        }

        fclose($fp);
        return TRUE;
    }
}

if ( ! function_exists('format_date'))
{
    /**
     * Formats a timestamp into a human date format.
     *
     * @param int $unix The UNIX timestamp
     * @param string $format The date format to use.
     * @return string The formatted date.
     */
    function format_date($unix, $format = '')
    {
        if ($unix == '' || !is_numeric($unix)) {
            $unix = strtotime($unix);
        }

        if ( ! $format) {
            $format = Setting::get('date_format');
        }

        return strstr($format, '%') !== false ? ucfirst(utf8_encode(strftime($format, $unix))) : date($format, $unix);
    }
}

if ( ! function_exists('pick_trans'))
{
    /**
     * To choose the language from right place
     */
    function pick_trans($item, $options = array())
    {
        if (strpos($item, '::') !== false)
        {
            return Lang::get($item, $options);
        }

        $pkg = Session::get('onPackage');
        $loc_reference = "{$pkg}.{$item}";
        $pkg_reference = "{$pkg}::{$pkg}.{$item}";

        if ($pkg != 'cmsharenjoy')
        {
            if (Lang::has('cmsharenjoy.'.$item))
            {
                return Lang::get('cmsharenjoy.'.$item, $options);
            }

            if (Lang::has('cmsharenjoy::cmsharenjoy.'.$item))
            {
                return Lang::get('cmsharenjoy::cmsharenjoy.'.$item, $options);
            }
        }

        if (Lang::has($loc_reference))
        {
            return Lang::get($loc_reference, $options);
        }
        
        if (Lang::has($pkg_reference))
        {
            return Lang::get($pkg_reference, $options);
        }

        if (Lang::has($item))
        {
            return Lang::get($item, $options);
        }

        if (Lang::has('cmsharenjoy::'.$item))
        {
            return Lang::get('cmsharenjoy::'.$item, $options);
        }
        
        return false;
    }
}

if ( ! function_exists('ii'))
{
    /**
     * For Debugbar info method
     * @param mixed $data
     * @return Debugbar
     */
    function ii($data, $warning = null)
    {
        return is_null($warning) ? Debugbar::info($data) : Debugbar::warning($warning).Debugbar::info($data);
    }
}

if ( ! function_exists('ww'))
{
    /**
     * For Debugbar warning method
     * @param mixed $data
     * @return Debugbar
     */
    function ww($warning = null)
    {
        return is_null($warning) ? Debugbar::warning('warning') : Debugbar::warning($warning);
    }
}

if ( ! function_exists('start'))
{
    /**
     * For Debugbar start measure method
     */
    function start($name = 'Custom vendor', $description = null)
    {
        return Debugbar::startMeasure($name, $description);
    }
}

if ( ! function_exists('stop'))
{
    /**
     * For Debugbar stop measure method
     */
    function stop($name = 'Custom vendor')
    {
        return Debugbar::stopMeasure($name);
    }
}

if ( ! function_exists('set_package_asset_to_view'))
{
    /**
     * To set the asset to View
     */
    function set_package_asset_to_view($asset)
    {
        $path    = Config::get('cmsharenjoy::assets.path');
        $package = Config::get('cmsharenjoy::assets.package.'.$asset);

        if (count($package))
        {
            foreach ($package as $key => $value)
            {
                if ($value['queue'])
                {
                    Theme::asset()->queue($value['type'])
                                  ->add($key, $path.$value['file']);
                }
                else
                {
                    Theme::asset()->add($key, $path.$value['file']);
                }
            }
        }
    }
}


