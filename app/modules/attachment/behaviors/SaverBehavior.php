<?php
namespace app\modules\attachment\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\UploadedFile;

/**
 * Behavior for managing image
 *
 * @author mlapko <maxlapko@gmail.com>
 * @version 0.1
 * 
 * 
 *   public function behaviors()
 *   {
 *       return [
 *          'fileSaver' => [
 *               'class' => '\app\modules\attachment\behaviors\SaverBehavior',
 *           ]
 *       ];
 *   }
 * 
 */
class SaverBehavior extends Behavior
{       
    /**
     * Attachment path
     * @var string 
     */
    public $filePath = '@webroot/files/attachments';
    
    /**
     * Attachment url
     * @var string 
     */
    public $fileUrl = '@web/files/attachments';
    
    /**
     * File mode for new files
     * @var integer 
     */
    public $fileMode = 0777;
    
    public $prefixPathCallback;
    
    /**
     * Get Path for file
     * @param string $attribute
     * 
     * @return string 
     */
    public function getFilePath($attribute)
    {
        return Yii::getAlias($this->filePath . $this->getSubDir($attribute) . DIRECTORY_SEPARATOR . $this->owner->$attribute);
    }
    
    /**
     * Get Path for file
     * @param string $attribute
     * 
     * @return string 
     */
    public function getFileUrl($attribute)
    {
        return Yii::getAlias($this->fileUrl . $this->getSubDir($attribute) . DIRECTORY_SEPARATOR . $this->owner->$attribute);
    }   
    
    /**
     * Upload image
     * @param UploadedFile $file
     * @param string $attribute
     * @param boolean $deleteOld
     * @return boolean 
     */
    public function uploadFile($file, $attribute, $deleteOld = true)
    {
        $isObject = $file instanceof UploadedFile;
        
        if ($file && (
            ($isObject && $file->tempName) || 
            (!$isObject && ($file = $this->getFileFromString($file)) !== false)
        )) {
            if ($deleteOld) {
                $this->deleteFile($attribute);
            }
            $this->owner->$attribute = uniqid() . '.' . $file->extension;
            $fullPath = $this->createDir($this->getSubDir($attribute)) . DIRECTORY_SEPARATOR . $this->owner->$attribute;
            if ($isObject) {
                $file->saveAs($fullPath);
            } else {
                rename($file, $fullPath);
            }
            return true;
        } else {
            $this->owner->$attribute = $this->owner->getOldAttribute($attribute);            
        }
        return false;
    }
    
    /**
     * Delete image for 
     * @param string $attribute
     * @return boolean 
     */
    public function deleteFile($attribute)
    {        
        if ($this->owner->$attribute) {
            $this->owner->$attribute = null;
            $filename = $this->getFilePath($attribute);
            if (file_exists($filename)) {
                return unlink($filename);
            }
        }
        return false;
    }
    
    public function getSubDir($attribute)
    {
        if ($this->prefixPathCallback) {
            return call_user_func($this->prefixPathCallback, $this->owner, $attribute);
        }
        return DIRECTORY_SEPARATOR . $this->owner->formName() . DIRECTORY_SEPARATOR . 
            substr(md5($this->owner->$attribute), 0, 2);
    }
    
    /**
     * 
     * @param type $subDir
     * @param type $prefix
     * @return type
     */
    private function createDir($subDir, $prefix = true)
    {
        $directory = $prefix ? Yii::getAlias($this->filePath) . $subDir : $subDir;
        if (!is_dir($directory)) {
            mkdir($directory, $this->fileMode, true);
            chmod($directory, $this->fileMode);
        }
        return $directory;
    }
    
    /**
     * Return file if exists or upload from network and save to tmp dir
     * @param string $filename
     * @return boolean|string
     */
    private function getFileFromString($filename)
    {
        if (file_exists($filename)) {
            return $filename;
        } elseif (preg_match('/^https?:\/\//', $filename) && ($content = file_get_contents($filename))) {
            $filename = tempnam(sys_get_temp_dir(), 'fs');
            file_put_contents($filename, $content);
            return $filename;
        }
        return false;
    }
}