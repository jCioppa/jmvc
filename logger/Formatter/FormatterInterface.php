<?php

namespace Logger\Formatter;

interface FormatterInterface
{
    public function format(array $record);
    public function formatBatch(array $records);
}
