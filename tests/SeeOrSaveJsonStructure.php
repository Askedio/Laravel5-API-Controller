<?php

namespace Askedio\Tests;

trait SeeOrSaveJsonStructure
{
    /**
     * See a json result from a file or save json result to file.
     */
    public function seeOrSaveJsonStructure()
    {
        if (!env('RESPONSE_FOLDER')) {
            return $this;
        }

        $this->setup();

        $file = rtrim(env('RESPONSE_FOLDER'), '\\/').DIRECTORY_SEPARATOR.class_basename(debug_backtrace()[1]['class']).'-'.debug_backtrace()[1]['function'].'.json';

        if (!env('SAVE_RESPONSES', false) && file_exists($file)) {
            $this->seeJsonStructure(json_decode(file_get_contents($file), true));

            return $this;
        }

        file_put_contents($file, json_encode($this->getKeys($this->response->getContent())));

        return $this;
    }

    /**
     * Create the response folder.
     *
     * @return void
     */
    private function setup()
    {
        if (!is_dir(env('RESPONSE_FOLDER'))) {
            mkdir(env('RESPONSE_FOLDER'), 0600, true);
        }
    }

    /**
     * Return a array of keys.
     *
     * @param array $array
     *
     * @return array
     */
    private function arrayKeys($array)
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, [$key => array_merge(array_keys($value), $this->arrayKeys($value))]);
            }
        }

        return $results;
    }

    /**
     * Get keys from a json array.
     *
     * @param string $content
     *
     * @return arrayKeys
     */
    private function getKeys($content)
    {
        return $this->arrayKeys(json_decode($content, true));
    }
}
