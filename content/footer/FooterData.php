<?php
namespace CurrantPi;

class FooterData implements CurrantModule
{
    private $data;

    public function __construct()
    {
        $this->data = $this->prepareData();
    }

    private function prepareData()
    {
        /*
         * The array $_SERVER[] contains a bunch of server and execution
         * environment information.
         *
         * Reading /proc/cpuinfo to find out the type of processor this
         * is running on.
         */

        // preparing cpu info
        $name_full = trim(preg_replace('/\s\s+/', ' ', shell_exec('cat /proc/cpuinfo | grep name | head -1')));
        $name = explode(': ', $name_full);

				// find Rasbperry Pi revision and model
        $revision = trim(preg_replace('/\s\s+/', ' ', shell_exec("cat /proc/cpuinfo | grep 'Revision' | awk '{print $3}' | sed 's/^1000//'")));

        //determine php version
        $php_version = explode('.', phpversion());

        // data object
        $data = new \stdClass();

        $data->webserver = $_SERVER['SERVER_NAME'].' ('.$_SERVER['SERVER_ADDR'].') - '.$_SERVER['SERVER_SOFTWARE'];
        $data->php_version = 'PHP/' . $php_version[0] . '.' . $php_version[1] . '.' . $php_version[1];
        $data->cpu = $name[1];
        $data->revision = $revision;

        return $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
