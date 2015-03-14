<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 14.03.2015
 * Time: 09:14
 */

namespace Bonefish\Core\Mode;


class ACLMode extends DatabaseMode
{
    const MODE = 'ACLMode';

    /**
     * @var \Bonefish\ACL\ACL
     * @inject
     */
    public $acl;

    /**
     * Init needed framework stack
     */
    public function init()
    {
        parent::init();

        if ($this->isModeStarted(self::MODE)) return;

        /** @var \Bonefish\Auth\IAuth $authService */
        $authService = $this->container->get('\Bonefish\Auth\IAuth');
        if ($authService->authenticate()) {
            $profile = $authService->getProfile();
        } else {
            $profile = $this->container->create('\Bonefish\ACL\Profiles\PublicProfile');
        }
        $this->acl->setProfile($profile);

        $this->setModeStarted(self::MODE);
    }
} 