<?php

class Sheep_Debug_Model_Db_Profiler extends Zend_Db_Profiler
{
    protected $stackTraces = array();

    /**
     * @param $queryId
     * @return string
     * @throws Zend_Db_Profiler_Exception
     */
    public function parentQueryEnd($queryId)
    {
        return parent::queryEnd($queryId);
    }


    /**
     * @return string
     */
    public function getStackTrace()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        return array_slice($trace, 2);
    }

    public function queryEnd($queryId)
    {
        $result = $this->parentQueryEnd($queryId);

        $this->stackTraces[$queryId] = $this->getStackTrace();

        return $result;
    }


    /**
     * @return array
     */
    public function getAllQueriesWithStackTrace()
    {
        $queries  = array();
        foreach ($this->_queryProfiles as $queryId => $queryProfile) {
            $queryModel = Mage::getModel('sheep_debug/query');
            $stacktrace = array_key_exists($queryId, $this->stackTraces) ? $this->stackTraces[$queryId] : '';
            $queryModel->init($queryProfile, $stacktrace);

            $queries[] = $queryModel;
        }

        return $queries;
    }

}
