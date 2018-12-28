<?php

namespace Dotmaui;


class Paste
{
    public $Author;
    public $Expiration;
    public $Exposure;
    public $Language;
    public $Text;
    public $Theme;
    public $Title;

    function __construct()
    {
        $this->Author     = "A guest";
        $this->Expiration = "N";
        $this->Exposure   = "public";
        $this->Language   = "text";
        $this->Theme      = "default";
    }
}