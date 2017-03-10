<?php

/**
 * Bricks Framework & Bricks CMS
 * http://bricks-cms.org
 *
 * The MIT License (MIT)
 * Copyright (c) 2015 bricks-cms.org
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Bricks\Backup;

use Bricks\Config\ConfigAwareInterface;
use Bricks\Config\ConfigInterface;
use Bricks\Loader\LoaderAwareInterface;
use Bricks\Loader\LoaderInterface;
use Zend\Db\Adapter\AdapterInterface;

class Backup implements ConfigAwareInterface,LoaderAwareInterface {

    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * @param ConfigInterface $config
     */
    public function setConfig(ConfigInterface $config){
        $this->config = $config;
    }

    /**
     * @return ConfigInterface
     */
    public function getConfig(){
        return $this->config;
    }

    /**
     * @param LoaderInterface $loader
     */
    public function setLoader(LoaderInterface $loader){
        $this->loader = $loader;
    }

    /**
     * @return LoaderInterface
     */
    public function getLoader(){
        return $this->loader;
    }

    /**
     * Parameters example
     *
     * File Backup:
     * array(
     *   'path' => 'data',
     *   'offset' => unset (optional)
     *   'limit' => unset (optional)
     * );
     *
     * Sql Backup:
     * array(
     *   'adapter' => AdapterInterface,
     *   'table' => unset (optional)
     *   'offset' => unset (optional)
     *   'limit' => unset (optional)
     * )
     *
     * @param mixed $params
     * @param string $type
     */
    public function backup($params,$type=null){
        if(null == $type && !is_array($params)){
            if($params instanceof AdapterInterface){
                $params = array('adapter' => $params);
            } elseif(is_string($params)){
                $params = array('path' => $params);
            }
        }

        if(null == $type && isset($params['path'])){
            if(!file_exists($params['path'])){
                throw $this->getLoader()->get('Bricks\Backup\Exception\InvalidSourceFile',array('Backup source file '.$params['path'].' could not be found',array('path' => $params['path'])));
            }
            $strategy = $this->getLoader()->get('Bricks\Backup\BackupStrategy\File');
        }
        if(null == $type && isset($params['adapter'])){
            $strategy = $this->getLoader()->get('Bricks\Backup\BackupStrategy\Sql');
        }
        if(null !== $type){
            $strategy = $this->getLoader()->get($type);
        }
        return array_replace($params,$strategy->backup($params));
    }

}