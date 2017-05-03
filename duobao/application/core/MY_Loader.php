<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
    /**
     * List of loaded sercices
     *
     * @var array
     * @access protected
     */
    protected $_ci_services = array();
    /**
     * List of paths to load sercices from
     *
     * @var array
     * @access protected
     */
    protected $_ci_service_paths = array();

    /**
     * Constructor
     *
     * Set the path to the Service files
     */
    public function __construct()
    {
        parent::__construct();
        load_class('Service', 'core');
        $this->_ci_service_paths = array(APPPATH);
    }

    /**
     * Service Loader
     *
     * Loads and instantiates services
     *
     * @param string $service
     * @param string $name
     * @param null   $params
     *
     * @return $this
     */
    public function service($service = '', $name = '', $params = null)
    {
        if (empty($service))
        {
            return $this;
        }
        elseif (is_array($service))
        {
            foreach ($service as $key => $value)
            {
                is_int($key) ? $this->service($value, '', $params) : $this->service($key, $value, $params);
            }

            return $this;
        }

        $path = '';

        if (($last_slash = strrpos($service, '/')) !== FALSE)
        {
            $path = substr($service, 0, ++$last_slash);
            $service = substr($service, $last_slash);
        }

        if (is_null($params) && $name && (! is_string($name) || is_numeric($name))) {
            $params = $name;
            $name = '';
        }

        if (empty($name))
        {
            $name = $service;
        }

        if (in_array($name, $this->_ci_services, TRUE))
        {
            return $this;
        }

        $CI =& get_instance();
        if (isset($CI->$name))
        {
            throw new RuntimeException('The service name you are loading is the name of a resource that is already being used: ' . $name);
        }

        $service = ucfirst($service);
        if ( ! class_exists($service)) {
            foreach($this->_ci_service_paths as $service_path)
            {
                if ( ! file_exists($full_path = $service_path . 'services/' . $path . $service . '.php'))
                {
                    continue;
                }

                require_once($full_path);

                if ( ! class_exists($service, FALSE))
                {
                    throw new RuntimeException($full_path . " exists, but doesn't declare class " . $service);
                }

                break;
            }

            if ( ! class_exists($service, FALSE))
            {
                throw new RuntimeException('Unable to locate the service you have specified: ' . $service);
            }
        }
        elseif ( ! is_subclass_of($service, 'MY_Service'))
        {
            throw new RuntimeException("Class " . $service . " already exists and doesn't extend MY_Service");
        }

        $this->_ci_services[] = $name;

        if (is_null($params)) {
            $CI->$name = new $service();
        } else {
            $CI->$name = new $service($params);
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Model Loader
     *
     * Loads and instantiates models.
     *
     * @param	string	$model		Model name
     * @param	string	$name		An optional object name to assign to
     * @param	bool	$db_conn	An optional database connection configuration to initialize
     * @param	    	$params
     * @return	object
     */
    public function model($model, $name = '', $db_conn = FALSE, $params = null)
    {
        if (empty($model))
        {
            return $this;
        }
        elseif (is_array($model))
        {
            foreach ($model as $key => $value)
            {
                is_int($key) ? $this->model($value, '', $db_conn) : $this->model($key, $value, $db_conn);
            }

            return $this;
        }

        $path = '';

        // Is the model in a sub-folder? If so, parse out the filename and path.
        if (($last_slash = strrpos($model, '/')) !== FALSE)
        {
            // The path is in front of the last slash
            $path = substr($model, 0, ++$last_slash);

            // And the model name behind it
            $model = substr($model, $last_slash);
        }

        if (is_null($params) && $name && (! is_string($name) || is_numeric($name))) {
            $params = $name;
            $name = '';
        }

        if (empty($name))
        {
            $name = $model;
        }

        if (in_array($name, $this->_ci_models, TRUE))
        {
            return $this;
        }

        $CI =& get_instance();
        if (isset($CI->$name))
        {
            throw new RuntimeException('The model name you are loading is the name of a resource that is already being used: '.$name);
        }

        if ($db_conn !== FALSE && ! class_exists('CI_DB', FALSE))
        {
            if ($db_conn === TRUE)
            {
                $db_conn = '';
            }

            $this->database($db_conn, FALSE, TRUE);
        }

        // Note: All of the code under this condition used to be just:
        //
        //       load_class('Model', 'core');
        //
        //       However, load_class() instantiates classes
        //       to cache them for later use and that prevents
        //       MY_Model from being an abstract class and is
        //       sub-optimal otherwise anyway.
        if ( ! class_exists('CI_Model', FALSE))
        {
            $app_path = APPPATH.'core'.DIRECTORY_SEPARATOR;
            if (file_exists($app_path.'Model.php'))
            {
                require_once($app_path.'Model.php');
                if ( ! class_exists('CI_Model', FALSE))
                {
                    throw new RuntimeException($app_path."Model.php exists, but doesn't declare class CI_Model");
                }
            }
            elseif ( ! class_exists('CI_Model', FALSE))
            {
                require_once(BASEPATH.'core'.DIRECTORY_SEPARATOR.'Model.php');
            }

            $class = config_item('subclass_prefix').'Model';
            if (file_exists($app_path.$class.'.php'))
            {
                require_once($app_path.$class.'.php');
                if ( ! class_exists($class, FALSE))
                {
                    throw new RuntimeException($app_path.$class.".php exists, but doesn't declare class ".$class);
                }
            }
        }

        $model = ucfirst($model);
        if ( ! class_exists($model))
        {
            foreach ($this->_ci_model_paths as $mod_path)
            {
                if ( ! file_exists($mod_path.'models/'.$path.$model.'.php'))
                {
                    continue;
                }

                require_once($mod_path.'models/'.$path.$model.'.php');
                if ( ! class_exists($model, FALSE))
                {
                    throw new RuntimeException($mod_path."models/".$path.$model.".php exists, but doesn't declare class ".$model);
                }

                break;
            }

            if ( ! class_exists($model, FALSE))
            {
                throw new RuntimeException('Unable to locate the model you have specified: '.$model);
            }
        }
        elseif ( ! is_subclass_of($model, 'CI_Model'))
        {
            throw new RuntimeException("Class ".$model." already exists and doesn't extend CI_Model");
        }

        $this->_ci_models[] = $name;
        if (is_null($params)) {
            $CI->$name = new $model();
        } else {
            $CI->$name = new $model($params);
        }
        return $this;
    }


    /**
     * View Loader
     *
     * Loads "view" files.
     *
     * @param	string	$view	View name
     * @param	array	$vars	An associative array of data
     *				to be extracted for use in the view
     * @param	bool	$return	Whether to return the view output
     *				or leave it to the Output class
     * @return	object|string
     */
    public function view($view, $vars = array(), $return = FALSE)
    {
        if (defined('MODULE') && MODULE) {
            $view = MODULE . '/' . $view;
        }
        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

    /**
     * 视图小部件
     *
     * @param string $widget
     * @param array  $data
     * @param bool   $return
     */
    public function widget($widget = '', $data = array(), $return = FALSE)
    {
        $CI =& get_instance();
        $this->service('widget_service', $data);
        $callable = array($CI->widget_service, $widget);
        if (is_callable($callable)) {
            $data['widget'] = call_user_func($callable);
        }
        return $CI->widget($widget, $data, $return);
    }

    /**
     * Internal CI Data Loader
     *
     * Used to load views and files.
     *
     * Variables are prefixed with _ci_ to avoid symbol collision with
     * variables made available to view files.
     *
     * @used-by	CI_Loader::view()
     * @used-by	CI_Loader::file()
     * @param	array	$_ci_data	Data to load
     * @return	object
     */
    protected function _ci_load($_ci_data)
    {
        // Set the default data variables
        foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val)
        {
            $$_ci_val = isset($_ci_data[$_ci_val]) ? $_ci_data[$_ci_val] : FALSE;
        }

        $file_exists = FALSE;

        // Set the path to the requested file
        if (is_string($_ci_path) && $_ci_path !== '')
        {
            $_ci_x = explode('/', $_ci_path);
            $_ci_file = end($_ci_x);
        }
        else
        {
            $_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
            $_ci_file = ($_ci_ext === '') ? $_ci_view.'.php' : $_ci_view;

            foreach ($this->_ci_view_paths as $_ci_view_file => $cascade)
            {
                if (file_exists($_ci_view_file.$_ci_file))
                {
                    $_ci_path = $_ci_view_file.$_ci_file;
                    $file_exists = TRUE;
                    break;
                }

                if ( ! $cascade)
                {
                    break;
                }
            }
        }

        if ( ! $file_exists && ! file_exists($_ci_path))
        {
            show_error('Unable to load the requested file: '.$_ci_file);
        }

        // This allows anything loaded using $this->load (views, files, etc.)
        // to become accessible from within the Controller and Model functions.
        $_ci_CI =& get_instance();
        foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var)
        {
            if ( ! isset($this->$_ci_key))
            {
                $this->$_ci_key =& $_ci_CI->$_ci_key;
            }
        }

        /*
         * Extract and cache variables
         *
         * You can either set variables using the dedicated $this->load->vars()
         * function or via the second parameter of this function. We'll merge
         * the two types and cache them so that views that are embedded within
         * other views can have access to these variables.
         */
        if (isset($_ci_view) && false !== strpos($_ci_view, '_widget/') && is_array($_ci_vars)) {
            extract(array_merge($this->_ci_cached_vars, $_ci_vars));
        } else {
            if (is_array($_ci_vars))
            {
                $this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
            }
            extract($this->_ci_cached_vars);
        }


        /*
         * Buffer the output
         *
         * We buffer the output for two reasons:
         * 1. Speed. You get a significant speed boost.
         * 2. So that the final rendered template can be post-processed by
         *	the output class. Why do we need post processing? For one thing,
         *	in order to show the elapsed page load time. Unless we can
         *	intercept the content right before it's sent to the browser and
         *	then stop the timer it won't be accurate.
         */
        ob_start();

        // If the PHP installation does not support short tags we'll
        // do a little string replacement, changing the short tags
        // to standard PHP echo statements.
        if ( ! is_php('5.4') && ! ini_get('short_open_tag') && config_item('rewrite_short_tags') === TRUE)
        {
            echo eval('?>'.preg_replace('/;*\s*\?>/', '; ?>', str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
        }
        else
        {
            include($_ci_path); // include() vs include_once() allows for multiple views with the same name
        }

        log_message('info', 'File loaded: '.$_ci_path);

        // Return the file data if requested
        if ($_ci_return === TRUE)
        {
            $buffer = ob_get_contents();
            @ob_end_clean();
            return $buffer;
        }

        /*
         * Flush the buffer... or buff the flusher?
         *
         * In order to permit views to be nested within
         * other views, we need to flush the content back out whenever
         * we are beyond the first level of output buffering so that
         * it can be seen and included properly by the first included
         * template and any subsequent ones. Oy!
         */
        if (ob_get_level() > $this->_ci_ob_level + 1)
        {
            ob_end_flush();
        }
        else
        {
            $_ci_CI->output->append_output(ob_get_contents());
            @ob_end_clean();
        }

        return $this;
    }
}
