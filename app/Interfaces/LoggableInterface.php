<?php

    namespace App\Interfaces;

    interface LoggableInterface
    {
        public function logs();

        public function buildChangedDataStructure($originalData, $submittedData);

        public function processOriginalData($originalData);
    }
