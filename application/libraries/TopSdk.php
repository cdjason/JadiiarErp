<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TOP SDK 入口文件
 * 请不要修改这个文件，除非你知道怎样修改以及怎样恢复
 * @author wuxiao
 */

/**
 * 定义常量开始
 * 在include("TopSdk.php")之前定义这些常量，不要直接修改本文件，以利于升级覆盖
 */
/**
 * SDK工作目录
 * 存放日志，TOP缓存数据
 */
if (!defined("TOP_SDK_WORK_DIR"))
{
	define("TOP_SDK_WORK_DIR", "/tmp/");
}
/**
 * 是否处于开发模式
 * 在你自己电脑上开发程序的时候千万不要设为false，以免缓存造成你的代码修改了不生效
 * 部署到生产环境正式运营后，如果性能压力大，可以把此常量设定为false，能提高运行速度（对应的代价就是你下次升级程序时要清一下缓存）
 */
if (!defined("TOP_SDK_DEV_MODE"))
{
	define("TOP_SDK_DEV_MODE", true);
}
/**
 * 定义常量结束
 */

/**
 * 找到lotusphp入口文件，并初始化lotusphp
 * lotusphp是一个第三方php框架，其主页在：lotusphp.googlecode.com
 */
/*必须去掉的lotusphp框架
$lotusHome = dirname(__FILE__) . DIRECTORY_SEPARATOR . "lotusphp_runtime" . DIRECTORY_SEPARATOR;
include($lotusHome . "Lotus.php");
$lotus = new Lotus;
$lotus->option["autoload_dir"] = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'top';
$lotus->devMode = TOP_SDK_DEV_MODE;
$lotus->defaultStoreDir = TOP_SDK_WORK_DIR;
$lotus->init();
 */

class TopSdk
{
    var $CI;
	var $top_cilent;
	var $app_key='12056677';
	var $secret_key='aa68718a6d55287e70dd3db3a4641340';
	var $_req=null;
	var $format ='array';
    var $req;
    public function __construct($config = array())
    {
        include("top/TopClient.php");
        include("top/RequestCheckUtil.php");
        if (count($config) > 0)
		{
			$this->initialize($config);
		}
		$this->CI =& get_instance();
		$this->top_cilent = new TopClient;
		if (in_array($this->format, array('xml', 'json'))) {
			$this->top_cilent->format = $this->format;
		}
		$this->top_cilent->appkey = $this->app_key;
		$this->top_cilent->secretKey = $this->secret_key;
		log_message('debug', "TopSdk Class Initialized");
    }

    //$api_home = dirname(__FILE__) . DIRECTORY_SEPARATOR;
    
    function autoload($name)
    {
        //global $api_home;
        try
        {
            include('top/request/'.$name.'.php');
		    $this->req = new $name;
        }catch(Exception $e) 
        {
            echo $e->getMessage(), "\n";
        }
    }
	// ------------------------------------------------------------------------
	
	/**
	 * get_data
	 *
	 * COMMENT : get_data : comment
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function get_data()
	{
		$resp = $this->top_cilent->execute($this->req);
        if ("array" == $this->format) {	
			$resp = $this->_get_object_vars_final($resp);
		}
		return $resp;
	}    	
	// ------------------------------------------------------------------------
	
	/**
	 * get_object_vars_final
	 *
	 * COMMENT : get_object_vars_final : comment
	 *
	 * @access	private
	 * @param	string
	 * @return	bool
	 */
    private function _get_object_vars_final ($obj)
    {
        if (is_object($obj)) {
            $obj = get_object_vars($obj);
        }

        if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                $obj[$key] = $this->_get_object_vars_final($value);
            }
        }
        return $obj;
    }	
    // ------------------------------------------------------------------------
	
	/**
	 * initialize
	 *
	 * COMMENT : initialize : comment
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$this->$key = $val;
			}
		}
	}
	// ------------------------------------------------------------------------
	
	/**
	 * __call
	 *
	 * COMMENT : __call : comment
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	public function __call($method, $args)
	{
		if (!is_object($this->_req)) {
			throw new Exception("没有初始化请求方法", 1);
			
		}
		//var_dump($args);
        if(method_exists($this->_req, $method))
        {
            $this->_req->$method($args[0]);
        }
	}
}
