<?php
/**
 *
 * Class Home
 */

class Help extends Groupon_Base
{
    public function groupon()
    {
        $this->render(array(), 'help/rule_groupon');
    }
}