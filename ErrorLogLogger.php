<?php

use Psr\Log\AbstractLogger;

class ErrorLogLogger extends AbstractLogger
{
  /**
   * Logs with an arbitrary level.
   *
   * @param mixed  $level
   * @param string $message
   * @param array $context
   *
   * @return void
   */
  public function log($level, $message, $context = [])
  {
    error_log(sprintf('%s: %s. Details: %s', $level, trim($message, '.'), json_encode($context)));
  }
}
