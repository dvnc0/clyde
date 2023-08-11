<?php
namespace Clyde\Objects;

abstract class Printer_Object_Base {
    public string $error = '1;37;41';
    public string $warning = '0;33';
    public string $alert = '0;31';
    public string $message = '0;37';
    public string $info = '1;34';
    public string $success = '0;32';
    public string $banner = '1;37;45';
    public string $caption = '0;90';
}