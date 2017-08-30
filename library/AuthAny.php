<?php

/**
 * Social Share Locker
 * Core
 *
 * @author      Social Suite <support@suite.social>
 * @copyright   2017 Social Suite
 * @link        http://suite.social
 * @license     Commercial
 *
 * @category    Core
 * @package     Social Share Locker
 */
class AuthAny {

    public $cookieTimeout = 2147483647;
    public $env;
    public $providers;
    public $providerConfig;
    public $requestedProvider = null;
    public $error = array();
    private $handler;
    public $loginConfig;

    /**
     * Constructor
     *
     * @param   string	$env
     * @return  void
     */
    public function __construct($env = 'live') {
        $this->env = $env;
        $this->setProviders($this->env);
        $this->setProviderConfig($this->env);
        $this->setRequestedProvider();
        $this->setHandler($this->getRequestedProvider());
        $this->setLoginConfig();
    }

    /**
     * Get Errors
     *
     * @return	array
     */
    public function getError() {
        return $this->error;
    }

    /**
     * Set Error
     *
     * @param	mixed	$error
     * @return	void
     */
    public function setError($error) {
        $this->error[] = $error;
    }

    /**
     * Set Login Configuration
     *
     * @return	void
     */
    public function setLoginConfig() {
        $config = AUTHANY_ROOT . '/Config/AuthAny.ini';
        if (file_exists($config)) {
            $this->loginConfig = new Zend_Config_Ini($config, 'login');
        } else {
            trigger_error('AuthAny.ini not found', E_USER_ERROR);
        }
    }

    /**
     * Get Login Configuration
     *
     * @return	object
     */
    public function getLoginConfig() {
        return $this->loginConfig;
    }

    /**
     * Determine if the requested path
     * is a login attempt
     *
     * @param   string	$path
     * @return  boolean
     */
    public function isLoginAttempt($path = null) {
        $path = ( is_null($path) ) ? $_SERVER['REQUEST_URI'] : $path;

        if (empty($_GET)) {
            if ($path != AUTHANY_LOGIN_ROOT) {
                if (strpos($path, AUTHANY_LOGIN_ROOT) !== false) {
                    return true;
                }
            }
        }
    }

    /**
     * Handle Login Request
     *
     * @param	string	$request
     * @return	array
     */
    public function handleRequest($request = null) {
        $request = ( is_null($request) ) ? $_SERVER['REQUEST_URI'] : $request;
        $provider = $this->getRequestedProvider();

        if (!is_null($provider)) {
            $config = $this->getProviderConfig($provider);

            if (!empty($config)) {
                if ($config->{$this->env}->enabled == 1) {
                    try {
                        $className = 'AuthAny_Provider_' . ucfirst($provider);
                        $handler = new $className;
                    } catch (Exception $e) {
                        $handler = $this;
                    }

                    $this->setHandler($handler);
                } else {
                    // not enabled
                    $this->setError('NOT_ENABLED');
                    return $this->getError();
                }
            } else {
                // no provider...
                $this->setError('NO_PROVIDER');
                return $this->getError();
            }
        } else {
            if ($this->isLoginAttempt($request)) {
                trigger_error('no provider is configured for:  ' . $request, E_USER_ERROR);
            }
        }

        if ($this->isLoginAttempt($request)) {
            return $this->getHandler()->handleAuth($config);
        } elseif (isset($_GET['code'])) {
            return $this->getHandler()->handleCode($_GET['code']);
        } elseif (!empty($_GET)) {
            return $this->getHandler()->handleGet();
        }
    }

    /**
     * Get Authorization Code
     *
     * @param	array	$config
     * @return	void
     */
    public function handleAuth($config = array()) {
        if (!empty($config)) {
            $api = $config->get('api');
            $env = $config->get($this->env);

            $params = array(
                'client_id' => $env->appId,
                'state' => UUID::mint(4)->__toString(),
                'scope' => $api->scope,
                'response_type' => $api->responseType,
                'redirect_uri' => AUTHANY_BASEURL . '/' . $env->redirectUri
            );

            $apiEndpoint = $api->authEndpoint . '?' . http_build_query($params);
            header('Location: ' . $apiEndpoint);
        }
    }

