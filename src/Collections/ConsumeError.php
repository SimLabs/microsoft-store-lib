<?php

namespace MicrosoftStoreLib\Collections;

use Symfony\Component\Serializer\Annotation\SerializedName;

/// <summary>
/// Error data from the consume service if the consume request failed
/// </summary>
class ConsumeError
{
    public function __construct()
    {
        $this->Details = [];
        $this->Data = [];
    }
    /// <summary>
    /// Error code related to the consume service
    /// </summary>
    /**
     * @SerializedName("code")
     */
    public string $Code;

    /// <summary>
    /// Additional data about the error
    /// </summary>
    /**
     * @SerializedName("data")
     */
    public array/*<string>*/ $Data;

    /// <summary>
    /// Additional details about the error
    /// </summary>
    /**
     * @SerializedName("details")
     */
    public array $Details;

    /// <summary>
    /// Message describing the error
    /// </summary>
    /**
     * @SerializedName("message")
     */
    public string $Message;

    /// <summary>
    /// Source of the error being reported
    /// </summary>
    /**
     * @SerializedName("source")
     */
    public string $Source;
}
