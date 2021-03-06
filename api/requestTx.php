<?php
	function http($url, $data='', $method='POST')
	{   
		//echo "  url=".$url;
		//echo "  data=".$data;
	    $curl = curl_init(); // 启动一个CURL会话  
	    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址  
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查  
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在  
	    //curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器  
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转  
	    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer  
	    if($method=='POST'){  
	        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求  
	        if ($data != ''){  
	            curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包  
	        }  
	    }  
	    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环  
	    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容  
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回  
	    $tmpInfo = curl_exec($curl); // 执行操作  
	    curl_close($curl); // 关闭CURL会话  
	    return $tmpInfo; // 返回数据  
	}  
	$requestBody = @file_get_contents('php://input');
	$arrayBody = json_decode($requestBody, true);
	$addr = $arrayBody["addr"];
	$pageSize = $arrayBody["pageSize"];
	$pageNum = $arrayBody["pageNum"];

	$db = new MySQLi("120.79.88.105","root","123","test");


	$sqlCount = "select count(*) count from txlist";
	$sql = "select * from txList";
	
	if($addr != "")
	{
		$sql .= " where sendAddr = \"" . $addr."\" or revAddr = \"". $addr ."\"";
		$sqlCount .= " where sendAddr = \"" . $addr."\" or revAddr = \"". $addr ."\"";
	}
	else
	{
		$sql .= " ORDER BY time desc";
	}

	$dataCount = $db->query($sqlCount);// 获取所有数据
	$dataCount = $dataCount->fetch_row();
	$pageNum = ($pageNum - 1) * $pageSize;
	$sql .= " limit ".$pageNum.",".$pageSize;

	//echo $sql;
	$reslut = $db->query($sql);

	
	$returnBody = array();
	if($reslut)
	{
		$i = 0;
		while($attr = $reslut->fetch_row())
		{
			$returnBody[$i] = array();

			$returnBody[$i]["id"] = $attr[0];
			$returnBody[$i]["sendAddr"] = $attr[1];
			$returnBody[$i]["revAddr"] = $attr[2];
			$returnBody[$i]["txHash"] = $attr[3];
			$returnBody[$i]["time"] = $attr[4];
			$returnBody[$i]["dataCount"] = $dataCount[0];
			$i += 1;
		}
	}
	//echo "return=---";
	$returnJson = json_encode($returnBody);
	echo $returnJson;

?>