    /**
     * Handle Authorization Code
     *
     * @param	string	$code
     * @return	array
     */
    public function handleCode($code) {
        $code = trim($code);
        if (strlen($code)) {
            $provider = $this->getRequestedProvider();
            if (!is_null($provider)) {
                $config = $this->getProviderConfig($provider);
                if (!empty($config)) {
                    if ($config->{$this->env}->enabled == 1) {
                        $token = $this->getAccessToken($code, $config);
                        parse_str($token, $response);

                        if (isset($response['access_token'])) {
                            return $this->handleAccessToken($response);
                        } else {
                            return $response;
                        }
                    } else {
                        // not enabled
                        trigger_error('Provider:  ' . $provider . ' not enabled', E_USER_ERROR);
                    }
                } else {
                    // provider
                    trigger_error('No config for this Provider:  ' . $provider, E_USER_ERROR);
                }
            } else {
                trigger_error('No Provider specified', E_USER_ERROR);
            }
        }
    }

    /**
     * Get Access Token
     *
     * @param	string	$code
     * @param	array	$config
     * @return	array
     */
    public function getAccessToken($code, $config = array()) {
        if (!empty($config) AND strlen($code)) {
            $api = $config->get('api');
            $env = $config->get($this->env);

            $params = array(
                'client_id' => $env->appId,
                'client_secret' => $env->appSecret,
                'code' => $code,
                'redirect_uri' => AUTHANY_BASEURL . '/' . $env->redirectUri
            );

            $apiEndpoint = $api->oauthEndpoint . '?' . http_build_query($params);
            $response = $this->curl_get_url($apiEndpoint);

            return $response;
        }
    }

    /**
     * Get Providers
     *
     * @return	array
     */
    public function getProviders() {
        return $this->providers;
    }

    /**
     * Set Providers
     *
     * @param	string	$env
     * @return	void
     */
    public function setProviders($env = 'live') {
        $providers = array();
        $files = glob(AUTHANY_ROOT . '/Provider/config/*.ini');

        if (!empty($files)) {
            foreach ($files AS $key => $value) {
                $providers[] = $value;
            }
        }

        $this->providers = $providers;
    }

    /**
     * Get Provider Config
     *
     * @param	string	$provider
     * @return	array
     */
    public function getProviderConfig($provider = null) {
        if (is_null($provider)) {
            return $this->providerConfig;
        } else {
            return $this->providerConfig[$provider];
        }
    }

    /**
     * Set Provider Config
     *
     * @param	string	$env
     * @param	string	$provider
     * @return	array
     */
    public function setProviderConfig($env = 'live', $provider = null) {
        $config = array();
        if (!empty($this->providers)) {
            foreach ($this->providers AS $key => $value) {
                $name = $this->getFilename(basename($value));
                $config[$name] = new Zend_Config_Ini($value);
            }
        }

        $this->providerConfig = $config;
    }

    /**
     * Set Requested Provider
     *
     * @param	string	$path
     * @return	void
     */
    public function setRequestedProvider($path = null) {
        if (!empty($_GET)) {
            $questionMarkPos = strpos($_SERVER['REQUEST_URI'], '?');
            if ($questionMarkPos !== false) {
                $path = substr($_SERVER['REQUEST_URI'], 0, $questionMarkPos);
            }
        } else {
            $path = ( is_null($path) ) ? $_SERVER['REQUEST_URI'] : $path;
        }

        $tokens = explode('/', $path);

        if (!empty($tokens)) {
            $tokens = array_filter($tokens, 'strlen');
            $key = max(array_keys($tokens));
            $path = $tokens[$key];
        }

        if (array_key_exists($path, $this->providerConfig)) {
            $this->requestedProvider = $path;
        }
    }

    /**
     * Get Requested Provider
     *
     * @return	array
     */
    public function getRequestedProvider() {
        return $this->requestedProvider;
    }

    /**
     * Set Authentication Handler
     *
     * @param	string	$handler
     * @return	void
     */
    public function setHandler($handler) {
        $this->_handler = $handler;
    }

    /**
     * Get Authentication Handler
     *
     * @return	string
     */
    public function getHandler() {
        return $this->_handler;
    }

