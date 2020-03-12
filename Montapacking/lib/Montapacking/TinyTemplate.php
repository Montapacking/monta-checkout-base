<?php

class Montapacking_TinyTemplate
{
    var $sLocalTemplateRoot;
    var $sDefaultTemplateRoot;
    var $sTemplateFile;
    var $aStylesheets = array();
    var $aJavaScript = array();
    var $aVars = array();

    public function __construct()
    {
        $this->setDefaultTemplateRoot("views/");
    }

    function getLocalFileName($sFileName)
    {
        return BASE_PATH . "Montapacking/" . $this->sLocalTemplateRoot . $sFileName;
    }

    function getFileName($sFileName)
    {
        return BASE_PATH . "Montapacking/" . $this->sDefaultTemplateRoot . $sFileName;
    }

    function setTemplateRoot($sTemplateRoot)
    {
        $this->sLocalTemplateRoot = $sTemplateRoot;
    }

    function setDefaultTemplateRoot($sTemplateRoot)
    {
        $this->sDefaultTemplateRoot = $sTemplateRoot;
    }

    function getLocalTemplateRoot()
    {
        return $this->sLocalTemplateRoot;
    }

    function setFile($sFileName)
    {
        if (!$this->checkIfFileExists($sFileName)) {
            throw new Exception("File " . $sFileName . " does not exist");
        }

        $this->sTemplateFile = $sFileName;
    }

    function getFile()
    {
        return $this->sTemplateFile;
    }

    function assign($sVarName, $mValue, $bOverwrite = true)
    {
        if (is_string($sVarName) === false) {
            trigger_error("'" . $sVarName . "' is not a string", E_USER_ERROR);
        }

        if (isset($this->aVars[$sVarName]) === false || $bOverwrite == true) {
            $this->aVars[$sVarName] = $mValue;
        } else {
            trigger_error("'" . $sVarName . "' is already set. use bOverwrite to assign.", E_USER_ERROR);
        }
    }

    function process($bStdOut = false)
    {
        $sOutput = $this->includeFile($this->sTemplateFile);

        if ($bStdOut === true) {
            echo $sOutput;
        }

        return $sOutput;
    }

    function checkIfFileExists($sFileName)
    {
        if ($this->getLocalTemplateRoot() !== null) {
            if (file_exists($this->getLocalFileName($sFileName))) {
                return true;
            }
        }

        return file_exists($this->getFileName($sFileName));
    }

    function includeFile($sFileName)
    {
        // Declare $oController for using it in the TPL files
        //$oController = SC_Controller::getInstance();

        foreach ($this->aVars as $sVarName => $mValue) {
            $$sVarName = $mValue;
        }

        ob_start();
        if ($this->getLocalTemplateRoot() !== null && file_exists($this->getLocalFileName($sFileName))) {
            include($this->getLocalFileName($sFileName));
        } else if (file_exists($this->getFileName($sFileName)) && trim($sFileName) != "") {
            include($this->getFileName($sFileName));
        } else {
            if (trim($sFileName)) {
                //Generate warning
                trigger_error("File " . $this->getFileName($sFileName) . "' wasn't found", E_USER_WARNING);
            }
        }

        $sOutput = ob_get_clean();
        return $sOutput;
    }

    public function getVar($sVarName)
    {
        if (array_key_exists($sVarName, $this->aVars)) {
            return $this->aVars[$sVarName];
        }
    }

    public function addStylesheet($sCss)
    {
        $this->aStylesheets[] = $sCss;
    }

    public function getStylesheets()
    {
        return $this->aStylesheets;
    }

    public function getStylesheetPaths()
    {
        $aPaths = array();
        foreach ($this->aStylesheets as $sStylesheet) {

            if (file_exists(BASE_PATH . "Montapacking/css/" . $sStylesheet)) {
                $aPaths[] = "/Montapacking/css/" . $sStylesheet . "?" . filemtime(BASE_PATH . "Montapacking/css/" . $sStylesheet);
            }

        }
        return $aPaths;
    }

    public function addJavaScript($sJavaScript)
    {
        $this->aJavaScript[] = $sJavaScript;
    }

    public function getJavaScripts()
    {
        return $this->aJavaScript;
    }

    public function getJavaScriptPaths()
    {
        $aPaths = array();
        foreach ($this->aJavaScript as $sJavaScript) {

            $pos = strpos($sJavaScript, 'http');

            if ($pos !== false) {
                $aPaths[] = $sJavaScript;
            } else if (file_exists(BASE_PATH . "Montapacking/javascripts/" . $sJavaScript)) {
                $aPaths[] = "/Montapacking/javascripts/" . $sJavaScript . "?" . filemtime(BASE_PATH . "Montapacking/javascripts/" . $sJavaScript);
            }
        }
        return $aPaths;
    }

    public static function printTemplate($sTemplateFilename, $aTemplateParams, $sTemplateRoot = "templates/default/")
    {
        $oTemplateEngine = new SC_Template_TinyTemplate();
        $oTemplateEngine->setDefaultTemplateRoot($sTemplateRoot);
        $oTemplateEngine->setFile($sTemplateFilename);

        foreach ($aTemplateParams as $sParamName => $mParamValue) {
            $oTemplateEngine->assign($sParamName, $mParamValue);
        }

        return $oTemplateEngine->process();
    }

    /**
     * getAreaType return template agent/client template type to be included
     *
     * @return String
     */
    public function getAreaType()
    {
        return SC_Controller::getInstance()->getCurrentUser()->getCompany()->isAgent() ? 'agent' : 'client';
    }

}

?>