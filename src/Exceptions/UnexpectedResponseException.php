<?php namespace Pisa\GizmoAPI\Exceptions;

/**
 * Exception when a response or the type of the response isn't what was expected
 */
class UnexpectedResponseException extends \UnexpectedValueException implements GizmoAPIException
{
}
