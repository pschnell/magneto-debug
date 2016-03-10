<?php

class Sheep_Debug_Model_Db_Profiler extends Zend_Db_Profiler
{
    protected $stackTrace;

    public function parentQueryEnd($queryId)
    {
        return parent::queryEnd($queryId);
    }


    public function getStackTrace()
    {
        return debug_backtrace();

    }

    public function queryEnd($queryId)
    {
        $result = $this->parentQueryEnd($queryId);

        $this->stackTrace = $this->getStackTrace();

        return $result;
    }

}
