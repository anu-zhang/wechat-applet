<?php
namespace PhpRush\Wechat\Applet;

use PhpRush\Wechat\Applet\Exceptions\IllegalIvException;
use PhpRush\Wechat\Applet\Exceptions\IllegalAesKeException ;
use PhpRush\Wechat\Applet\Exceptions\IllegalBufferException;

class WXBizDataCrypt
{

    private $appid;

    private $sessionKey;

    /**
     * ���캯��
     *
     * @param $sessionKey �û���С�����¼���ȡ�ĻỰ��Կ            
     * @param $appid С�����appid            
     */
    function __construct($appid, $sessionKey)
    {
        $this->appid = $appid;
        $this->sessionKey = $sessionKey;
    }

    /**
     * �������ݵ���ʵ�ԣ����һ�ȡ���ܺ������.
     *
     * @param $encryptedData ���ܵ��û�����            
     * @param $iv ���û�����һͬ���صĳ�ʼ����            
     * @param $data ���ܺ��ԭ��            
     *
     * @return int �ɹ�0��ʧ�ܷ��ض�Ӧ�Ĵ�����
     */
    public function decryptData($encryptedData, $iv)
    {
        if (strlen($this->sessionKey) != 24) {
            throw new IllegalAesKeException("���Ϸ���AesKey");
        }
        $aesKey = base64_decode($this->sessionKey);
        if (strlen($iv) != 24) {
            throw new IllegalIvException("���Ϸ���Iv");
        }
        
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $pc = new Prpcrypt($aesKey);
        
        $result = $pc->decrypt($aesCipher, $aesIV);
        
        $data = json_decode($result, true);
        if ($data == NULL) {
            throw new IllegalBufferException("���Ϸ���Buffer");
        }
        
        if (array_get($data, 'watermark.appid') != $this->appid) {
            throw new IllegalBufferException("���Ϸ���Buffer");
        }
        
        return $data;
    }
}