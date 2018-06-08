<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use cmf\controller\HomeBaseController;
use app\portal\service\PostService;
use app\portal\model\PortalCategoryModel;
use think\Db;
use think\Request;

class IndexController extends HomeBaseController {

    public function index(){
        $cid = input('cid', 1, 'intval');
        $page= input('page', 1, 'intval');
        $pageSize = 10;

        $categories = Db::name('portal_category')->order("list_order ASC")->field('id,name')->where(['delete_time' => 0, 'id' => array('neq', 1)])->select()->toArray();

        $join  = [
            ['portal_category_post relation', 'post.id = relation.post_id']
        ];
        $limit = ($page - 1) * $pageSize . ',' . $pageSize;
        $where = ['post_status' => 1, 'status' => 1, 'relation.category_id' => $cid];
        $field = 'post.id,post.comment_count,post.post_title,post.more,relation.category_id';
        $order = 'is_top desc,recommended desc,published_time desc';
        $articles = Db::name('portal_post')->alias('post')->where($where)->field($field)->join($join)->limit($limit)->order($order)->select()->toArray();
        if($articles){
            foreach ($articles as $key => &$val){
                $val['more'] = $this->object_array(json_decode($val['more']));
            }
        }

        if(Request::instance()->isAjax()){
            return json(['data' => $articles, 'switchs' => count($articles) < $pageSize ? 1 : 0]);
        }else{
            $this->assign('cid', $cid);
            $this->assign('count', count($articles));
            $this->assign('article', $articles);
            $this->assign('categories', $categories);
            $this->assign('pageSize', $pageSize);
            return $this->fetch(':index');
        }
    }

    private function object_array($array) {
        if(is_object($array)) {
            $array = (array)$array;
        } if(is_array($array)) {
            foreach($array as $key=>$value) {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }

    public function detail(){
        $portalCategoryModel = new PortalCategoryModel();
        $postService         = new PostService();

        $articleId  = $this->request->param('id', 0, 'intval');
        $categoryId = $this->request->param('cid', 0, 'intval');
        $article    = $postService->publishedArticle($articleId, $categoryId);

        if (empty($articleId)) {
            abort(404, '文章不存在!');
        }

        $prevArticle = $postService->publishedPrevArticle($articleId, $categoryId);
        $nextArticle = $postService->publishedNextArticle($articleId, $categoryId);

        if (!empty($categoryId)) {
            $category = $portalCategoryModel->where('id', $categoryId)->where('status', 1)->find();
            if (empty($category)) {
                abort(404, '文章不存在!');
            }

            $this->assign('category', $category);
        }

        Db::name('portal_post')->where(['id' => $articleId])->setInc('post_hits');

        $this->assign('article', $article);
        $this->assign('prev_article', $prevArticle);
        $this->assign('next_article', $nextArticle);

        return $this->fetch(':detail');
    }
	
	public function zepo(){
		$categories = Db::name('portal_category')->order("list_order ASC")->field('id,name')->where(['delete_time' => 0, 'id' => array('neq', 1)])->select()->toArray();
		$this->assign('categories', $categories);
		return $this->fetch(':zepo');
	}
}
