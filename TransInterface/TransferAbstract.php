<?php
namespace FileTransfer\TransInterface;

use FileTransfer\TransInterface\TransferInterface as ITransfer;

abstract class TransferAbstract implements ITransfer
{
    const DEFAULT_PORT = 22;
    protected $user;
    protected $pass;
    protected $host;
    protected $port;
    protected $connect;

    abstract protected function setConnect($host, $port);
    abstract protected function login(&$connect, $user, $pass);

    public function __construct($user, $pass, $host, $port)
    {
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
        $this->port = $port?:static::DEFAULT_PORT;

        $this->setConnect($this->host, $this->port);
        $this->login($this->connect, $this->user, $this->pass);
    }

    public function __destruct() {
        $this->close();
    }
}