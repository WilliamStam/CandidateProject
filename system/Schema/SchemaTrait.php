<?php
namespace System\Schema;

use System\Exceptions\Schemas\NoSchemaPassed;

trait SchemaTrait {

    function toSchema($schema = null) {

        if (is_string($schema) && class_exists($schema)) {
            $schema = new $schema();
        }
        $args_passed = func_get_args();
        array_shift($args_passed);

        if ($schema instanceof SchemaInterface) {
            $args = (array)$schema->args();
            foreach ($args_passed as $k => $v) {
                $args[$k] = $v;
            }
            return call_user_func_array(array($schema->load($this), "__invoke"), $args);
        }
        if (is_callable($schema)) {
            $args = $args_passed;
            array_unshift($args, $this);
            return call_user_func_array($schema, $args);
        }
        throw new NoSchemaPassed("No schema found. toSchema needs a valid schema, either a schema::class or schema object or a callable");
    }
}