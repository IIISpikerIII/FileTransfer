<?php

namespace FileTransfer;

use FileTransfer\Transfer\FtpTransfer;
use FileTransfer\Transfer\SshTransfer;

require_once('autoloader.php');

class Factory
{
    public function getConnection($type, $user, $pass, $host, $port = false)
    {
        switch ($type) {
            case 'ftp':
                return new FtpTransfer($user, $pass, $host, $port);
            case 'ssh':
                return new SshTransfer($user, $pass, $host, $port);
        }

        throw new FileTransferException('Connection type not found');
    }

}

class FileTransferException extends \Exception {}