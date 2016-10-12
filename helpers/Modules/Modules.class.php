<?php

class Modules
{

    static $AttachedModules = array();

    static function AttachModule($moduleName, $hasSeparateFolder = true, $needsToBeIncluded = false)
    {
        $moduleNames   = explode('.', $moduleName);
        $isAdminModule = false;

        if ($moduleNames[0] == 'Admin') {
            $moduleName    = $moduleNames[1];
            $isAdminModule = true;
        }

        $moduleNameUpper = strtoupper(($isAdminModule ? $moduleNames[0] : '') . $moduleName);

        if (!array_key_exists($moduleNameUpper, self::$AttachedModules)) {
            self::$AttachedModules[$moduleNameUpper] = 1;
            $path = BASEPATH . ($isAdminModule ? 'admin/' : '') . 'lib/' . ($hasSeparateFolder ? "wmp{$moduleName}/" : '') . "wmp{$moduleName}.php";

            if (file_exists($path)) {

                if ($needsToBeIncluded) {
                    include $path;
                } else {
                    require $path;
                }
                define("_MODULE_{$moduleNameUpper}", 1);

                return true;
            }
        }

        return false;
    }

}