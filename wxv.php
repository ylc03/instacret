<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "{F517B030-D249-433d-AE84-495F00EA3937}");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();
//$wechatObj->responseMsg();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
				$msgType = $postObj->MsgType;
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
				$time = time();
				$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
							
				if ($msgType == "event") {
					
					$event = $postObj->Event;
					if ($event = "subscribe") {
						$msgType = "text";
						$contentStr = "欢迎您添加摩洛幼儿英语官方微信号。";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}
					
				} else if ($msgType == "text") {
				
					$keyword = trim($postObj->Content);
					if($keyword == "reply")
					{
						$msgType = "text";
						$contentStr = "Welcome to wechat world!";
						$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
						echo $resultStr;
					}else{
						echo "Input something...";
					}
				}

        }else {
        	echo "";
        	exit;
        }
    }
	
	private function responseEvent(){
	
	}
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>