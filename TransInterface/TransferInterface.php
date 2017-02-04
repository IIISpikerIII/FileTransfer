<?php
namespace FileTransfer\TransInterface;

interface TransferInterface
{
    public function download($path);

    public function upload($path);

    public function exec($cmd);

    public function pwd();

    public function cd($path);

    public function close();
}