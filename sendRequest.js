// url is URL ajax 
// method: GET | POST
// json format: 1(type='json') else (type='html') 
// data format: 'key1=value1&key2=value2'
// async format: true|false
// doing: function link
/***
example: var F = function(data){document.getElementById('content').innerHTML = data;}
	     sendRequest('/test/','post',0,'',1,F);

example: var F = function(data){
			document.getElementById('content').innerHTML = data.name+' '+data.surname;}
		 sendRequest('test.json','post',1,'',1,F);
***/
function sendRequest(url,method,json,data,async,doing){
	var ajax;
	if (window.XMLHttpRequest){
		ajax=new XMLHttpRequest();
	}else{
		ajax=new ActiveXObject("Microsoft.XMLHTTP");
	}
	ajax.onreadystatechange=function(){
		if (ajax.readyState==4 && ajax.status==200)
		    {
		    	if(json == 1){
		    		var result = JSON.parse(ajax.responseText);
		    	}else{
		    		var result = ajax.responseText;
		    	}
		    	doing(result);
		    }
	}
	if(method == 'get' || method == 'GET' || method == 'Get'){
		if(data != ''){
			ajax.open(method,url+'?'+data,async);
		}else{
			ajax.open(method,url,async);
		}
	}
	if(method == 'post' || method == 'POST' || method == 'Post'){
		ajax.open(method,url,async);
		ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		if(data != ''){
			ajax.send(data);
		}else{
			ajax.send();
		}
	}else{
		ajax.send();
	}
}