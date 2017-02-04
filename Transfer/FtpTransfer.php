<?php
namespace FileTransfer\Transfer;

use FileTransfer\FileTransferException;
use FileTransfer\TransInterface\TransferAbstract;

class FtpTransfer extends TransferAbstract
{
    const DEFAULT_PORT = 21;

    /**
     * Set connection to $connect
     * @param $host
     * @param $port
     *
     * @throws FileTransferException
     */
    protected function setConnect($host, $port)
    {
        if (!extension_loaded('ftp')) {
            throw new FileTransferException('FTP extension not found');
        }

        $connect = ftp_connect($host, $port);
        if ($connect === false) {
            throw new FileTransferException('FTP is not connected');
        }

        $this->connect = $connect;
    }

    /**
     * Auth connect by user data
     * @param $connect
     * @param $user
     * @param $pass
     *
     * @throws FileTransferException
     */
    protected function login(&$connect, $user, $pass)
    {
        if (!ftp_login($connect, $user, $pass)) {
            throw new FileTransferException('FTP connect cant to login');
        }
    }

    /**
     * @return string
     * @throws FileTransferException
     */
    public function pwd()
    {
        $dName = ftp_pwd($this->connect);
        if ($dName === false) {
            throw new FileTransferException('Error FTP pwd method');
        }

        return $dName;
    }

    /**
     * @param $file
     *
     * @throws FileTransferException
     */
    public function upload($file)
    {
        $fileName = basename($file);
        $ret = ftp_nb_put($this->connect, $fileName, $file, FTP_BINARY);
        while ($ret == FTP_MOREDATA) {
            $ret = ftp_nb_continue($this->connect);
        }
        if ($ret != FTP_FINISHED) {
            throw new FileTransferException('Error FTP uploading file');
        }
    }

    /**
     * @param $file
     *
     * @throws FileTransferException
     */
    public function download($file)
    {
        $fileName = basename($file);
        $ret = ftp_nb_get($this->connect, $fileName, $file, FTP_BINARY);
        while ($ret == FTP_MOREDATA) {
            $ret = ftp_nb_continue($this->connect);
        }
        if ($ret != FTP_FINISHED) {
            throw new FileTransferException('Error FTP downloading file');
        }
    }

    /**
     * Close connection
     */
    public function close()
    {
        ftp_close($this->connect);
        $this->connect = null;
    }

    /**
     * @param $cmd
     *
     * @return array
     */
    public function exec($cmd)
    {
        return ftp_raw($this->connect, $cmd);
    }

    /**
     * @param $path
     *
     * @return bool
     */
    public function cd($path)
    {
        return ftp_chdir($this->connect, $path);
    }
}