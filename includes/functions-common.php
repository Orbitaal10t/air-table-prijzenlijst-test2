<?php
if (!defined('ABSPATH')) exit;

function airtable_field_to_text($field) {
    if ($field === null || $field === '') return '';
    if (is_array($field)) {
        if ((isset($field['state']) && $field['state'] === 'error') ||
            (isset($field['errorType']) && $field['errorType'] === 'emptyDependency')) {
            return '';
        }
        if (isset($field['value']) && is_string($field['value']) && trim($field['value']) !== '') {
            return trim($field['value']);
        }
        $parts = [];
        foreach ($field as $v) {
            if (is_string($v) && trim($v) !== '') $parts[] = trim($v);
        }
        return !empty($parts) ? implode(', ', $parts) : '';
    }
    return is_string($field) ? trim($field) : '';
}