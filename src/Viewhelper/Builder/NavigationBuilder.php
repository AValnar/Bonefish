<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 06.09.14
 * Time: 13:21
 */

namespace Bonefish\Viewhelper\Builder;


use Bonefish\Viewhelper\Navigation\Navigation;
use Bonefish\Viewhelper\Navigation\NavigationElement;
use Bonefish\Viewhelper\Navigation\NavigationSubmenu;

class NavigationBuilder extends Builder
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var \Nette\Caching\Cache
     * @inject
     */
    public $cache;

    /**
     * @var \Bonefish\Repositories\NavigationRepository
     * @inject
     */
    public $navigationRepository;

    /**
     * @param int $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function build()
    {
        $cache = $this->cache->load('navigation:' . $this->id);

        if ($cache !== NULL) {
            return $cache;
        }

        /** @var \Bonefish\Models\Navigation $navigation */
        $navigation = $this->navigationRepository->getByID($this->id);

        if ($navigation === NULL) {
            throw new \InvalidArgumentException('Invalid Navigation');
        }

        $navigationViewhelper = $navigation->getViewhelper();
        $elements = $navigation->getElements();
        /** @var \Bonefish\Models\NavigationElement $element */
        foreach ($elements as $element) {
            $submenus = $element->getSubmenus();
            $elementViewhelper = $element->getViewhelper();
            /** @var \Bonefish\Models\NavigationSubmenu $submenu */
            foreach ($submenus as $submenu) {
                $submenuViewhelper = $submenu->getViewhelper();
                $elementViewhelper->addChild($submenuViewhelper);
            }
            $navigationViewhelper->addChild($elementViewhelper);
        }
        $html = $navigationViewhelper->render();
        $this->cache->save('navigation:' . $this->id, $html);
        return $html;
    }
} 