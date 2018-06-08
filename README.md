ThinkCMF 5.0（大唐之声）

### ThinkCMF5主要特性
* 基于全新 ThinkPHP5.0开发
* 更规范的代码,遵循PSR-2命名规范和PSR-4自动加载规范
* 更规范的数据库设计
* 前后台完全基于bootstrap3
* 增加 api 模块（需单独下载）
* 支持 composer 管理第三方库
* 核心化：独立核心代码包
* 应用化：开发者以应用的形式增加项目模模块
* 插件化：更强的插件机制，开发者以插件形式扩展功能
* 模板化：模板完全傻瓜式，用户无须改动任何代码即可在后台完成模板设计和配置
* 增加 URL美化功能，支持别名设置，更简单
* 独立的回收站功能，可以管理所有应用临时删除的数据
* 统一的资源管理，相同文件只保存一份
* 注解式的后台菜单管理功能，方便开发者代码管理后台菜单
* 文件存储插件化，默认支持七牛文件存储插件
* 模板制作标签化，内置多个cmf标签，方便小白用户
* 更人性化的导航标签，可以随意定制 html 结构
* 后台首页插件化，用户可以定制的网站后台首页

### 环境推荐
> php5.5+

> mysql 5.6+

> 打开rewrite


### 最低环境要求
> php5.4+

> mysql 5.5+ (mysql5.1安装时选择utf8编码，不支持表情符)

> 打开rewrite

### 演示站点
http://demo5.thinkcmf.com/admin/   
用户名/密码:demo/thinkcmf

### 自动安装
> 之前安装过 cmf5的同学,请手动创建`data/install.lock`文件

代码已经加入自动安装程序,如果你在安装中有任何问题请提交 issue!

1. public目录做为网站根目录,入口文件在 public/index.php
2. 配置好网站，请访问http://你的域名

enjoy your cmf~!

### 系统更新
如果您是已经安装过 cmf5的用户,请查看 update 目录下的 sql 升级文件,根据自己的下载的程序版本进行更新

### API开发 (支持app,小程序,web)
如果你需要 `api` 开发请下载:  
ThinkCMF5 API :https://github.com/thinkcmf/thinkcmfapi

### 完整版目录结构
```
thinkcmf  根目录
├─api                   api目录(核心版不带)
├─app                   应用目录
│  ├─portal             门户应用目录
│  │  ├─config.php      应用配置文件
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  └─ ...            更多类库目录
│  ├─ ...               更多应用
│  ├─command.php        命令行工具配置文件
│  ├─common.php         应用公共(函数)文件
│  ├─config.php         应用(公共)配置文件
│  ├─database.php       数据库配置文件
│  ├─tags.php           应用行为扩展定义文件
│  └─route.php          路由配置文件
├─data                  数据目录
│  ├─conf               动态配置目录
│  ├─runtime            应用的运行时目录（可写）
│  └─ ...               更多
├─public                WEB 部署目录（对外访问目录）
│  ├─api                api入口目录(核心版不带)
│  ├─plugins            插件目录
│  ├─static             静态资源存放目录(css,js,image)
│  ├─themes             前后台主题目录
│  │  ├─admin_simpleboot3  后台默认主题
│  │  └─simpleboot3            前台默认主题
│  ├─upload             文件上传目录
│  ├─index.php          入口文件
│  ├─robots.txt         爬虫协议文件
│  ├─router.php         快速测试文件
│  └─.htaccess          apache重写文件
├─simplewind         
│  ├─cmf                CMF核心库目录
│  ├─extend             扩展类库目录
│  ├─thinkphp           thinkphp目录
│  └─vendor             第三方类库目录（Composer）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
