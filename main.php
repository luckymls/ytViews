<?php

# Copyright (c) 2017-2018 The YtViews Authors (https://github.com/luckymls)
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.


function getViews($url){
	

	$r = file_get_contents($url);
	return intval(str_replace('.','',explode(' ',explode('class="short-view-count style-scope yt-view-count-renderer">', $r)[1])[0]));
	
}


if($argv[1]){
	while($i < $argv[1]){

$url = 'https://www.youtube.it/watch'; 

$data = [
'hl' => 'it', //Language
'gl' => 'IT', //Language
'v' => '' //Url of video
];

#$viewBefore = getViews("https://www.youtube.it/watch?v=$data[v]");
#$viewBefore = getViews($url.'?v='.$data['v']);
#$viewAfter = $viewBefore+$argv[1];

$getHeaders = get_headers($url, 1);

$setCookie = $getHeaders['Set-Cookie'];
$visitorLiveHash = explode('=', explode(';', $setCookie[2])[0])[1];
$yscHash = explode('=', explode(';', $setCookie[1])[0])[1];

$cookieSession = "VISITOR_INFO1_LIVE=$visitorLiveHash; YSC=$yscHash;";


$data = http_build_query($data);
$rand = rand(100, 999);
$context_options = array (
        'http' => array (
            'method' => 'POST',
            'header'=> "Content-type: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng;q=0.8"."\r\n"
               . "Content-Length: " . strlen($data) . "\r\n"
				
				. "User-agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko)"."\r\n"
				. "cache-control: max-age=0"."\r\n"
				. "Authority: clients4.google.com"."\r\n"
				. "Cookie: $cookieSession"."\r\n"
				#. "REMOTE_ADDR: 192.$rand.29.32"."\r\n",
				
				,
            'content' => $data
            )
        );

$context = stream_context_create($context_options	);
$result = @file_get_contents($url, false, $context);

 if($result) $ok = 1;
	file_put_contents('ri.txt', $result);
	
$i++;
echo "Request: $i/$argv[1]
Success: $ok 	\n";
	}
	
}
	
