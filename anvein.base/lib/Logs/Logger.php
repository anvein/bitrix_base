<?php

namespace Anvein\Base\Logs;

use Exception;
use CEventLog;

/**
 * Class Logger.
 */
class Logger
{
    const SEVERITY_INFO = 'INFO';
    const SEVERITY_ERROR = 'ERROR';
    const SEVERITY_WARNING = 'WARNING';

    const LOG_LEVEL_INFO = 0;
    const LOG_LEVEL_ERROR = 1;

    /**
     * ID типа события.
     *
     * @var string
     */
    protected $typeId = 'logger';

    /**
     * Файл, в который нужно будет продублировать лог.
     *
     * @var string
     */
    protected $filePath = '';

    /**
     * Logger constructor.
     *
     * @param string $typeId
     */
    public function __construct(string $typeId = '', string $filePath = '')
    {
        if (!empty($typeId)) {
            $this->typeId .= "_{$typeId}";
        }
        $this->filePath = $filePath;
    }

    /**
     * Добавляет ошибку в лог.
     *
     * @param Exception $e - объект ошибки
     */
    public function addErrorLog(Exception $e)
    {
        $message = "Message: {$e->getMessage()}. ";
        $message .= "File: {$e->getFile()}. Line: {$e->getLine()}. Trace: {$e->getTraceAsString()}.";

        CEventLog::Add([
            'SEVERITY' => self::SEVERITY_ERROR,
            'AUDIT_TYPE_ID' => $this->typeId,
            'DESCRIPTION' => $message,
        ]);

        if ($this->filePath) {
            file_put_contents($this->filePath, $message . "\r\n\r\n", FILE_APPEND);
        }

        return;
    }

    /**
     * Добавляет лог с указанием уровня критичности.
     *
     * @param string $message  - сообщение
     * @param int    $logLevel - уровень критичности: 0 - информационный, 1 - ошибка
     */
    public function addLog(string $message = '', int $logLevel = 0)
    {
        $severity = '';
        if ($logLevel === self::LOG_LEVEL_INFO) {
            $severity = self::SEVERITY_INFO;
        } elseif ($logLevel === self::LOG_LEVEL_ERROR) {
            $severity = self::SEVERITY_ERROR;
        }

        CEventLog::Add([
            'SEVERITY' => $severity,
            'AUDIT_TYPE_ID' => $this->typeId,
            'DESCRIPTION' => $message,
        ]);

        if ($this->filePath) {
            file_put_contents($this->filePath, $message . "\r\n\r\n", FILE_APPEND);
        }

        return;
    }
}
