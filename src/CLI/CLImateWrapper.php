<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 16.03.2015
 * Time: 21:03
 */

namespace Bonefish\CLI;


use League\CLImate\CLImate;

class CLImateWrapper
{
    /**
     * @var CLImate
     */
    protected $climate;

    public function __init()
    {
        $this->climate = new CLImate();
    }

    /**
     * Display a line break
     * @return mixed
     */
    public function br()
    {
        return $this->climate->br();
    }

    /**
     * Display a table
     * @param array $data
     * @return mixed
     */
    public function table(array $data = array())
    {
        return $this->climate->table($data);
    }

    /**
     * Print a line of text on the command line
     * @param string $text
     * @return mixed
     */
    public function out($text = '')
    {
        return $this->climate->out($text);
    }

    /**
     * Print a red line of text on the command line
     * @param string $text
     * @return mixed
     */
    public function red($text = '')
    {
        return $this->climate->red($text);
    }
} 