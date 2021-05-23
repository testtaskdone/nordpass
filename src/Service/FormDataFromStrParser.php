<?php

namespace App\Service;

class FormDataFromStrParser
{
    public function parse(string $content): array
    {
        preg_match_all('#Content-Disposition: form-data; name="([^"]*)"\r?\n\r?\n([^------]*)#', $content, $m);

        return array_combine($m[1], array_map('trim', $m[2]));
    }
}
