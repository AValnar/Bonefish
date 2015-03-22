<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 16.03.2015
 * Time: 22:09
 */

namespace Bonefish\CLI\Raptor\Validator;


use Bonefish\AbstractTraits\Parameters;
use Bonefish\Interfaces\Validator;

class CLIPackageArguments implements Validator
{
    use Parameters;

    /**
     * @var \Bonefish\Core\PackageManager
     * @inject
     */
    public $packageManager;

    /**
     * @var bool
     */
    protected $vendorRequired = TRUE;

    /**
     * @var bool
     */
    protected $packageRequired = TRUE;

    /**
     * @return boolean
     */
    public function isPackageRequired()
    {
        return $this->packageRequired;
    }

    /**
     * @param boolean $packageRequired
     * @return self
     */
    public function setPackageRequired($packageRequired)
    {
        $this->packageRequired = $packageRequired;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isVendorRequired()
    {
        return $this->vendorRequired;
    }

    /**
     * @param boolean $vendorRequired
     * @return self
     */
    public function setVendorRequired($vendorRequired)
    {
        $this->vendorRequired = $vendorRequired;
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (!$this->isValidVendor()) {
            return false;
        }

        return $this->isValidPackage();
    }

    /**
     * @return bool
     */
    protected function isValidPackage()
    {
        if (!isset($this->arguments[2])) {
            return !$this->isPackageRequired();
        }

        return $this->packageManager->isPackageInstalledByVendor($this->arguments[1], $this->arguments[2]);
    }

    /**
     * @return bool
     */
    protected function isValidVendor()
    {
        if (!isset($this->arguments[1])) {
            return !$this->isVendorRequired();
        }

        return $this->packageManager->isPackageInstalledByVendor($this->arguments[1]);
    }
} 