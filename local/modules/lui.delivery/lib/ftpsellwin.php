<?php

namespace Lui\Delivery;

class FtpSellwin
{
    protected $server;
    protected $username;
    protected $password;
    protected $file;
    protected $fileFtp;
    protected $conn_id;
    protected $isAuth = false;

    /**
     * FtpSellwin constructor.
     * @param $server
     * @param $username
     * @param $password
     */
    public function __construct($server, $username, $password)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return resource
     */
    public function connect()
    {
        return $this->conn_id = ftp_connect($this->server, 21, 30);
    }

    /**
     * @return bool
     */
    public function Login()
    {
        $r = false;
        if ($this->conn_id) {
            if ($r = @ftp_login($this->conn_id, $this->username, $this->password)) {
                $this->isAuth = true;
            }
        }
        return $r;
    }

    /**
     * @param $file
     * @return bool
     */
    public function SetFile($file)
    {
        $r = false;
        if (file_exists($file)) {
            $this->file = $file;
            $info = pathinfo($file);
            $this->fileFtp = $info['filename'] . '.' . $info['extension'];
            $r = true;
        }
        return $r;
    }

    /**
     * @return bool
     */
    public function Upload()
    {
        $upload = false;
        if ($this->isAuth) {
            $upload = ftp_put($this->conn_id, '/' . $this->fileFtp, $this->file, FTP_BINARY);
            ftp_close($this->conn_id);
        }
        return $upload;
    }

    /**
     * @param $file
     * @param bool $un
     * @return bool
     */
    public static function UploadFile($file, $un = false)
    {
        $r = false;
        $ob = new self('ftp2.sellwin.by', 'Selvin\FTP_FOR_SITE', 'Astra29%');
        if ($ob->connect() and $ob->Login() and $ob->SetFile($file)) {
            if ($r = $ob->Upload()) {
                if ($un) {
                    unlink($file);
                }
            }
        }
        return $r;
    }


}
