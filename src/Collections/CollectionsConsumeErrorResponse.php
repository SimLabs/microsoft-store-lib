<?php

namespace MicrosoftStoreLib\Collections;

use Symfony\Component\Serializer\Annotation\SerializedName;

/// <summary>
/// JSON response body if there was an error executing the consume request
/// </summary>
class CollectionsConsumeErrorResponse
{
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
    public array/*<string>*/ $Data = [];

    /// <summary>
    /// Additional details about the error
    /// </summary>
    /**
     * @SerializedName("details")
     */
    public array $Details = [];

    /// <summary>
    /// Error data from the consume service if the consume request failed
    /// </summary>
    /**
     * @SerializedName("innererror")
     */
    public ConsumeError $InnerError;

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
