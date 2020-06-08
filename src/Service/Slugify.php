<?php


namespace App\Service;


class Slugify
{
    const AUTHORIZED = [
        'a' => ["à", 'â'],
        'c' => ["ç"],
        'e' => ['é', 'è', 'ê'],
        'o' => ['ô'],
        'u' => ['û', 'ù'],
        '' => ['!', '\'', '?', ';','\"',"\:", '.', ','],
    ];

    public function generate(string $input) : string
    {
        $input = trim($input);

        foreach(self::AUTHORIZED as $authorizedCaracter => $forbiddenCaracter)
        {
            $input = str_replace($forbiddenCaracter, $authorizedCaracter, $input);
        }
        $input = str_replace("/[^a-zA-Z0-9 ]/g", '', $input);
        $input = str_replace(' ', '-', $input);

        while(strpos($input, "--") !== FALSE)
        {
            $input = str_replace('--', '-', $input);
        }
        $input = strtolower($input);

        return $input;
    }
}