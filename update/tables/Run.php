<?php

namespace update\tables;

use \update\AbstractBase;
use \update\RunInterface;


class Run extends AbstractBase implements RunInterface {

    static function cmd(){
        return "tables";
    }

    function run() {
        if (!$this->is_dev()){
            echo "Cant be run in production mode";
            return false;
        }

        $this->label("Generate Table Eloquent Files", 1);

        $timer = (double)microtime(TRUE);
        $tables = array();
        foreach ($this->db()->select("SHOW TABLES;") as $table_row) {
            $table =array_values($table_row)[0];
            if (!in_array($table, array())) {
                $tables[] = $table;
            }
        };
        $path = implode(DIRECTORY_SEPARATOR,array(realpath(dirname(__DIR__,2)),"app","Tables"));


        $casts = include("Casts.php");
        $extra = include("Extra.php");
        $rules = include("Rules.php");

        $remove_files = array();
        $iterator = new \FilesystemIterator($path);

        foreach($iterator as $entry) {
            $file = $entry->getFilename();
            if (!in_array($file,array("AbstractTable.php")) ){
                $remove_files[] =$file;
            }

        }

        foreach ($tables as $table) {
            if (substr($table,0,5)=="view_"){
                $table = str_replace("view_", "VIEW_", $table);
                $filename = str_replace("_", "", ucwords( substr($table,5), " \t\r\n\f\v_'")) . "View";
            } else {
                $filename = str_replace("_", "", ucwords($table, " \t\r\n\f\v_'")) . "Table";
            }

            $content = $this->render($table, $filename,
                rules: $this->return_array($table,$rules),
                casts: $this->return_array($table,$casts),
                extra: $this->return_array($table,$extra),
            );

            $file_path = $path  . DIRECTORY_SEPARATOR . $filename . ".php";

            if (($key = array_search($filename.".php", $remove_files)) !== false) {
                unset($remove_files[$key]);
            }



            $this->label($file_path,2);
            $table_file = fopen($file_path, "w") or die("Unable to open file!");
            fwrite($table_file, $content);
            fclose($table_file);

        }

        if (count($remove_files)){
            $this->label("Removing unused eloquent files", 1);
            foreach ($remove_files as $file){

                $file_path = $path  . DIRECTORY_SEPARATOR . $file;
                $this->label($file_path,2);
                unlink($file_path);
            }
        }



        $ended = (double)microtime(TRUE);
        $this->label("Finished: " . ($ended - $timer));

    }
    protected function return_array($table,$items){
        if (isset($items[$table])){
            return $items[$table];
        } else {
            return array();
        }


    }

    public function render($table, $filename, $rules=array(),$casts=array(),$extra=array()) {


        $fillable = array();

        $columns_records = $this->db()->select("SHOW COLUMNS FROM {$table}");
        $columns = array();
        foreach ($columns_records as $column) {
            if (!in_array($column, array("id"))) {
                $fillable[] = $column['Field'];
            }
            $columns[] = $column['Field'];

            if (!isset($rules[$column['Field']])){
                $rules[$column['Field']] = array();
            }
            if ($this->startsWith($column['Type'], "varchar")) {
                $max_length = substr($column['Type'], 8, strlen($column['Type']) - 9);
                $rules[$column['Field']][] = "[Rules\MaxLength::class,{$max_length}]";
            }

        }


        $fillable = json_encode($fillable);

        $created_at = (!in_array("created_at", $columns)) ? "const CREATED_AT = null;" : "";
        $updated_at = (!in_array("updated_at", $columns)) ? "const UPDATED_AT = null;" : "";




        $extra_string = implode("\n    ",$extra);





        $casts_string = array();
        foreach ($casts AS $column=>$type){
            $casts_string[] = "        '{$column}' => '{$type}'";
        }

        $casts_string = implode(",\n",$casts_string);

        $rules_string = array();
        foreach ($rules AS $column=>$rules){

            $r = implode(",".PHP_EOL."            ",$rules);

            $rules_string[] = "        '{$column}' => array(\n            {$r}\n        )";


        }

        $rules_string = implode(",\n",$rules_string);



        return <<<TEXT
<?php
namespace App\Tables;
use System\Validation\Rules;

class {$filename} extends AbstractTable {
    protected \$table = '{$table}';
    $created_at
    $updated_at
    $extra_string
    public \$fillable = $fillable;
    protected \$rules = array(
$rules_string
    );
    protected \$casts = array(
$casts_string
    );
}
TEXT;

    }

}

