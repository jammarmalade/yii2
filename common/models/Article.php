<?php

namespace common\models;

use Yii;
use yii\data\Pagination;
use backend\models\Image as TableImage;
use backend\models\Tag;
use frontend\components\Functions as tools;
use common\models\ArticleTag;
use common\models\BaseModel;
/**
 * This is the model class for table "t_article".
 *
 * @property string $id
 * @property string $sid
 * @property string $uid
 * @property string $username
 * @property string $subject
 * @property string $description
 * @property string $content
 * @property string $like
 * @property string $view
 * @property string $comment
 * @property string $view_auth
 * @property string $image_id
 * @property string $copyright
 * @property integer $status
 * @property string $time_update
 * @property string $time_create
 */
class Article extends BaseModel
{
    //时期搜索（开始）
    public $time_create_from;
    //时期搜索（结束）
    public $time_create_to;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 't_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'like', 'view', 'comment', 'image_id', 'status'], 'integer'],
            [['content'], 'string'],
            [['time_update', 'time_create'], 'safe'],
            [['username'], 'string', 'max' => 15],
            [['subject'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sid' => '加密ID',
            'uid' => '用户id',
            'username' => '用户名',
            'subject' => '文章标题',
            'description' => '文章摘要',
            'content' => '文章内容',
            'like' => '点赞数',
            'view' => '查看数',
            'comment' => '评论数',
            'view_auth' => '查看密码',
            'image_id' => '内容第一张图片的id',
            'copyright' => '显示版权',
            'status' => '状态，1正常，2删除',
            'time_update' => '更新时间',
            'time_create' => '创建时间',
        ];
    }
    /**
     * 版权标记
     */
    public function copyrightArr(){
        return [
            0 => '不显示',
            1 => '显示',
        ];
    }
    /**
     * 根据tag 获取文章列表
     */
    public static function getArticleList($tagId = ''){
        $limit = Config::getConfig('articleListLimit');
        $limit = $limit ? $limit : 10;
        $defaultPageSize = $limit;
        //文章列表
        //若有tagid 则根据 ArticleTag表查询
        $field = 'a.id,a.uid,a.username,a.subject,a.description,a.view_auth,a.image_id,a.time_create,a.like,a.view,a.comment';
        if($tagId){
            $articleQuery = ArticleTag::find()->from(ArticleTag::tableName().' at')->innerJoin(['a' => Article::tableName()], "a.id = at.aid")->where(['at.tid' => $tagId,'a.status' => 1]);
        }else{
            $articleQuery = Article::find()->from(Article::tableName().' a')->where(['status' => 1]);
        }
        $count = $articleQuery->count();
        $pages = new Pagination(['totalCount' => $count, 'pageSize' => $limit,'defaultPageSize' => $defaultPageSize]);
        $articleList = $articleQuery->select($field)->orderBy('a.time_create DESC')->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        
        $imgList = $imageIds = $aids = $groupTagList = [];
        if (is_array($articleList)) {
            $imageIds = array_filter(array_column($articleList, 'image_id'));
            $aids = array_filter(array_column($articleList, 'id'));
        }
        if ($imageIds) {
            //查询文章图片
            $imgList = TableImage::find()->select('sid,path,thumb')->where(['in', 'id', $imageIds])->asArray()->indexBy('sid')->all();
        }
        //标签
        if ($aids) {
            $tagList = ArticleTag::find()->from(ArticleTag::tableName() . ' as at')
                            ->join('LEFT JOIN', Tag::tableName() . ' as t', 't.id = at.tid')
                            ->where(['in', 'at.aid', $aids])->select('at.id,at.tid,at.aid,t.name as tagname')->asArray()->all();
            //按照aid 分组
            foreach ($tagList as $k => $v) {
                $groupTagList[$v['aid']][] = $v;
            }
        }
        
        if (is_array($articleList)) {
            $defaultArticlItemImg = Yii::$app->view->theme->baseUrl.'/images/articl-item.jpg';
            foreach ($articleList as $k => $v) {
                $v['date'] = substr($v['time_create'], 0, 16);
                $v['faceUrl'] = $defaultArticlItemImg;
                $v['description'] = tools::textarea2br($v['description']);
                if (isset($imgList[$v['id']])) {
                    $tmpImage = $imgList[$v['id']];
                    $v['faceUrl'] = Yii::$app->params['imgDomain'] . $tmpImage['path'];
                    if ($tmpImage['thumb']) {
                        $v['faceUrl'] .= '.thumb.jpg';
                    }
                }
                $v['tagList'] = [];
                if (isset($groupTagList[$v['id']])) {
                    $v['tagList'] = $groupTagList[$v['id']];
                }
                $articleList[$k] = $v;
            }
        }
        $cacheData['articleList'] = $articleList;
        $cacheData['pages'] = $pages;
        return $cacheData;
    }
    
}
