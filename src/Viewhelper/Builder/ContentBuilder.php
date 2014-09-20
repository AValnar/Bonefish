<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 06.09.14
 * Time: 13:21
 */

namespace Bonefish\Viewhelper\Builder;


class ContentBuilder extends Builder
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
     * @var \Bonefish\Repositories\ContentElementRepository
     * @inject
     */
    public $contentRepository;

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
        $cache = $this->cache->load('content:' . $this->id);

        if ($cache !== NULL) {
            return $cache;
        }

        /** @var \Bonefish\Models\Navigation $navigation */
        $content = $this->contentRepository->getByID($this->id);

        if ($content === NULL) {
            throw new \InvalidArgumentException('Invalid Content Element');
        }

        $contentViewhelper = $content->getViewhelper();
        $html = $contentViewhelper->render();
        $this->cache->save('content:' . $this->id, $html);
        return $html;
    }
} 