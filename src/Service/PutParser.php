<?php

namespace App\Service;

class PutParser
{
    public function getData($request): array
    {
        $content = $request->getContent();
        $contentType = $request->headers->get('Content-Type');
        if (!preg_match('/boundary=(.*)$/', $contentType, $matches)) {
            throw new \RuntimeException('Boundary not found in Content-Type header.');
        }
        $boundary = $matches[1];

        //разделяем данные по boundary
        $parts = preg_split('/-+' . preg_quote($boundary, '/') . '/', $content);
        if (!$parts) {
            throw new \RuntimeException('Failed to parse multipart/form-data.');
        }

        //убираем пустые части
        $parts = array_filter($parts);

        //парсим данные
        $parsedData = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if (empty($part)) {
                continue;
            }

            // Разделяем заголовки и тело части
            if (strpos($part, "\r\n\r\n") !== false) {
                [$headers, $body] = explode("\r\n\r\n", $part, 2);
                $headers = trim($headers);
                $body = trim($body);

                // Извлекаем имя параметра из заголовков
                if (preg_match('/Content-Disposition:.*name="([^"]+)"/', $headers, $matches)) {
                    $name = $matches[1];
                    $parsedData[$name] = $body;
                }
            }
        }

        return $parsedData;
    }
}
