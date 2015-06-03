<?php
/**
 * Created by PhpStorm.
 * User: E8400
 * Date: 25.04.2015
 * Time: 18:35
 */

namespace Bonefish\Controller;

use Bonefish\View\IView;

class ViewController extends Base
{
    /**
     * @var IView
     * @Bonefish\Inject
     */
    public $view;

    /**
     * @param string $action
     * @return bool
     */
    public function beforeExecute($action)
    {
        $action = str_replace('Action', '', $action);
        $this->view->setLayout(get_class($this) . '/' . ucfirst($action) . '.latte');
        return TRUE;
    }

    public function afterExecute()
    {
        $this->view->render();
    }
}