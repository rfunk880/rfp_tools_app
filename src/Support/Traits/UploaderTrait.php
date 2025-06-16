<?php
namespace Support\Traits;

use Support\Helpers\AttachmentFactory;
use Support\Helpers\Factory;
use Support\Services\Image\ImageManipulator;

trait UploaderTrait
{
    private $attachmentModel;
    private $uploadService;
    private $uploadType = '';
    private $originalName = null;
    private $title = '';
    private $remarks;

    private $uploadPath = './storage/uploads/attachments';

    /**
     * @return string
     */
    public function getUploadPath()
    {
        return $this->uploadPath;
    }

    public function setUploadFileName($val)
    {
        $this->originalName = $val;
        return $this;
    }

    public function setRemarks($val)
    {
        $this->remarks = $val;
        return $this;
    }

    public function setUploadPath($uploadPath)
    {
        $this->uploadPath = $uploadPath;
        return $this;
    }

    public function getAttachmentModel()
    {
        return $this->attachmentModel;
    }


    /**
     * @return mixed
     */
    public function getUploadService()
    {
        return $this->uploadService;
    }


    public function getUploadType()
    {
        return $this->uploadType;
    }

    public function setUploadType($uploadType)
    {
        $this->uploadType = $uploadType;
        return $this;
    }


    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }


    public function uploadMedia($fileHandler, $resize = false)
    {
        $this->attachMedia($fileHandler);
        /*$model->attachments()->save($this->getAttachmentModel());*/
        if ($resize) {
            $this->resize();
        }

        $this->getAttachmentModel()->save();
        return $this->getAttachmentModel();
    }

    public function uploadMediaForModel($model, $fileHandler, $dynamicAttachment = null, $resize = false)
    {
        $this->attachMedia($fileHandler);
        if (!is_null($dynamicAttachment) && is_callable($dynamicAttachment)) {
            $dynamicAttachment($model, $this->getAttachmentModel());
        } else {
            $model->attachments()->save($this->getAttachmentModel());
        }

        if ($resize) {
            // $this->resize();
        }


        return $this->getAttachmentModel();
    }


    public function uploadFileForModel($model, $attachmentModel, $dynamicAttachment = null, $resize = false)
    {
        if (!is_null($dynamicAttachment) && is_callable($dynamicAttachment)) {
            $dynamicAttachment($model, $attachmentModel);
        } else {
            $model->attachments()->save($attachmentModel);
        }

        if ($resize) {
            $this->resize();
        }


        return $this->getAttachmentModel();
    }


    public function attachMedia($fileHandler)
    {
        if (is_null($fileHandler)) {
            return '';
        }
        $this->uploadService = Factory::UploadService($fileHandler, $this->getUploadPath());
        $this->uploadService->upload();
        $this->attachmentModel = Factory::NewAttachment(array(
            'filename' => $this->uploadService->getUploadedName(),
            'original_name' => $this->getOriginalName(),
            'folder' => $this->uploadService->getUploadPath(),
            'mime_type' => $this->uploadService->getClientMimeType(),
            'filesize' => $this->uploadService->getFileSize(),
            'type' => $this->uploadType,
            'title' => $this->title,
            'remarks' => $this->remarks
        ));

        /*$this->image->resize($this->uploadService->getUploadedName(), $this->uploadService->getUploadPath().'/');*/
        /*return array('id' => $attachmentModel->id, 'service' => $this->uploadService);*/
    }

    public function getOriginalName()
    {
        if (!is_null($this->originalName)) {
            return $this->originalName . '.' . $this->uploadService->getClientOriginalExtension();
        }
        return $this->uploadService->getClientOriginalName();
    }


    public function deleteOldMedia($mediaID, $physicalDelete = false)
    {

        /*lets delete old one*/
        $oldAttachmentModel = Attachment::findOrFail($mediaID);
        if (!is_null($oldAttachmentModel)) {
            $oldAttachmentModel->selfDestruct($physicalDelete);
        }
    }

    public function resize()
    {
        $this->uploadService = $this->getUploadService();
        $this->getImageManipulator()->resize($this->uploadService->getUploadedName(), rtrim($this->uploadService->getUploadPath(), '/') . '/');
        /*$source = new Source($this->getUploadService()->getFullPath());
        $destination = new Destination($this->getUploadService()->getUploadPath());
        //ed($source->getSourcePath());
        $resizer = new Resizer($source);
        $resizer->resize($destination);*/
    }

    public function getImageManipulator()
    {
        return new ImageManipulator();
    }
}
