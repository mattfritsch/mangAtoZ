<?php

function validate(array $data, array $rules) : array {
  $formErrors = [];

  foreach ($rules as $fieldName => $ruleConfigs) {
    foreach ($ruleConfigs as $ruleConfig) {
      $callable = $ruleConfig['rule'];
      if (!is_callable($callable)) {
        throw new \LogicException('Not all validation rules for ' . $fieldName . ' are callable!');
      }

      $validationArgs = $ruleConfig['args'] ?? [];
      $fieldErrors = $callable($data, trim($data[$fieldName]), ...$validationArgs);
      //$args = array_merge([$data, trim($data[$fieldName])], $validationArgs);
      //$fieldErrors = call_user_func_array($callable, $args);

      if (!empty($fieldErrors)) {
        $formErrors[$fieldName] = $formErrors[$fieldName] ?? [];
        $formErrors[$fieldName] += $fieldErrors;
      }
    }
  }

  return $formErrors;
}

function required(array $data, string $fieldValue): array {
  if (strlen($fieldValue) === 0) {
    return ['Ce champs est obligatoire !'];
  }

  return [];
}

function length(array $data, string $fieldValue, int $min = null, int $max = null): array
{
  $errors = [];

  if (null !== $min && strlen($fieldValue) < $min) {
    $errors[] = 'Ce champs doit contenir au minimum ' . $min . ' caractères !';
  }

  if (null !== $max && strlen($fieldValue) > $max) {
    $errors[] = 'Ce champs doit contenir au maximum ' . $max . ' caractères !';
  }

  return $errors;
}

function sameAs(
  array $data,
  string $fieldValue,
  string $field,
  string $errorMessage = 'Ce champs n\'est pas valide !'
) {
  $errors = [];

  if ($data[$field] !== $fieldValue) {
    $errors[] = $errorMessage;
  }

  return $errors;
}

function checkbox(
  array $data,
  string $fieldValue,
  bool $checked,
  string $errorMessage = 'Valeur invalide'
) {
  $errors = [];

  $fieldChecked = in_array($fieldValue, ['1', 'on']);

  if ($fieldChecked !== $checked) {
    $errors[] = $errorMessage;
  }

  return $errors;
}

function uniq(
  array $data,
  $fieldValue,
  callable $callback,
  string $errorMessage = 'Entité non unique !'
): array {
  $errors = [];

  if ($callback($fieldValue) === false) {
    $errors[] = $errorMessage;
  }

  return $errors;
}










