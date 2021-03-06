<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers;

use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\Article\Models\Article as ArticleModel;
use Modules\Article\Mappers\Category as CategoryMapper;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Comment\Models\Comment as CommentModel;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function init()
    {
        $locale = '';

        if ((bool)$this->getConfig()->get('multilingual_acp')) {
            if ($this->getTranslator()->getLocale() != $this->getConfig()->get('content_language')) {
                $locale = $this->getTranslator()->getLocale();
            }
        }

        $this->locale = $locale;
    }

    public function indexAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->header()
            ->css('static/css/article.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuArticle'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index']);

        $pagination->setRowsPerPage(!$this->getConfig()->get('article_articlesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('article_articlesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('categoryMapper', $categoryMapper)
            ->set('commentMapper', $commentMapper)
            ->set('userMapper', $userMapper)
            ->set('articles', $articleMapper->getArticles($this->locale, $pagination))
            ->set('pagination', $pagination);
    }

    public function showAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();
        $userMapper = new UserMapper();
        $config = \Ilch\Registry::get('config');

        if ($this->getRequest()->getPost('saveComment')) {
            $date = new \Ilch\Date();
            $commentModel = new CommentModel();
            if ($this->getRequest()->getPost('fkId')) {
                $commentModel->setKey('article/index/show/id/'.$this->getRequest()->getParam('id').'/id_c/'.$this->getRequest()->getPost('fkId'));
                $commentModel->setFKId($this->getRequest()->getPost('fkId'));
            } else {
                $commentModel->setKey('article/index/show/id/'.$this->getRequest()->getParam('id'));                
            }
            $commentModel->setText($this->getRequest()->getPost('comment_text'));
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
            $this->redirect(['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);
        }

        if ($this->getRequest()->getParam('commentId') AND ($this->getRequest()->getParam('key') == 'up' OR $this->getRequest()->getParam('key') == 'down')) {
            $id = $this->getRequest()->getParam('id');
            $commentId = $this->getRequest()->getParam('commentId');
            $oldComment = $commentMapper->getCommentById($commentId);

            $commentModel = new CommentModel();
            $commentModel->setId($commentId);
            if ($this->getRequest()->getParam('key') == 'up') {
                $commentModel->setUp($oldComment->getUp()+1);
            } else {
                $commentModel->setDown($oldComment->getDown()+1);
            }
            $commentModel->setVoted($oldComment->getVoted().$this->getUser()->getId().',');
            $commentMapper->saveLike($commentModel);

            $this->redirect(['action' => 'show', 'id' => $id.'#comment_'.$commentId]);
        }

        if ($this->getRequest()->isPost() & $this->getRequest()->getParam('preview') == 'true') {
            $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('menuArticle'))
                ->add($this->getTranslator()->trans('preview'));
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('preview'), ['action' => 'index']);

            $catIds = implode(",", $this->getRequest()->getPost('cats'));

            $articleModel = new ArticleModel();
            $articleModel->setTitle($this->getRequest()->getPost('title'))
                ->setTeaser($this->getRequest()->getPost('teaser'))
                ->setCatId($catIds)
                ->setKeywords($this->getRequest()->getPost('keywords'))
                ->setContent($this->getRequest()->getPost('content'))
                ->setImage($this->getRequest()->getPost('image'))
                ->setImageSource($this->getRequest()->getPost('imageSource'))
                ->setVisits(0);

            $this->getView()->set('categoryMapper', $categoryMapper)
                ->set('commentMapper', $commentMapper)
                ->set('article', $articleModel);
        } else {
            $article = $articleMapper->getArticleByIdLocale($this->getRequest()->getParam('id'));
            $catIds = explode(",", $article->getCatId());

            $this->getLayout()->header()
                ->css('static/css/article.css')
                ->css('../comment/static/css/comment.css');
            $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('menuArticle'))
                ->add($this->getTranslator()->trans('menuCats'));
            foreach ($catIds as $catId) {
                $articlesCats = $categoryMapper->getCategoryById($catId);
                $this->getLayout()->getTitle()->add($articlesCats->getName());
            }
            $this->getLayout()->getTitle()->add($article->getTitle());
            $this->getLayout()->set('metaDescription', $article->getDescription());
            $this->getLayout()->set('metaKeywords', $article->getKeywords());
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuCats'), ['controller' => 'cats', 'action' => 'index']);
            foreach ($catIds as $catId) {
                $articlesCats = $categoryMapper->getCategoryById($catId);
                $this->getLayout()->getHmenu()->add($articlesCats->getName(), ['controller' => 'cats', 'action' => 'show', 'id' => $catId]);
            }
             $this->getLayout()->getHmenu()->add($article->getTitle(), ['action' => 'show', 'id' => $article->getId()]);

            $articleModel = new ArticleModel();
            $articleModel->setId($article->getId())
                ->setVisits($article->getVisits() + 1);
            $articleMapper->saveVisits($articleModel);

            $this->getView()->set('categoryMapper', $categoryMapper)
                ->set('commentMapper', $commentMapper)
                ->set('userMapper', $userMapper)
                ->set('config', $config)
                ->set('article', $article)
                ->set('comments', $commentMapper->getCommentsByKey('article/index/show/id/'.$this->getRequest()->getParam('id')));
        }
    }
}
