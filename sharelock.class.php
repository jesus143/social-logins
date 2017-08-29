<?php
class sharelock{

	 
	//constructor defined for class
	function __construct($id=0)
	{        
        $id = (int)$id;
	}
    

    //function to check visitor counts and store in file. beside this function checks if ip is duplicate or new. if there is new ip ip is stored in repective txt file
    function header($id,$ip,$cid)
    {
	    @session_start();
	    $id = $id;
	    $ipadd = $_SERVER['REMOTE_ADDR'];
	    $visitor_file='visitor_'.$cid.'_'.$id.'.txt';
	    if(file_exists($visitor_file))
	    {
	    	$cnt=file_get_contents($visitor_file);
	    	//print_r($cnt);
	    }
	    else
	    {
	    	$cnt=0;
	    	$finsertv = fopen ($visitor_file, "w");
	        fwrite($finsertv, $cnt);
	    }

	    if($ip=='1')
		{
       
			$ok = false;
	        
	    	$filename=$cid.'_'.$id.'.txt';
	        if(file_exists($filename))
	        {
	        	foreach(glob($filename) as $filenm)
				{
				  foreach(file($filenm) as $fli=>$fl)
				  {
				  	
				  	 $arr_list=explode(';',$fl);
				  	if (in_array($ipadd, $arr_list))				    
				    {
				        return $count = $cnt;
				    }
				    else
				    {
				    	$strr=$ipadd.';';
	        			file_put_contents($filename,$strr,FILE_APPEND) ;
				    	return $count = $cnt+1;
				    }
				  }
				}    	
	        }
	        else
	        {
	        	$ipadd = $_SERVER['REMOTE_ADDR'];
	        	$strr=$ipadd.';';
	        	$fh = fopen($filename, 'w');
	        	 file_put_contents($filename,$strr,FILE_APPEND);
	        	return $count = $cnt+1;
	        }				       
		}
		else
		{
			if(isset($_SESSION['sharelock-detect']))
	        {
	        
	            if (!isset($_COOKIE['sharelock-detect']))
	            {
	                $domain_name = preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);
	                setcookie('sharelock-detect', '', time() - 86400 * 1, '/', $domain_name);
	                setcookie('sharelock-detect', '1', time() + 86400 * 1, '/', $domain_name);
	                $_SESSION['sharelock-detect'] = 1;
	            }	
	            
	        } 
	        else
	        {
	            $domain_name = preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);
	            setcookie('sharelock-detect', '', time() - 86400 * 1, '/', $domain_name);
	            setcookie('sharelock-detect', '1', time() + 86400 * 1, '/', $domain_name);
	            $_SESSION['sharelock-detect'] = 1;		
	        }
       
       	 	$hash = md5($id . $this->get_URL());
       	 	//echo $_COOKIE[$hash];
        
	        if (!isset($_COOKIE[$hash]))
	        {
	        	
				if((isset($_COOKIE['sharelock-detect'])) && (isset($_SESSION['sharelock-detect'])))
				{
					$domain_name = preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);
					setcookie($hash, '', time() - 86400 * 1, '/', $domain_name);
					setcookie($hash, '1', time() + 86400 * 1, '/', $domain_name);	
					return $count = $cnt;         	
				}  
				else
				{
					return $count = $cnt+1; 
				}					
	        }
	        else
	        {
	        	return $count = $cnt;
	        }
	      
	    }                
    }

	//
	function visitor($id,$ip,$cid)
	{
		@session_start();
		$id = $id;
		$ipadd = $_SERVER['REMOTE_ADDR'];
		if($ip=='1')
		{

			$ok = false;

			$filename=$cid.'_'.$id.'.txt';
			if(file_exists($filename))
			{
				foreach(glob($filename) as $filenm)
				{
					foreach(file($filenm) as $fli=>$fl)
					{

						$arr_list=explode(';',$fl);
						if (in_array($ipadd, $arr_list))
						{
							return false;
						}
						else
						{
							$strr=$ipadd.';';
							file_put_contents($filename,$strr,FILE_APPEND) ;
							//return $count = $cnt+1;
							$visitor_file='visitor_'.$cid.'_'.$id.'.txt';
							if(file_exists($visitor_file))
							{
								$cnt=file_get_contents($visitor_file);
								$n= $cnt+1;
								$finsertv = fopen ($visitor_file, "w");
								fwrite($finsertv, $n);
								//print_r($cnt);
							}
							else
							{
								$cnt=0;
								$finsertv = fopen ($visitor_file, "w");
								fwrite($finsertv, $cnt);
							}
						}
					}
				}
			}
			else
			{
				$ipadd = $_SERVER['REMOTE_ADDR'];
				$strr=$ipadd.';';
				$fh = fopen($filename, 'w');
				file_put_contents($filename,$strr,FILE_APPEND);
				//return $count = $cnt+1;

				$visitor_file='visitor_'.$cid.'_'.$id.'.txt';
				if(file_exists($visitor_file))
				{
					$cnt=file_get_contents($visitor_file);
					$n= $cnt+1;
					$finsertv = fopen ($visitor_file, "w");
					fwrite($finsertv, $n);
					//print_r($cnt);
				}
				else
				{
					$cnt=0;
					$finsertv = fopen ($visitor_file, "w");
					fwrite($finsertv, $cnt);
				}
			}
		}
		//header("Location: http://suite.social/coder/share/connect/");
		//exit;
	}
    //function to retrieve url
	private function get_URL(){
		$URL = 'http';
		if (isset($_SERVER["HTTPS"]) && ( $_SERVER["HTTPS"] == "on" ) ) {
			$URL = $URL . "s";
		}
		$URL = $URL . "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$URL = $URL . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$URL = $URL . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		return $URL;
	}
}
?>