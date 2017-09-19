<?php

namespace CrestApps\CodeGenerator\Commands\Bases;

use CrestApps\CodeGenerator\Support\Config;
use CrestApps\CodeGenerator\Support\FieldTransformer;
use CrestApps\CodeGenerator\Support\ResourceTransformer;
use CrestApps\CodeGenerator\Traits\CommonCommand;
use Illuminate\Console\Command;

class ResourceFileCommandBase extends Command
{
    use CommonCommand;

    protected function getJson(array $resource)
    {
        return json_encode($resource, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    protected function getResources($file)
    {
        return ResourceTransformer::fromJson($this->getFileContent($file), 'crestapps');
    }
    /**
     * Gets the destenation filename.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getFilename($name)
    {
        $path = base_path(Config::getFieldsFilePath());

        return $path . $name;
    }

    /**
     * Get primary key properties.
     *
     * @param object $input
     *
     * @return array
     */
    protected function getFields(array $fieldNames, array $transaltionFor)
    {
        //a,b,c
        //OR
        //name:a;html-type:select;options:first|second|third|fourth
        $fields = [];

        foreach ($fieldNames as $fieldName) {
            $properties = [];

            if (str_contains($fieldName, ':')) {
                // Handle the following format
                // name:a;html-type:select;options:first|second|third|fourth
                if (!str_contains($fieldName, 'name:')) {
                    throw new Exception('The "name:" property is not present and is required!');
                }

                $parts = explode(';', $fieldName);

                foreach ($parts as $part) {

                    if (str_contains($part, ':')) {
                        list($key, $value) = explode(':', $part);

                        $properties[$key] = $value;

                        if ($key == 'options') {
                            $options = explode('|', $value);

                            $properties['options'] = [];

                            foreach ($options as $option) {
                                $optionTitle = FieldTransformer::convertNameToLabel($option);

                                if (count($transaltionFor) == 0) {
                                    $properties['options'][$option] = $optionTitle;
                                }

                                foreach ($transaltionFor as $lang) {
                                    if (!isset($properties['options'][$lang])) {
                                        $properties['options'][$lang] = [];
                                    }
                                    $properties['options'][$lang][$option] = $optionTitle;
                                }
                            }
                        }
                    } else {
                        $properties['name'] = $part;
                    }
                }
            } else {
                // handle the following format
                // a,b,c
                $properties['name'] = $fieldName;
            }

            $label = FieldTransformer::convertNameToLabel($properties['name']);

            foreach ($transaltionFor as $lang) {
                $properties['label'][$lang] = $label;
            }

            $fields[] = $properties;
        }

        return FieldTransformer::fromArray($fields, 'generic');
    }

    protected function noResourcesProvided()
    {
        $this->error('Nothing to append was provided! Please use the --fields, --relations, or --indexes to append to file.');
    }
}