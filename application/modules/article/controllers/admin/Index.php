<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers\Admin;

use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\Article\Models\Article as ArticleModel;
use Modules\Article\Mappers\Category as CategoryMapper;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuCats',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getControllerName() == 'index' AND $this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuArticle',
            $items
        );
    }

    public function indexAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();
        $commentMapper = new CommentMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_articles')) {
            foreach ($this->getRequest()->getPost('check_articles') as $articleId) {
                $articleMapper->delete($articleId);
                $commentMapper->deleteByKey('article/index/show/id/'.$articleId);
            }
        }

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('articleMapper', $articleMapper);
        $this->getView()->set('articles', $articleMapper->getArticles('', $pagination));
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $this->getView()->set('pagination', $pagination);
    }

    public function treatAction()
    {
        $articleMapper = new ArticleMapper();
        $categoryMapper = new CategoryMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            if ($this->getRequest()->getParam('locale') == '') {
                $locale = '';
            } else {
                $locale = $this->getRequest()->getParam('locale');
            }

            $this->getView()->set('article', $articleMapper->getArticleByIdLocale($this->getRequest()->getParam('id'), $locale));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'cats' => 'required',
                'title' => 'required',
                'content' => 'required',
            ]);

            if ($validation->isValid()) {
                $catIds = implode(",", $this->getRequest()->getPost('cats'));
                $model = new ArticleModel();
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }
                if ($this->getRequest()->getPost('language') != '') {
                    $model->setLocale($this->getRequest()->getPost('language'));
                } else {
                    $model->setLocale('');
                }

                $model->setCatId($catIds)
                    ->setAuthorId($this->getUser()->getId())
                    ->setDescription($this->getRequest()->getPost('description'))
                    ->setKeywords($this->getRequest()->getPost('keywords'))
                    ->setTitle($this->getRequest()->getPost('title'))
                    ->setTeaser($this->getRequest()->getPost('teaser'))
                    ->setContent($this->getRequest()->getPost('content'))
                    ->setPerma($this->getRequest()->getPost('permaLink'))
                    ->setImage($this->getRequest()->getPost('image'))
                    ->setImageSource($this->getRequest()->getPost('imageSource'));
                $articleMapper->save($model);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat']);
        }

        $this->getView()->set('cats', $categoryMapper->getCategories());
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $articleMapper = new ArticleMapper();
            $commentMapper = new CommentMapper();
            $articleMapper->delete($this->getRequest()->getParam('id'));
            $commentMapper->deleteByKey('article/index/show/id/'.$this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }
    }
}