    /**
     * Call the Local Login Function or Class
     * specified in $loginConfig
     *
     * @param	array	$data
     * @return	mixed
     */
    public function handleLocalLogin($data = array()) {
        if (!empty($data)) {
            if ($this->loginConfig->callbackType == 'class') {
                return call_user_func_array(array($this->loginConfig->callbackClass, $this->loginConfig->callbackMethod), array($data));
            } else {
                return call_user_func_array($this->loginConfig->callbackFunction, array($data));
            }
        }
    }

    /**
     * Login a User Locally
     *
     * @param	array	$data
     * @return	void
     */
    public function loginLocal($data = array()) {
        if (!empty($data)) {
            $provider = $this->getRequestedProvider();
            $_SESSION[$provider] = $data;

            if (isset($_SESSION['email'])) {
                $_SESSION['email'] = $_SESSION[$provider]['email'];
            }

            $_SESSION['logged_in'] = 1;
            $_SESSION['network'] = array();
            $_SESSION['network']['name'] = $provider;
            $_SESSION['sid'] = $_SESSION[$provider]['id'];

			$share_link = '';
			if (isset($_SESSION[$provider]['id'])) {
				$share_link = 'http://localhost/erwin/testing/social-logins/coder/share/connect/'.$_SESSION['share_page'].'?id='.$_SESSION[$provider]['id'].'&cid='.$_SESSION['client_id'];
			}
            $_SESSION['share_link'] = $share_link;

            // redirect
            header('Location: ' . AUTHANY_BASEURL);
        }
    }

    /**
     * Get filename
     *
     * @param   string
     * @return  string
     */
    public function getFilename($file) {
        return substr($file, 0, strrpos($file, '.', -1));
    }

