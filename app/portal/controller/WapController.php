<?php
namespace app\portal\controller;
use cmf\controller\HomeBaseController;
use think\Db;
use app\portal\service\PostService;
use app\portal\model\PortalCategoryModel;
use think\Request;
/**
 *
 * Author: lirong
 * Date: 2017/8/15
 * Time: 9:50
 */
 class WapController extends HomeBaseController{

     public function index(){
        $cate =Db::name('portal_category')->field('id,name')->where(['delete_time'=>0,'status'=>1])->cache('index_categroy',600)->order('list_order','asc')->select();
        $map['post_status'] = 1;
        $map['post_type']   = 1;
        $map['p.delete_time'] = 0;
        $list  = Db::name('portal_post')->alias('p')->join('portal_category_post cp','p.id=cp.post_id','left')->where($map)->page(1, 8)->group('id')->field('p.id,post_title,published_time,more')->order(['recommended' => 'desc', 'is_top' => 'desc', 'published_time' => 'desc'])->select()->toArray();

        foreach ($list as $key => &$item){
            $more =json_decode($item['more'],true) ;
            $item['image'] ='/upload/'.$more['thumbnail'];
            $item['post_title'] = mb_strlen($item['post_title'],'utf-8') > 28 ? mb_substr($item['post_title'], 0 , 28, 'utf-8') . "..." : $item['post_title'];
            $item['published_time'] = date('Y-m-d',$item['published_time']);
            unset($item['more']);
        }

        $this->assign('page', 2);
        $this->assign('cate', $cate);
        $this->assign('list', $list);
        return $this->fetch();
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
         $prevArticle = $prevArticle ? $prevArticle->toArray() : '';
         $nextArticle = $nextArticle ? $nextArticle->toArray() : '';

         $prevArticle['post_title'] = $prevArticle ? (mb_strlen($prevArticle['post_title'],'utf-8') > 28 ? mb_substr($prevArticle['post_title'], 0 , 28, 'utf-8') . "..." : $prevArticle['post_title']) : array();
         $nextArticle['post_title'] = $nextArticle ? (mb_strlen($nextArticle['post_title'],'utf-8') > 28 ? mb_substr($nextArticle['post_title'], 0 , 28, 'utf-8') . "..." : $nextArticle['post_title']) : array();;

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

         return $this->fetch();
     }

     public function ajaxlist(){
        $cid =$this->request->param('cid',0,'intval');
        $page =$this->request->param('page', 1, 'intval');
        $group ="";
        if (!empty($cid)){
            $map['category_id'] =$cid;
        }else{
            $group = "id";
        }
        $map['post_status'] = 1;
        $map['post_type']   = 1;
        $map['p.delete_time'] = 0;
        $listRow=8;
        $list = Db::name('portal_post')->alias('p')->join('portal_category_post cp','p.id=cp.post_id','left')->where($map)->page($page,$listRow)->group($group)->field('p.id,post_title,published_time,more')->order(['is_top'=>'desc','recommended'=>'desc','published_time'=>'desc'])->select()->toArray();
        $count =Db::name('portal_post')->alias('p')->join('portal_category_post cp','p.id=cp.post_id','left')->where($map)->field('p.id,post_title,published_time,more')->count();

        $end =ceil($count/$listRow) > $page ? false : true;

        if (empty($list)){
            return json(['list'=>[],'code'=>300,'page'=>$page+0,'switchs'=>$end,'count'=>$count]);
        }

        foreach ($list as $key => &$item){
            $more =json_decode($item['more'],true) ;
            $item['image'] ='/upload/'.$more['thumbnail'];
            $item['post_title'] =mb_strlen($item['post_title'],'utf-8')>28?mb_substr($item['post_title'],0,28,'utf-8')."...":$item['post_title'];

            $item['published_time']=date('Y-m-d',$item['published_time']);
            unset($item['more']);
        }

        return json(['list'=>$list,'code'=>200,'page'=>$page+1,'switchs'=>$end,'count'=>$count]);
    }

    public function share(){
        $postService         = new PostService();

        $articleId  = $this->request->param('id', 0, 'intval');
        $categoryId = $this->request->param('cid', 0, 'intval');
        $article    = $postService->publishedArticle($articleId, $categoryId);
        $this->assign('article', $article);
        return $this->fetch();
    }
}

