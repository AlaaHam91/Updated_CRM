<?php


namespace Traits;

/**
 * Trait TAdditionalField
 * @package Traits
 * @author Kamal Mohammed
 */
trait TAdditionalField
{
    /**
     * @param $data
     * @return mixed
     */
    private function createTextField($data)
    {
        $textfield = $this->textFieldRepository->create($data);
        $f = $this->textFieldRepository->createAdditionalField($textfield,$data);
        $this->additionalFieldRepository->createAssignments($f , $data);
        return $textfield;
    }

    /**
     * @param $data
     * @return mixed
     */
    private function createNumberField($data)
    {
        $numberfield = $this->numberFieldRepository->create($data);
        $f = $this->numberFieldRepository->createAdditionalField($numberfield,$data);
        $this->additionalFieldRepository->createAssignments($f , $data);
        return $numberfield;
    }

    /**
     * @param $data
     * @return mixed
     */
    private function createSelectMenu($data)
    {
        $select = $this->selectMenuRepository->create($data);
        $f = $this->selectMenuRepository->createAdditionalField($select, $data);
        $this->additionalFieldRepository->createAssignments($f , $data);
        return $select;
    }

    /**
     * @param $data
     * @return mixed
     */
    private function createFileUploader($data)
    {
        $uploader = $this->fileUploaderRepository->create($data);
        $f = $this->fileUploaderRepository->createAdditionalField($uploader,$data);
        $this->additionalFieldRepository->createAssignments($f , $data);
        return $uploader;
    }

    /**
     * @param $data
     */
    public function saveFileds($data)
    {
        foreach($data['fields'] as $item)
        {
            switch ($item['field_family'])
            {
                case 'text_field':
                    $this->createTextField($item);
                    break;
                case 'number_field':
                    $this->createNumberField($data);
                    break;
                case 'select_menu':
                    $this->createSelectMenu($item);
                    break;
                case 'file_uploader':
                    $this->createFileUploader($item);
                    break;
                default:
                    break;
            }
        }
    }
}