    /**
     * Post to a URL via cURL
     *
     * @link	http://bavotasan.com/2011/post-url-using-curl-php
     * @link	http://davidwalsh.name/curl-post
     *
     * @param   string  $url
     * @param	array	$data
     * @param	array	$headers
     * @param   boolean $returnCurlInfo
     * @param   int     $timeout
     * @param   int     $maxRedirs
     * @return  array
     */
    public function curl_post_url($url, $data = array(), $headers = array(), $returnCurlInfo = false, $timeout = 60, $maxRedirs = 10) {
        if (empty($data)) {
            return array();
        }

        $fields = '';
        foreach ($data AS $key => $value) {
            // decode if already URL encoded,
            // this insures that the URL is URL encoded
            $fields .= $key . '=' . urlencode(urldecode($value)) . '&';
        }

        rtrim($fields, '&');

        $post = curl_init();

        if (!empty($headers)) {
            curl_setopt($post, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($post, CURLOPT_USERAGENT, 'PHP ' . phpversion());
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);

        if (!strlen(ini_get('open_basedir')) AND ini_get('safe_mode') == 'Off') {
            curl_setopt($post, CURLOPT_FOLLOWLOCATION, true);
        }

        curl_setopt($post, CURLOPT_MAXREDIRS, $maxRedirs);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($post, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($post, CURLOPT_HEADER, false);

        if ($returnCurlInfo) {
            curl_setopt($post, CURLINFO_HEADER_OUT, true);
        }

        curl_setopt($post, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($post, CURLOPT_TIMEOUT, $timeout);

        $response = curl_exec($post);

        if ($returnCurlInfo) {
            $originalResponse = $response;
            $response = array();
            $response['info'] = curl_getinfo($post);
            $response['html'] = $originalResponse;
        }

        if ($error = curl_error($post)) {
            $response['error'] = $error;
            $response['errorno'] = curl_errno($post);
        }

        curl_close($post);

        return $response;
    }

    /**
     * fetch the response of a URL via cURL
     *
     * @param   string  $url
     * @param	array	$headers
     * @param   boolean $returnCurlInfo
     * @param	boolean	$anonymizeReferrer
     * @param	boolean	$randomizeUserAgent
     * @param	string	$userAgent
     * @param   int     $timeout
     * @param   int     $maxRedirs
     * @return  mixed	array or string
     */
    public function curl_get_url($url, $headers = array(), $returnCurlInfo = false, $anonymizeReferrer = false, $randomizeUserAgent = false, $userAgent = null, $timeout = 60, $maxRedirs = 10) {
        if (!strlen($url)) {
            return false;
        }

        if ($anonymizeReferrer) {
            $referers = array(
                'www.google.com',
                'yahoo.com',
                'msn.com',
                'ask.com',
                'live.com'
            );

            $referer = array_rand($referers);
            $referer = 'http://' . $referers[$referer];
        }

        if ($randomizeUserAgent) {
            $browsers = array(
                'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0',
                'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.3) Gecko/2008092510 Ubuntu/8.04 (hardy) Firefox/3.0.3',
                'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20060918 Firefox/2.0',
                'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3',
                'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)',
                'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0',
                'Googlebot/2.1 (+http://www.google.com/bot.html)'
            );

            $browser = array_rand($browsers);
            $browser = $browsers[$browser];
        }

        $ch = curl_init($url);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if ($randomizeUserAgent) {
            curl_setopt($ch, CURLOPT_USERAGENT, $browser);
        } else {
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP ' . phpversion());
        }

        if ($anonymizeReferrer) {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }

        curl_setopt($ch, CURLOPT_AUTOREFERER, true);

        if (!strlen(ini_get('open_basedir')) AND ini_get('safe_mode') == 'Off') {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }

        curl_setopt($ch, CURLOPT_MAXREDIRS, $maxRedirs);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        if ($returnCurlInfo) {
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);


        $response = curl_exec($ch);

        if ($returnCurlInfo) {
            $originalResponse = $response;
            $response = array();
            $response['info'] = curl_getinfo($ch);
            $response['data'] = $originalResponse;
        }

        if ($error = curl_error($ch)) {
            $response['error'] = $error;
            $response['errorno'] = curl_errno($ch);
        }

        curl_close($ch);

        return $response;
    }

    /**
     * Recursively convert an object
     * to an array
     *
     * @link	https://coderwall.com/p/8mmicq
     * @param	object	$object
     * @return	array
     */
    public function objectToArray($object) {
        if (is_object($object)) {
            $object = get_object_vars($object);
        }

        return is_array($object) ? array_map(array($this, __FUNCTION__), $object) : $object;
    }

    public function handleGet() {
        // START:	Error Handling
        $error = array();
        $possibleErrors = array(
            'error',
            'error_code',
            'error_description',
            'error_reason'
        );

        foreach ($possibleErrors AS $key => $value) {
            if (isset($_GET[$value])) {
                $error[$value] = '<strong>' . $value . ':</strong>  ' . $_GET[$value];
            }
        }
        // END:		Error Handling

        if (empty($error)) {
            return $this->handleAuth($this->getProviderConfig());
        }
    }

    // @link	http://stackoverflow.com/questions/3125410/trying-to-digitally-sign-via-hmac-sha1-with-php
    // @link	http://gmaps-samples.googlecode.com/svn/trunk/urlsigning/UrlSigner.php-source
    // Encode a string to URL-safe base64
    public function encodeBase64UrlSafe($value) {
        return str_replace(array('+', '/'), array('-', '_'), base64_encode($value));
    }

    // Decode a string from URL-safe base64
    public function decodeBase64UrlSafe($value) {
        return base64_decode(str_replace(array('-', '_'), array('+', '/'), $value));
    }

    // Sign a URL with a given crypto key
    // Note that this URL must be properly URL-encoded
    public function signUrl($myUrlToSign, $privateKey) {
        // parse the url
        $url = parse_url($myUrlToSign);

        $urlPartToSign = $url['path'] . "?" . $url['query'];

        // Decode the private key into its binary format
        $decodedKey = $this->decodeBase64UrlSafe($privateKey);

        // Create a signature using the private key and the URL-encoded
        // string using HMAC SHA1. This signature will be binary.
        $signature = hash_hmac("sha1", $urlPartToSign, $decodedKey, true);

        $encodedSignature = $this->encodeBase64UrlSafe($signature);

        return $myUrlToSign . "&signature=" . $encodedSignature;
    }

// END OF THIS CLASS 	
}
