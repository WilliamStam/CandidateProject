<?php

namespace System\Utilities;
class Strings {
    // replace all the slashes with DIRECTORY_SEPARATOR and remove extra from the path if there are
    static function fixDirSlashes(string $path, $slashes = DIRECTORY_SEPARATOR): string {
        return str_replace(array(
            "/",
            "//",
            "///",
            "\\",
            "\\\\",
            "\\\\\\",
        ), $slashes, $path);
    }
}