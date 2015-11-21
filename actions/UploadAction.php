<?php

namespace bajadev\ckeditor\actions;

use Yii;
use yii\web\ViewAction;
use yii\helpers\Inflector;
use Imagine\Image\Box;
use yii\imagine\Image;

/**
 * Class BrowseAction
 * @package bajadev\ckeditor\actions
 */
class UploadAction extends ViewAction
{

    public $url;
    public $path;
    public $maxWidth = 800;
    public $maxHeight = 800;

    public function init()
    {
        parent::init();
        Yii::$app->controller->enableCsrfValidation = false;
    }


    /**
     * @inheritdoc
     */
    public function run()
    {

        if(Yii::$app->request->isPost) {
            $image = \yii\web\UploadedFile::getInstanceByName('upload');
            $imageFileType = strtolower(pathinfo($image->name, PATHINFO_EXTENSION));
            $allowed = ['png', 'jpg', 'gif', 'jpeg'];
            if(!empty($image) and in_array($imageFileType, $allowed)) {
                $fileName = Inflector::slug(str_replace($imageFileType, '',$image->name), '_');
                $fileName = $fileName.'.'.$imageFileType;
                $image->saveAs($this->getPath().$fileName);
                Image::frame($this->getPath().$fileName)
                    ->thumbnail(new Box($this->maxWidth, $this->maxHeight))
                    ->save($this->getPath().$fileName, ['quality' => 100]);
            }

        }

    }

    /**
     * @return string
     */
    private function getUrl()
    {
        return Yii::getAlias($this->url);
    }

    /**
     * @return string
     */
    private function getPath()
    {
        return Yii::getAlias($this->path);
    }
}
