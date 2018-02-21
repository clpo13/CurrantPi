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

        // match revisions to model names
        // https://elinux.org/RPi_HardwareHistory#Which_Pi_have_I_got.3F
        $model_arr = array(
          "Beta" => "B (Beta)",
          "0002" => "B",
          "0003" => "B (ECN0001)",
          "0004" => "B",
          "0005" => "B",
          "0006" => "B",
          "0007" => "A",
          "0008" => "A",
          "0009" => "A",
          "000d" => "B",
          "000e" => "B",
          "000f" => "B",
          "0010" => "B+",
          "0011" => "Compute Module 1",
          "0012" => "A+",
          "0013" => "B+",
          "0014" => "Compute Module 1",
          "0015" => "A+",
          "a01040" => "2 Model B",
          "a01041" => "2 Model B",
          "a21041" => "2 Model B",
          "a21042" => "2 Model B (with BCM2837)",
          "900021" => "A+",
          "900032" => "B+",
          "900092" => "Zero",
          "900093" => "Zero",
          "920092" => "Zero",
          "9000c1" => "Zero W",
          "a02082" => "3 Model B",
          "a020a0" => "Compute Module 3/CM3 Lite",
          "a22082" => "3 Model B",
          "a32082" => "3 Model B",
        );

				// find Rasbperry Pi revision and model
        $revision = trim(preg_replace('/\s\s+/', ' ', shell_exec("cat /proc/cpuinfo | grep 'Revision' | awk '{print $3}' | sed 's/^1000//'")));
        $model = $model_arr[$revision];

        //determine php version
        $php_version = explode('.', phpversion());

        // data object
        $data = new \stdClass();

        $data->webserver = $_SERVER['SERVER_NAME'].' ('.$_SERVER['SERVER_ADDR'].') - '.$_SERVER['SERVER_SOFTWARE'];
        $data->php_version = 'PHP/' . $php_version[0] . '.' . $php_version[1] . '.' . $php_version[1];
        $data->cpu = $name[1];
        $data->revision = $revision;
        $data->model = $model;

        return $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
