<?php
namespace FileTransfer\Transfer;

use FileTransfer\FileTransferException;
use FileTransfer\TransInterface\TransferAbstract;

class SshTransfer extends TransferAbstract
{
    const DEFAULT_PORT = 2222;

    /**
     * @param $host
     * @param $port
     *
     * @throws FileTransferException
     */
    protected function setConnect($host, $port)
    {
        if (!extension_loaded('ssh2')) {
            throw new FileTransferException('SSH2 extension not found');
        }

        $connect = ssh2_connect($host, $port);
        if ($connect === false) {
            throw new FileTransferException('SSH is not connected');
        }

        $this->connect = $connect;
    }

    /**
     * @param $connect
     * @param $user
     * @param $pass
     *
     * @throws FileTransferException
     */
    protected function login(&$connect, $user, $pass)
    {
        if (!ssh2_auth_password($connect, $user, $pass)) {
            throw new FileTransferException('SSH connect cant to login');
        }
    }

    /**
     * @param $cmd
     *
     * @return string
     * @throws FileTransferException
     */
    public function exec($cmd)
    {
        if (!($stream = ssh2_exec($this->connect, $cmd))) {
            throw new FileTransferException('SSH command failed');
        }
        stream_set_blocking($stream, true);
        $data = "";
        while ($buf = fread($stream, 4096)) {
            $data .= $buf;
        }
        fclose($stream);
        return $data;
    }

    /**
     * close connection
     */
    public function close() {
        $this->exec('echo "EXITING" && exit;');
        $this->connect = null;
    }

    /**
     * @return $this
     */
    public function pwd() {
        $this->exec('pwd');
        return $this;
    }

    /**
     * @param $path
     *
     * @return $this
     */
    public function cd($path) {
        $this->exec('cd '.$path);
        return $this;
    }

    /**
     * @param $file
     *
     * @return $this
     * @throws FileTransferException
     */
    public function upload($file) {
        $fileName = basename($file);
        if(!ssh2_scp_send($this->connect, $file, $fileName)){
            throw new FileTransferException('Error SSH uploading file');
        }
        return $this;
    }

    /**
     * @param $file
     *
     * @return $this
     * @throws FileTransferException
     */
    public function download($file) {
        $fileName = basename($file);
        if(!ssh2_scp_recv($this->connect, $file, $fileName)){
            throw new FileTransferException('Error SSH downloading file');
        }
        return $this;
    }
}