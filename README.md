
# Auto Test for Yaf Demo
* Auto, 懒人化测试PHP代码

* 不仅仅只适合Ci ( Not Just For Yaf ! ) 

* You can make it for other frameworks. Delivered as php-extensions.


## Install
* composer require auto/ci
##### /\\^请多操作一次确保更新成功^/\
* composer update


## ADD  Auto Test  to Yaf 
* 在 `system\core\CodeIgniter.php`文件
* 这行代码上面：call_user_func_array(array(&$CI, $method), $params); 放入以下代码
```$php

      //执行测试模式
	if (AUTO_TEST_START) {
		try{
			$realArr = Auto\Auto::_auto(new $class, $method);
		} catch (\Exception $e) {
		//记录执行错误Log
		echo $e->getMessage();die;
		}
		$params = array();
		$params['data'] = $realArr;
	}
```
* 在`index.php`文件
* 加入下面的代码
```
/**
 * 开启注释测试模式
 */
define('AUTO_TEST_START', true);

/**
 * 配置你的API地址(建议:分开部署)
 */
define('AUTO_TEST_API_HOST','http://www.lidi.ci.com');

if (AUTO_TEST_START) {
    require_once __DIR__.'/vendor/autoload.php';
}
```

## Start Auto Test Demo ^_~:
### 举个栗子：
* 1、编辑`application\controllers\User.php`
```
              /**
               * 开启auto API Document
               */
               use Auto\Api;
           
               /**
                * TEST Demo
                * @AD array('name'=>'Luck','address'=>'Beijing')
                * User:  fomo3d.wiki
                * Email: fomo3d.wiki@gmail.com
                * Date: 2020/5/4
                */
               public function test_ok()
               {
                   //调用案例：
                   if (false) {
                       /**
                        * @var Auto\Data $data
                        */
                       $data = Auto\Auto::data();
                       
                       /**
                        * Ms. Demetris Dickens
                        */
                       $data::name();
                       
                       /**
                        * 9113 Greenfelder Inlet\nMaudiehaven, NE 56622
                        */
                       $data::address();
                   }
                   
                   //接收数据
                   $params =  $_POST;
                   
                   //返回数据
                   Respond::ok($params);
               }
```
* 2、编辑`application\views\api.php`
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome to CodeIgniter</title>
</head>
<body>
<div class="container">
    <h1>Welcome to auto api !</h1>
    <div id="body">
        <p>
            <?php
            echo $notice;
            ?>
        </p>
        <p>
            <?php
            /**
             * APi 模块 配置
             */
            $module = array(
                'user'   => '用户模块',
                'meeting'=> '会议模块',
            );
            $host = AUTO_TEST_API_HOST;
            array_map(function ($item) use ($host) {
                $url = $host . '/'.$item.'/api';
                echo '<a href="'.$url.'">'.$url.'</a>'.'<br><br>';
            }, array_keys($module));
            ?>
        </p>
    </div>
</div>
</body>
</html>
```
* 3、访问：http://www.lidi.ci.com/user/api
##### 输出类似下面内容，恭喜你成功啦！
```
Welcome to auto api !

You should know , this api is useful !

```
## Notice Help:

### 目前支持3种格式
* 1、普通参数传递：@AD (发送请求为POST)
```
@AD array('say'=>$data::bankAccountNumber(),'name'=>$data::name())
```
or
```
@AD array('say'=>'我爱你,','name'=>'伟大的祖国！')
```
* 2、GET参数传递：@GET (发送请求为GET)
```
@GET array('num'=>$data::bankAccountNumber(),'email'=>$data::email())
```

* 3、POST参数传递：@POST (发送请求为POST)
```
@POST array('name'=>$data::name(),'address'=>$data::address())
```

## 文档生成 ADD   Auto  ApiDoc to CI 
* 在 `system\core\Loader.php`文件
* 这行代码基础上：$_ci_CI->output->append_output(ob_get_contents()); 放入以下代码
```$php
        //执行测试模式
     	$strDoc = '';
     	if (AUTO_TEST_START) {
     		try{
     			$strDoc = Auto\Auto::::getStrDoc($this->uri);
     			} catch (\Exception $e) {
     				//记录执行错误Log
     				echo $e->getMessage();die;
     			}
     		}
     	$_ci_CI->output->append_output(ob_get_contents() . $strDoc);
```
访问：http://www.lidi.ci.com/user/api
##### 输出类似下面内容，恭喜你成功啦！
```
Welcome to auto api !
You should know , this api is useful !

http://www.lidi.ci.com/user/api

http://www.lidi.ci.com/meeting/api


/**

* TEST Demo

* @AD array('say'=>'我爱你,','name'=>'伟大的祖国！')

* User: fomo3d.wiki

* Email: fomo3d.wiki@gmail.com

* Date: 2020\/5\/4

*/
say:


name:



发送请求: user/test_ok

/**

* GET Demo

* @GET array('num'=>$data::bankAccountNumber(),'email'=>$data::email())

* User: fomo3d.wiki

* Email: fomo3d.wiki@gmail.com

* Date: 2020\/5\/4

*/
num:


email:



发送请求: user/get_ok

/**

* POST Demo

* @POST array('name'=>$data::name(),'address'=>$data::address())

* User: fomo3d.wiki

* Email: fomo3d.wiki@gmail.com

* Date: 2020\/5\/4

*/
name:


address:



发送请求: user/post_ok
```
*
*
* 待续3...ing



