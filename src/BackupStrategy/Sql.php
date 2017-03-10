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

namespace Bricks\Backup\BackupStrategy;

use Zend\Db\Adapter\AdapterInterface;

class Sql {

    /**
     * @return int
     */
    public function getMemoryLimit(){
        $memoryLimit = ini_get('memory_limit');
        $val = trim($memoryLimit);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * @return int
     */
    public function getMaxExecutionTime(){
        return ini_get('max_execution_time')?:30;
    }

    /**
     * @param array
     */
    public function getTableList(AdapterInterface $adapter){
    }

    /**
     * @param $params
     */
    public function backup($params){
        $adapter = $params['adapter'];
        $table = isset($params['table'])?$params['table']:null;
        $offset = isset($params['offset'])?$params['offset']:0;
        $limit = isset($params['limit'])?$params['limit']:0;

        $tableList = $this->getTableList($params['adapter']);

        if(null == $table){
            $table = current($tableList);
        }

    }

}