<?php

namespace MicrosoftStoreLib\Submissions;

class ErrorInfo
{
    public string $code = '';
    public string $details = '';
}

class StatusDetails
{
    /**
     * @var ErrorInfo[]
     */
    public $errors = [];
    /**
     * @var ErrorInfo[]
     */
    public $warnings = [];
    public $certificationReports;

    public function addError(ErrorInfo $item)
    {
        $this->errors[] = $item;
    }

    public function removeError(ErrorInfo $item)
    {
        foreach ($this->errors as $i => $error) {
            if ($item->code === $error->code) {
                unset($this->errors[$i]);
                break;
            }
        }
    }

    public function addWarning(ErrorInfo $item)
    {
        $this->warnings[] = $item;
    }

    public function removeWarning(ErrorInfo $item)
    {
        foreach ($this->warnings as $i => $warning) {
            if ($item->code === $warning->code) {
                unset($this->warnings[$i]);
                break;
            }
        }
    }
}

class AddOnSubmissionResource extends UpdateAddOnSubmissionResource
{
    public string $status = '';
    public StatusDetails $statusDetails;
    public string $fileUploadUrl = '';
    public string $friendlyName = '';

    // public function getUpdateResourceSlice()
    // {
    //     $copy_of_object = clone $this;

    //     $ref = new \ReflectionClass(AddOnSubmissionResource::class);
    //     $ownProps = array_filter($ref->getProperties(), function ($property) use($ref) {
    //         return $property->getDeclaringClass()->getName() == $ref->getName();
    //     });

    //     foreach($ownProps as $property)
    //     {
    //         unset($copy_of_object->{$property->getName()});
    //     }

    //     return $copy_of_object;
    // }
}
